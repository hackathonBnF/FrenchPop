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
        return $this->getArkInfos($arkNumber);;
    }
}
