<?php
namespace Jeybin\Networkintl\App\Models;

use Illuminate\Database\Eloquent\Model;
use Jeybin\Networkintl\App\Exceptions\WebhookExceptions;

/**
 * Class Base
 * @package App\Models
 */
class NgeniusGatewayWehooks extends Model{

    protected $table    = 'ngenius_gateway_webhooks';

    protected $fillable = ['event_id',
                           'event_name',
                           'order_reference',
                           'merchant_order_reference',
                           'email',
                           'currency',
                           'amount',
                           'payload',
                           'exception'];
    
    public $guarded = [];
    
    protected $casts = [
                        'payload' => 'array',
                        'exception' => 'array',
                        'merchant_order_reference'=>'integer'
                    ];


    public function process(){

        $this->clearException();

        if ($this->event_name === '') {
            throw WebhookExceptions::missingType($this);
        }

        /**
         * Can use the event if needed with the name of the 
         * order response
         * eg : if PURCHASE_DECLINED -> event will be ngenius::PURCHASE_DECLINED
         */
        // event("ngenius::{$this->event_name}", $this);

        /**
         * Determining the job classes
         * ----------------------------------------
         * The function will be check the job class name 
         * which are mentioned in webhook-jobs array
         * inside the ngenius-config file and return the 
         * Job Name if the job doesn't exists it will
         * return null or if the class doesnt exists it will 
         * return exception or else dispatch the job 
         */
        $jobClass = $this->determineJobClass($this->event_name);

        if ($jobClass === '') {
            return;
        }

        if (! class_exists($jobClass)) {
            throw WebhookExceptions::jobClassDoesNotExist($jobClass, $this);
        }

        try{
            dispatch(new $jobClass($this));
        }catch(Exception $e){
            $this->saveException($e);
        }
    }

    public function saveException(Exception $exception)
    {
        $this->exception = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ];

        $this->save();


        return $this;
    }

    protected function determineJobClass(string $eventType): string
    {   

        $jobConfigKey = str_replace('.', '_', $eventType);
        
        return config("ngenius-config.webhook-jobs.{$jobConfigKey}", '');
        
    }

    protected function clearException()
    {
        $this->exception = null;

        $this->save();

        return $this;
    }

}
