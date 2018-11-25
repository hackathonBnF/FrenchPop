<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\endpoint\Dbpedia;
use frenchpop\Frenchpop;

class Entity {
    protected static $dataBnf;
    protected static $dbpedia;
    protected $id=0;
    protected $arkNumber = "";
    
    protected function getDataBnf(){
        if(self::$dataBnf){
            return self::$dataBnf;
        }
        self::$dataBnf = new DataBnf();
        return self::$dataBnf;
    }
    
    protected function getDbpedia(){
        if(self::$dbpedia){
            return self::$dbpedia;
        }
        self::$dbpedia = new Dbpedia();
        return  self::$dbpedia;
    } 
    
    public static function getInstance($type,$uri){
        switch($type){
            case '1' :
                return new Author($uri);
            case '2' :
                return new Expression($uri);
            case '0' :
                return new Expression($uri);
        }
    }
    
    public function getArkNumber(){
        if($this->arkNumber!= ''){
            return $this->arkNumber;
        }
        $this->arkNumber = str_replace('http://data.bnf.fr/','',$this->uri);
        return $this->arkNumber;
    }
    
    public function getId(){
        if($this->id!== 0){
            return $this->id;
        }
        $r = Frenchpop::query("select id_ressource from ressources where identifiant='".$this->uri."'");
        $row =  $r->fetch_object();
        $this->id = $row->id_ressource;
        return $this->id;
    }
}