<?php

namespace Jeybin\Networkintl\Services\Client;


use Illuminate\Support\Facades\Http;
use Jeybin\Networkintl\Models\NgeniusGateway;
use Illuminate\Http\Client\ConnectionException;

final class NgeniusClient {

    /**
     * API Key
     *
     * @var [string]
     */
    private $API_KEY;

    /**
     * Base api url of ngenius
     *
     * @var [string]
     */
    private $BASE_URL;
    
    /**
     * Refernce Id of ngenius
     *
     * @var [string]
     */
    private $REFERENCE_ID;


    public function __construct(){
        $this->initalize();
    }

    /**
     * Initalizing the Ngenius Client 
     * Variables
     * 
     */
    private function initalize(){
        $gatewayConfig = NgeniusGateway::where('active',true)->first();
        if(empty($gatewayConfig)){
            throwNgeniusPackageResponse('Please configure ngenius_gateway table to continue',null,422);
        }
        $this->BASE_URL     = $gatewayConfig->base_url;
        $this->API_KEY      = $gatewayConfig->api_key;
        $this->REFERENCE_ID = $gatewayConfig->reference_id;
    }

    private function setApi($api=''){
        return $this->BASE_URL.'/'.$api;
    }

    /**
     * Generating Access token
     *
     */
    protected function ACCESS_TOKEN(){
        $headers['accept']        = 'application/vnd.ni-identity.v1+json';
        $headers['content-type']  = 'application/vnd.ni-identity.v1+json';
        $headers['authorization'] = 'Basic '.$this->REFERENCE_ID;
        $API = self::setApi('identity/auth/access-token');
        return self::POST_REQUEST($API,[],$headers);
    }
    


    private static function POST_REQUEST($API,$BODY=[],$HEADERS=[]){
        $response = (!empty($HEADERS)) ? Http::withHeaders($HEADERS)->post($API,$BODY) 
                                       : Http::post($API,$BODY);
        $response = $response->json();
        if(!$response->successful()){
            throwNgeniusPackageResponse('Failed authorize ngenius, please check your credentials',null,406);
        }
        return $response;
    }


    
}
