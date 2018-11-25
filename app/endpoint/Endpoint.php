<?php
namespace frenchpop\app\endpoint;

use frenchpop\Frenchpop;

class Endpoint {
    protected $store;
    protected $prefixes = array();
    
    public function __construct(){
        $this->store = Frenchpop::getEndpoint($this->name,$this->url);
    }
    
    
    private function getPrefixes(){
       $prefixes = '';
       foreach($this->prefixes as $prefix => $uri){
           $prefixes.= "
           prefix ".$prefix.": <".$uri."> ";
       }
       return $prefixes;
    }
    
    public function query($query){
        $result = $this->store->query($this->getPrefixes().$query);
        if(count($this->store->getErrors())){
            throw new \Exception('SPARQL Error : '.implode('',$this->store->getErrors()));   
        }
        return $result['result']['rows'];
    
    
    }
    
    public function getInfos($uri){
        $result = $this->query("select * where { <".$uri."> ?property ?object }");
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
