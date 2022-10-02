<?php

if(!class_exists('throwNgeniusPackageResponse')){
    function throwNgeniusPackageResponse($message,$data=[],$errorCode=500){
        if ((gettype($message) !== 'string') && ($message instanceof \Exception)) {
            if($message->getMessage()){
                $data      = (!empty($message->getTrace()))   ? $message->getTrace()   : [];
                $message   = (!empty($message->getMessage())) ? $message->getMessage() : "Something went wrong";
                $data      = $data?:[$message];
                $errorCode = 500;
            }else{
                throw new \Illuminate\Http\Exceptions\HttpResponseException($message->getResponse());
            }
        }
        $errStatus = (in_array($errorCode,[200,201])) ? false : true;
        $response = ['code'=>(int)$errorCode,'error'=>$errStatus,"message"=>$message];
        if(!empty($data)){
            $response['data'] = $data;
        }
        if($errorCode == 200 && $data == "empty"){
            $response['data'] = [];
        }
        $response =  response()->json($response,$errorCode)->header('Content-Type', 'application/json');
        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }

}