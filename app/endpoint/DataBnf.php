<?php
namespace frenchpop\app\endpoint;

use frenchpop\Frenchpop;

class DataBnf extends Endpoint {
    protected $name = 'dataBnf';
    protected $url = 'https://data.bnf.fr/sparql';

    
    /**
     * Retourne les informations d'une personne depuis son numéro ARK
     * @param string $arkNumber numéro ARK 
     */
    public function getAuthorsInfos($arkNumber){
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
        return $result;
    }
}
