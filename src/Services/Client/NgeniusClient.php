<?php

namespace Jeybin\Networkintl\Services\Client;


use Exception;
use Illuminate\Support\Facades\Http;
use Jeybin\Networkintl\Models\NgeniusGateway;
use Illuminate\Http\Client\ConnectionException;

class NgeniusClient {

    /**
     * Base api url of ngenius
     *
     * @var [string]
     */
    private $BASE_URL;

    
        /**
     * Base api url of ngenius
     *
     * @var [string]
     */
    private $API_URL;

    /**
     * Refernce Id of ngenius
     *
     * @var [string]
     */
    private $REFERENCE_ID;


    /**
     * Generated access token
     *
     * @var [string]
     */
    private $BEARER_TOKEN;


    public function __construct(){
        $this->initalize();
    }

    /**
     * Initalizing the Ngenius Client 
     * Variables
     * 
     */
    private function initalize(){

        /**
         * Fetching the gateway configurations from the
         * table ngenius_gateway and setting some of them
         * as private variables
         */
        $gatewayConfig = NgeniusGateway::where('active',true)->first();
        if(empty($gatewayConfig)){
            throwNgeniusPackageResponse('Please configure ngenius_gateway table to continue',null,422);
        }

        /**
         * Base url from the table
         */
        $this->BASE_URL     = $gatewayConfig->base_url;

        /**
         * Reference id got from the dashboard of ngenius
         */
        $this->REFERENCE_ID = $gatewayConfig->reference_id;

        /**
         * Generating the bearer token since the bearer token is
         * required for every request it is generating from 
         * the constructor method itself and keeping it in 
         * the private variable. The bearer token will expire in 5 minutes
         */
        $this->BEARER_TOKEN = self::ACCESS_TOKEN($gatewayConfig->api_key);

    }

    protected function setApi($api='/'){
        /**
         * If with reference api string contains
         * {outlet-reference} string it will be
         * replaced with the reference id from 
         * the settings table 
         */
        $api = (strpos($api,'{outlet-reference}')) ? str_replace('{outlet-reference}',$this->REFERENCE_ID,$api) : $api;
        $this->API_URL = $this->BASE_URL.'/'.$api;
        return $this;
    }

    /**
     * Generating Access token
     * Using curl because the guzzle http client 
     * always giving Bad request error
     */
    private function ACCESS_TOKEN($API_KEY){
        try {
            /**
             * Access token generation api
             */
            self::setApi('identity/auth/access-token');
            /**
             * Api headers for the curl request
             */
            $headers = array("authorization: Basic ".$API_KEY,
                             "accept: application/vnd.ni-identity.v1+json",
                             "content-type: application/vnd.ni-identity.v1+json");
    
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->API_URL); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_POST, 1); 
            $output = json_decode(curl_exec($ch)); 
            if(!empty($output) && !empty($output->access_token)){
                return $output->access_token;
            }else{
                throwNgeniusPackageResponse($output);
            }
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    protected function execute($type,$request=[],$headers=[]){
        $type = strtolower($type);
        if(!in_array($type,['post','get'])){
            throwNgeniusPackageResponse('Invalid execute type please check!',null,500);
        }

        if($type == 'post'){
            return $this->POST_REQUEST($request,$headers);
        }

    }

    private function POST_REQUEST($body=[],$headers=[]){
        try {
            $allheaders = ['Content-Type'=>'application/vnd.ni-payment.v2+json',
                          'Accept'       =>'application/vnd.ni-payment.v2+json'];
                
            if(!empty($headers)){
                $allheaders = array_merge($allheaders,$headers);
            }

            if(empty($this->BEARER_TOKEN)){
                throwNgeniusPackageResponse('Access token not found, please generate access token to continue',null,422);
            }

            $response = Http::withHeaders($allheaders)->withToken($this->BEARER_TOKEN)->post($this->API_URL,$body);

            if(empty($response->json())){
                throwNgeniusPackageResponse('Failed authorize ngenius, please check your credentials',null,406);
            }

            $response = $response->json();
            if($response['code'] !== 200){
                $errors = (!empty($response['errors'])) ? $response['errors'] : null;
                throwNgeniusPackageResponse($response['message'],$errors,$response['code']);
            }

            return $response;

        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }catch(ConnectionException $connException){
            throwNgeniusPackageResponse($connException);
        }

    }


    
}
