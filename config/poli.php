<?php
return [
    'reference' => env('POLI_REFERENCE', 'AUREUS CORP PTY LTD'),
    'auth' => env('POLI_AUTH', 'S6104198:Ek8$!6QiX@s3^9'),
    'createUrl' => env('POLI_CREATE_Url', 'https: //poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate'),
    'queryUrl' => env('POLI_QUERY_Url', 'https: //poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction?token='),

];
