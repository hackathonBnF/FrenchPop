<?php
namespace frenchpop\api\frenchpop;

use frenchpop\webservice\Controler;
use frenchpop\FrenchPop;
use frenchpop\app\entity\Author;
use frenchpop\app\entity\Entity;

class GET extends Controler {
    
  public function accueil() {
	$r = FrenchPop::query('select count(*) as n from thematiques');
	$n = $r->fetch_object()->n;
	$x = rand(0,$n-1);
	$r = FrenchPop::query("select id_thematique, label from thematiques limit $x,1");
	$thematique = $r->fetch_object();
	$data = [
	    'id'=>$thematique->id_thematique, 
	    'label'=>$thematique->label
	];
	return FrenchPop::render(__DIR__."/resources/accueil.html", $data);

    }
    
    
    public function thematiques() {
	
      $id_thematique = $this->getQV('id_thematique');
      
      $r = FrenchPop::query("select label from thematiques where id_thematique=".$id_thematique);
      $label = $r->fetch_object()->label;
      
      $r = FrenchPop::query("select id_type, label from types order by label");
      $types = $r->fetch_all(MYSQLI_ASSOC);
   
      $data = [
	  'id'=>$id_thematique, 
	  'label'=>$label,
	  'types'=>$types,
      ];
      return FrenchPop::render(__DIR__."/resources/thematiques.html", $data);

    }
    
    
    public function thematiques_suite()
    {
	$id_thematique = $this->getQV('id_thematique');
	$id_type = $this->getQV('id_type');
	
	$r = FrenchPop::query("select label from thematiques where id_thematique=" . $id_thematique);
	$label_thematique = $r->fetch_object()->label;
	
	$r = FrenchPop::query("select label from types where id_type=" . $id_type);
	$label_type = $r->fetch_object()->label;
	
	$data = [
	    'id_thematique' => $id_thematique,
	    'label_thematique' => $label_thematique,
	    'id_type' => $id_type,
	    'label_type' => $label_type,
	];
	return FrenchPop::render(__DIR__ . "/resources/thematiques_suite.html", $data);
    } 
    
    public function entity(){
        $thematics = $tags = [];
        $id_resource = $this->getQV('id_resource');
        $r = FrenchPop::query("select * from ressources where id_ressource = ".$id_resource);
        $row =  $r->fetch_object();
        $resource = Entity::getInstance($row->num_type,$row->identifiant);
        $r = FrenchPop::query("select * from thematique_ressource join thematiques on num_thematique = id_thematique where num_ressource = ".$id_resource);
        while($row = $r->fetch_object()){
            $thematics[] = $row;
        }
        $r = FrenchPop::query("select tag.* from thematique_ressource join thematiques on num_thematique = id_thematique join thematique_ressource_tag on num_thematique_ressource = id_thematique_ressource join tag on id_tag = num_tag where num_ressource = ".$id_resource);
        while($row = $r->fetch_object()){
            $tags[] = $row;
        }
        try{
            $html = FrenchPop::render(__DIR__."/resources/author.html", [
                'resource' => $resource,
                'thematics' => $thematics,
                'tags' => $tags
                
            ]);
        }catch(Exception $e){
            var_dump($e);
        }
        return $html;
    }
}