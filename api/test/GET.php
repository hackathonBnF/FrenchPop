<?php
namespace frenchpop\api\test;

use frenchpop\webservice\Controler;
use frenchpop\app\sru\Sru;

class GET extends Controler {
    
    private $sru;
    
    public function __construct($request,$response) {
        parent::__construct($request, $response);
        $this->sru=new Sru();
    }
    
    public function test() {
        $map=$this->mapPath(['un','deux']);
        $v=$this->getPV('v');
        $v=$this->getQV('v');
        return $map->un." ".$map->deux." Test ".$v;
    }
    
    public function person() {
        $this->response->setRawResponse();
        $this->setWebService();
        $map=$this->mapPath(['query']);
        return $this->sru->searchPerson($map->query);
    }
}

