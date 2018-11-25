<?php
namespace frenchpop\api\ajax;

use frenchpop\app\sru\Sru;
use frenchpop\webservice\Controler;
use frenchpop\app\datas\Datas;

class GET extends Controler {
   
    private $sru;
    private $datas;
    
    public function __construct($request, $response) {
        parent::__construct($request, $response);
        $this->response->setRawResponse();
        $this->setWebService();
        $this->sru=new Sru();
        $this->datas=new Datas();
    }
    
    public function searchPerson() {
        $person=$this->getQV("person");
        $results=$this->sru->searchPerson($person);
        return $results;
    }
    
    public function searchTags() {
        $tag=$this->getQV("tag");
        return $this->datas->getTags($tag);
    }
}
