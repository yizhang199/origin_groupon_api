<?php

namespace App\Http\Controllers;

use App\Http\Controllers\helpers\OrderHelper;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $OrderHelper;

    public function __construct()
    {
        $this->OrderHelper = new OrderHelper();
    }

    public function store(Request $request)
    {

        //1. validation
        //2. create
        $dt = new \DateTime("now", new \DateTimeZone('Australia/Sydney'));
        $today = $dt->format('y-m-d');

        $token = $request->bearerToken();
        $user = User::where("api_token", $token)->first();

        $input = [
            'invoice_no' => $request->invoice_no,
            'store_id' => isset($request->store_id) ? $request->store_id : "",
            'customer_id' => $user->user_id,
            'fax' => isset($request->fax) ? $request->fax : "",
            'payment_method' => isset($request->payment_method) ? $request->payment_method : "",
            'total' => isset($request->total) ? $request->total : "",
            'date_added' => $today,
            'date_modified' => $today,
            'order_status_id' => 2,
        ];
        $order = Order::create($input);
        if (isset($request->customerComments)) {
            $order->comment = $request->customerComments;
            $order->save();
        }
        $order_products = $this->OrderHelper->createOrderProducts($request, $order->order_id);

// make reponse body

// response order with details container
        $responseOrders = array();
// Todo:: paginate
        $orders = Order::where('customer_id', $user->user_id)->get();
// add details to each order
        foreach ($orders as $order) {
            $detailedOrder = $this->OrderHelper->makeOrder($order);
            array_push($responseOrders, $detailedOrder);
        }

        return response()->json(compact("orders"), 200);
    }
}
