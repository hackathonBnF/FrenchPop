<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\entity\Entity;
use frenchpop\app\entity\Expression;
use frenchpop\Frenchpop;

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
    
    public function getId(){
        if($this->id!== 0){
            return $this->id;
        }
        $uri = $this->uri;
        if(strpos($uri,'#Expression') !== false){
            $uri= str_replace('#Expression','',$uri);
        }
        $r = Frenchpop::query("select id_ressource from ressources where identifiant='".$uri."'");
        if($r->num_rows > 0){
            $row =  $r->fetch_object();
            $this->id = $row->id_ressource;
        }
        return $this->id;
    }
}