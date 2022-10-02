<?php

namespace Jeybin\Networkintl;

use Illuminate\Http\Request;
use Jeybin\Networkintl\Middleware\NgeniusJsonHeader;

class Ngenius {

    /**
     * Type of request needed
     * eg: access-token,create-order etc. 
     */
    private $request_for;

    public function __construct()
    {
        $this->request_for = null;
    }

    public function execute(){
        return $this->request_for;
    }

    public function type(string $type){
        $this->request_for = $type;
        return $this;
    }


}