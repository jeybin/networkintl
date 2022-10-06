<?php
namespace Jeybin\Networkintl\App\Models;

use Illuminate\Database\Eloquent\Model;

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
    

}
