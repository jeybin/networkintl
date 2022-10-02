<?php

if(!class_exists('throwNgeniusPackageResponse')){

    function throwNgeniusPackageResponse($message,$data=[],$code){
        $response['code']  = $code;
        $response['error'] = (strpos($code, '2') === 0) ? false : true;
        $response['message'] = $message;
        if(!empty($data)){
            $response['data']  = $data;
        }
        $response =  response()->json($response,$code)->header('Content-Type', 'application/json');
        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }

}