<?php
namespace Jeybin\Networkintl\App\Models;

use Illuminate\Database\Eloquent\Model;
use Jeybin\Networkintl\App\Exceptions\NgeniusWebhookExceptions;

/**
 * Class Base
 * @package App\Models
 */
class NgeniusGatewayWehooks extends Model{

    protected $table    = 'ngenius_gateway_webhooks';

    protected $fillable = ['event',
                           'outlet',
                           'ref',
                           'email',
                           'currency',
                           'amount'];
    
    protected $casts = [
                        'payload' => 'array',
                        'exception' => 'array',
                    ];


    public function process(){

        $this->clearException();

        if ($this->type === '') {
            throw NgeniusWebhookExceptions::missingType($this);
        }
//        event("ngenius::{$this->type}", $this);

        $jobClass = $this->determineJobClass($this->type);

        if ($jobClass === '') {
            return;
        }

        if (! class_exists($jobClass)) {
            throw NgeniusWebhookExceptions::jobClassDoesNotExist($jobClass, $this);
        }

        dispatch(new $jobClass($this));
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
