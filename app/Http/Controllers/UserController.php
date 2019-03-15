<?php
namespace App\Http\Controllers;

use App\Http\Controllers\helpers\UserHelper;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->helper = new UserHelper();
    }

    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token' => $token,
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }
    /**
     * function - return all users information with permission object, URL: "users"
     *
     * @param Request $request
     * @return Response {users}
     */
    public function index(Request $request)
    {
        $users = $this->helper->fetchUsers($request);

        return response()->json(compact("users"), 200);
    }
    /**
     * function - user login with password and email addresss
     * Todo:: user can login with username not only email
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $user = \App\User::where('email', $request->email)->get()->first();
        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $user->api_token = $token;
            $user->save();
            $permissions = $user->permissions()->get();
            $response = ['success' => true, 'data' => ['id' => $user->user_id, 'api_token' => $user->api_token, 'name' => $user->username, 'email' => $user->email, 'permissions' => $permissions]];
        } else {
            $response = ['success' => false, 'data' => 'Record doesnt exists'];
        }

        return response()->json($response, 200);
    }
    public function register(Request $request)
    {
        $payload = [
            'password' => \Hash::make($request->password),
            'email' => $request->email,
            'username' => $request->username,
            'api_token' => '',
        ];

        $user = new \App\User($payload);
        if ($user->save()) {

            $token = self::getToken($request->email, $request->password); // generate user token

            if (!is_string($token)) {
                return response()->json(['success' => false, 'data' => 'Token generation failed'], 201);
            }

            $user = \App\User::where('email', $request->email)->get()->first();

            $user->api_token = $token; // update user token

            $user->save();

            $response = ['success' => true, 'data' => ['username' => $user->username, 'id' => $user->user_id, 'email' => $request->email, 'api_token' => $token]];
        } else {
            $response = ['success' => false, 'data' => 'Couldnt register user'];
        }

        return response()->json($response, 201);
    }

    public function show(Request $request)
    {

        $user = $request->user();

        $permissions = $user->permissions()->get();
        $response = ['success' => true, 'data' => ['id' => $user->user_id, 'api_token' => $user->api_token, 'username' => $user->username, 'email' => $user->email, 'permissions' => $permissions]];

        return response()->json($response, 200);

    }

    public function fetchSingle(Request $request, $user_id)
    {
        $user = User::find($user_id);

        return response()->json(compact("user"), 200);
    }

    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (isset($request->username)) {
            $check_user_result = User::where('username', $request->username)->first();
            if ($check_user_result !== null && $check_user_result->user_id != $user_id) {
                $errors = array("message" => "username duplicated");
                return response()->json(compact("errors"), 400);
            }
        }
        $user->username = isset($request->username) ? $request->username : $user->username;
        $user->email = isset($request->email) ? $request->email : $user->email;
        $user->phone = isset($request->phone) ? $request->phone : $user->phone;
        $user->status = isset($request->status) ? $request->status : $user->status;
        $user->save();

        $users = $this->helper->fetchUsers($request);
        return response()->json(compact("users"), 200);
    }
}
