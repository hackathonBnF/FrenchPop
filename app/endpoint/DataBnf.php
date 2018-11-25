<?php
namespace frenchpop\app\endpoint;

use frenchpop\Frenchpop;

class DataBnf extends Endpoint {
    protected $name = 'dataBnf';
    protected $url = 'https://data.bnf.fr/sparql';
    protected $prefixes = array(
        'bnfroles' => 'http://data.bnf.fr/vocabulary/roles/',
        'dc' => 'http://purl.org/dc/elements/1.1/',
        'dcterms' => 'http://purl.org/dc/terms/',
        'foaf' => 'http://xmlns.com/foaf/0.1/',
        'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
        'skos' => 'http://www.w3.org/2004/02/skos/core#',
        'frbr-rda' => 'http://rdvocab.info/uri/schema/FRBRentitiesRDA/',
        'rdarelationships' => 'http://rdvocab.info/RDARelationshipsWEMI/'
    );

    
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
    
    public function getExpressionsFromAuthor($arkNumber){
        if(strpos($arkNumber,'#about') === false){
            $arkNumber.= "#about";
        }
        return $this->query("select * where {   
            ?expr rdf:type frbr-rda:Expression .
            ?expr dcterms:contributor <http://data.bnf.fr/".$arkNumber.">
        } limit 10");
    }
    
    public function getExpressionInfos($uri){
        $result = $this->query("select * where {
            <".$uri."> ?property ?value .
            ?manif rdarelationships:expressionManifested <".$uri."> .
            ?manif dcterms:title ?title .
            optional {
                ?manif dcterms:abstract ?abstract
            }
        }");
        $response = [];
        if(count($result)){
            for($i=0 ; $i<count($result) ; $i++){
                if(isset($result[$i]['title']) && !isset($response['title'])){
                    $response['title'][] =  $result[$i]['title'];
                } 
                if(isset($result[$i]['abstract']) && !isset($response['abstract'])){
                    $response['abstract'][] =  $result[$i]['abstract'];
                }
            }
        }
        return $response;
    }
    
    public function getArkInfos($arkNumber){
        if(strpos($arkNumber,'#about') === false){
            $arkNumber.= "#about";
        }
        return $this->getInfos('http://data.bnf.fr/'.$arkNumber);
    }
}
