<?php
namespace Jeybin\Networkintl\App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Base
 * @package App\Models
 */
class NgeniusGateway extends Model{

    protected $table    = 'ngenius_gateway';

    /**
     * Disable mass insert/update 
     * from the project
     */
    protected $fillable = [];
    

}
