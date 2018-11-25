<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\entity\Entity;
use frenchpop\app\entity\Expression;

class Expression extends Entity{
    protected $uri="";
    protected $sameAs = [];
    
    public function __construct($uri){
        $this->uri = $uri;
        $this->fetchDatas();
    }    
    
    protected function fetchDatas(){
        $infos = $this->getDataBnf()->getExpressionInfos($this->uri);
        foreach($infos as $property => $values){
            switch($property){
//                 case 'http://www.w3.org/2002/07/owl#sameAs' :
//                     $this->sameAs = $values[0];
//                     break;
            }
        }
        var_dump($this->uri);
        var_dump($infos);
    }
}