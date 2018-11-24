<?php
namespace frenchpop\api\test;

use frenchpop\webservice\Controler;

class GET extends Controler {
    
    public function __construct($request,$response) {
        parent::__construct($request, $response);
    }
    
    public function test() {
        $map=$this->mapPath(['un','deux']);
        $v=$this->getPV('v');
        $v=$this->getQV('v');
        return $map->un." ".$map->deux." Test ".$v;
    }
}

