<?php
namespace frenchpop\app\endpoint;

use frenchpop\Frenchpop;

class Endpoint {
    protected $store;
    
    public function __construct(){
        $this->store = Frenchpop::getEndpoint($this->name,$this->url);
    }
    
    
    public function query($query){
        $result = $this->store->query($query);
        if(count($this->store->getErrors())){
            throw new \Exception('SPARQL Error : '.implode('',$this->store->getErrors()));   
        }
        return $result['result']['rows'];
    }
}
