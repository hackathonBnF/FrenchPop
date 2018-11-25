<?php 

namespace frenchpop\app\entity;

use frenchpop\app\endpoint\DataBnf;
use frenchpop\app\endpoint\Dbpedia;

class Entity {
    protected static $dataBnf;
    protected static $dbpedia;
    
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
        }
    }
}