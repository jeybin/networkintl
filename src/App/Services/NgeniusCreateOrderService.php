<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

final class NgeniusCreateOrderService extends NgeniusClient{

    /**
     * To accept a payment from a customer, an order is always required so 
     * that we have something to interact with in all our API interactions with 
     * the gateway, and on the Portal user interface. 
     *
     */
    public $client;


    /**
     * ordertype : "AUTH", "SALE", "PURCHASE"
     * Orders created with the ‘PURCHASE’ action will, if successfully authorized, 
     * automatically and immediately attempt to capture/settle the full order amount, 
     * whereas orders created with the ‘AUTH’ action will await some further action from 
     * instructing N-Genius Online to capture/settle the funds. Unless you are ready to 
     * ship your goods/services immediately, or you are selling digital content, we 
     * recommend you use the ‘AUTH’ action and capture your customers’ successful 
     * authorizations when you are ready to ship the goods to your customer.
     */
    private $order_type = 'PURCHASE';



    /**
     * Order currency
     * the order currency must the same as configured
     * in the dashboard of network international
     */
    private $order_currency;



    public function __construct(){
        $this->client         = new NgeniusClient();
        $this->order_currency = config('ngenius-config.merchant-currency');
    }

    public function create(array $request){
        try {
            /**
             * Generating the order request
             */
            $request  = $this->createOrderRequest($request);
            /**
             * Sending request to the ngenius client
             */
            $response = $this->client
                            ->setApi('transactions/outlets/{outlet-reference}/orders')
                            ->execute('post',$request);

            return $response;
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    public function createOrderRequest(array $request){
        try {

            /**
             * REF : https://docs.ngenius-payments.com/reference/list-of-order-input-attributes
             */

            /**
             * The amount must be convert into minor units
             * Hence multiplying the amount with 100;
             */
            $amount         = $request['amount']*100;

            /**
             * Action type is set as purchase 
             */
            $data['action'] = $this->order_type;

            /**
             * Amount value as minor value
             * multiplied by 100
             */
            $data['amount'] = ['currencyCode' => $this->order_currency,
                               'value'        => $amount];
            
            /**
             * Payer's email id
             */
            $data['emailAddress'] = $request['payer_email'];


            /**
             * Language switch (English, Arabic and French available)
             */
            $data['language']   = $request['language'];

            /**
             * Your own, optional reference. Accepted format allows alphanumeric 
             * characters (Aa-Zz and 0-9), and hyphens (-) only
             */
            $data['merchantOrderReference'] = $request['order_reference'];

            
            /**
             * merchantAttributes block
             */
            $data['merchantAttributes'] = $this->generateMerchantAttributesBlock($request);


            /**
             * Merchants may specify up to 100 custom data fields in a dedicated JSON block called merchantDefinedData. 
             * In all cases, both the key and value of these key/value pairs is entirely controlled by you, and will be 
             * reflected on the N-Genius Online portal (in the Order Details) page, and in any order reports you download.
             * This block will also be returned in any query (GET) against the status of the order, in any web-hooks 
             * that you define, and in any synchronous responses provided by the N-Genius Online APIs. 
             */
            if(!empty($request['merchant_defined'])){
                // $data['merchantDefinedData'] =  (object)json_decode(json_encode($request['merchant_defined']));
                $data['merchantDefinedData'] =  $request['merchant_defined'];
            }
            
            /**
             * billingAddress block
             */
            $data['billingAddress'] = $this->generateBillingAddressBlock($request['billing']);
            

            return (array)$data;
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }

    private function generateBillingAddressBlock($billing){
        
        /**
         * Billing address first name
         */
        $billing['firstName']   = $billing['first_name'];

        /**
         * Billing address last name
         */
        $billing['lastName']    = $billing['last_name'];

        /**
         * Billing address
         */
        $billing['address1']    = $billing['address'];

        /**
         * Billing address city : Dubai
         */
        $billing['city']        = $billing['city'];

        /**
         * Billing address country : UAE
         */
        $billing['countryCode'] = $billing['country'];

        return $billing;
    }


    private function generateMerchantAttributesBlock($request){
        /**
         * URL to redirect card-holder to after payment
         */
        $merchantAttr['redirectUrl']          = $request['redirect_url'];

        /**
         * URL to redirect card-holder to, in the event 
         * they want to return to shop before completing the payment
         */
        $merchantAttr['cancelUrl']            = $request['cancel_url'];

        /**
         * The text you want to display on the pay-page to 
         * return a card-holder to your website.
         * Default 'Continue Shopping' Example 'Return to Basket'
         */
        $merchantAttr['cancelText']            = $request['cancel_text'];

        /**
         * Indicates whether the customer will 
         * be presented with a payment confirmation page 
         * before being redirected back to your site.
         * true (to skip)
         */
        $merchantAttr['skipConfirmationPage'] = $request['skip_confirmation_page'];

        /**
         * Indicates whether the customer will be taken to 
         * the relevant 3D-Secure program for authentication.
         * true (to skip)
         */
        $merchantAttr['skip3DS']              = $request['skip3DS'];

        return $merchantAttr;
    }




}
