<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\entity\Entity;
use frenchpop\app\entity\Expression;

class Expression extends Entity{
    protected $uri="";
    protected $sameAs = [];
    protected $title = '';
    protected $abstract = '';
    
    /**
     * @return Ambigous <string, unknown>
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return Ambigous <string, unknown>
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    public function __construct($uri){
        $this->uri = $uri;
        $this->fetchDatas();
    }    
    
    protected function fetchDatas(){
        $infos = $this->getDataBnf()->getExpressionInfos($this->uri);
        foreach($infos as $property => $values){
            switch($property){
                case 'title' :
                    $this->title = $values[0];
                    break; 
                case 'abstract' :
                    $this->abstract = $values[0];
                    break;
            }
        }
    }
}