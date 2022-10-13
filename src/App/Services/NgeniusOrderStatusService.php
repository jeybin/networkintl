<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

final class NgeniusOrderStatusService extends NgeniusClient{

    public function __construct(){
        $this->client         = new NgeniusClient();
    }

    public function check(string $orderReference){
        try {
            /**
             * Sending request to the ngenius client
             */
            return $this->client
                        ->setApi('transactions/outlets/{outlet-reference}/orders/'.$orderReference)
                        ->execute('get');

        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }




}
