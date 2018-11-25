<?php

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\entity\Entity;
use frenchpop\app\entity\Expression;

class Author extends Entity{
    protected $arkNumber;
    protected $name="";
    protected $familyName="";
    protected $givenName="";
    protected $gender="";
    protected $biographicalInformation="";
    protected $thumbnails = [];
    protected $expressions = [];
    
    /**
     * @return mixed
     */
    public function getArkNumber()
    {
        return $this->arkNumber;
    }
    
    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }
    
    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }
    
    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    public function __construct($uri){
        $this->arkNumber = str_replace('http://data.bnf.fr/','',$uri);
        $this->uri = $uri;
        $this->fetchDatas();
    }
    
    protected function fetchDatas(){
        $infos = $this->getDataBnf()->getAuthorsInfos($this->arkNumber);
        foreach($infos as $property => $values){
            switch($property){
                case 'http://xmlns.com/foaf/0.1/name' :
                    $this->name = $values[0];
                    break;
                case 'http://xmlns.com/foaf/0.1/familyName' :
                    $this->familyName = $values[0];
                    break;
                case 'http://xmlns.com/foaf/0.1/givenName' :
                    $this->givenName = $values[0];
                    break;
                case 'http://xmlns.com/foaf/0.1/gender' :
                    $this->gender = $values[0];
                    break;
                case 'http://rdvocab.info/ElementsGr2/biographicalInformation' :
                    $this->biographicalInformation = $values[0];
                    break;
                case 'http://xmlns.com/foaf/0.1/depiction' :
                    $this->thumbnails = $values;
                    break;
            }
        }
        // $this->getExpressions();
    }
    /**
     * @return Ambigous <string, unknown>
     */
    public function getBiographicalInformation()
    {
        return $this->biographicalInformation;
    }
    
    /**
     * @return Ambigous <multitype:, unknown>
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function getExpressions(){
        $result = $this->getDataBnf()->getExpressionsFromAuthor($this->arkNumber);
        for($i=0 ; $i<count($result) ; $i++){
            $this->expressions[] = new Expression($result[$i]['expr']);
        }
    }
    
}