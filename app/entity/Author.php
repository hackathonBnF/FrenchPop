<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\entity\Entity;

class Author extends Entity{
    protected $arkNumber;
    protected $name="";
    protected $familyName="";
    protected $givenName="";
    protected $gender="";
    protected $biographicalInformation="";
    protected $thumbnails = [];
    
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

    /**
     * @param mixed $arkNumber
     */
    public function setArkNumber($arkNumber)
    {
        $this->arkNumber = $arkNumber;
    }

    public function __construct($arkNumber){
        $this->arkNumber = $arkNumber;
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
                    $this->famiyName = $values[0];
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

}