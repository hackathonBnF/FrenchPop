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
    
    
    }public function getArkInfos($arkNumber){
        if(strpos($arkNumber,'#about') === false){
            $arkNumber.= "#about";
        }
        $result = $this->query("select * where { <http://data.bnf.fr/".$arkNumber."> ?property ?object }");
        $response = [];
        if(count($result)){
            for($i=0 ; $i<count($result) ; $i++){
                if(isset($result[$i]['property'])){
                    $response[$result[$i]['property']][] =  $result[$i]['object'];
                }
            }
        }
        return $response;
    }
}
