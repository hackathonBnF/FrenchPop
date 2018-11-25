<?php
namespace frenchpop\api\ajax;

use frenchpop\app\sru\Sru;
use frenchpop\webservice\Controler;
use frenchpop\app\datas\Datas;
use frenchpop\FrenchPop;

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
    
    public function addPerson() {
        $content=$this->getQV('content');
        $content= json_decode($content);
        FrenchPop::query("insert into ressources (identifiant,num_type) values('".addslashes("http://data.bnf.fr/".$content->ark)."',$content->id_type)");
        $rid=FrenchPop::$dt->insert_id;
        FrenchPop::query("insert into thematique_ressource (num_thematique,num_ressource) values(".$content->id_thematique.",$rid)");
        $rtr=FrenchPop::$dt->insert_id;
        //Tags
        $tags=explode(" ",$content->tags);
        foreach ($tags as $tag) {
            if (trim($tag)) {
                $r=FrenchPop::query("select id_tag from tag where label='".addslashes($tag)."'");
                if ($r->num_rows) {
                    //On insère
                    $id_tag=$r->fetch_object()->id_tag;
                } else {
                    FrenchPop::query("insert into tag (label) values('".addslashes($tag)."')");
                    $id_tag=FrenchPop::$dt->insert_id;
                }
                FrenchPop::query("insert into thematique_ressource_tag (num_thematique_ressource,num_tag) values($rtr,$id_tag)");
            }
            return [];
        }
    }
}
