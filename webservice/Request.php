<?php
namespace frenchpop\webservice;

class Request {
    protected $method;
    protected $queryParameters;
    protected $datas;
    protected $path;
    
    public function __construct() {
        //Lecture des paramètres
        $this->method=$_SERVER['REQUEST_METHOD'];
        $this->queryParameters=$this->decodeQuery($_SERVER['QUERY_STRING']);
        $this->datas=$this->readInput();
        $this->path=explode('/',substr($_SERVER['PATH_INFO'],1));
    }
    
    protected function decodeQuery($query) {
        $vals=explode("&",$query);
        $datas=array();
        foreach ($vals as $val) {
            if ($val) {
                $couple=explode("=",$val);
                $datas[urldecode($couple[0])]= urldecode(isset($couple[1])?$couple[1]:false);
            }
        }
        return $datas;
    }
    
    protected function readInput() {
        return file_get_contents("php://input");
    }
    
    public function getQ($valName) {
        if (isset($this->queryParameters[$valName]))
            return $this->queryParameters[$valName];
        else return false;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function getMethod() {
        return $this->method;
    }
}

