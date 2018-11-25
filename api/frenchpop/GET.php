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
        $ark = $this->getQV('ark');
        if($id_resource > 0){
            $r = FrenchPop::query("select * from ressources where id_ressource = ".$id_resource);
            $row =  $r->fetch_object();
            $resource = Entity::getInstance($row->num_type,$row->identifiant);
            $r = FrenchPop::query("select thematiques.id_thematique,label, thematique_ressource.commentaire from thematique_ressource join thematiques on num_thematique = id_thematique where num_ressource = ".$id_resource);
            while($row = $r->fetch_object()){
                $thematics[] = $row;
            }
            $r = FrenchPop::query("select tag.* from thematique_ressource join thematiques on num_thematique = id_thematique join thematique_ressource_tag on num_thematique_ressource = id_thematique_ressource join tag on id_tag = num_tag where num_ressource = ".$id_resource);
            while($row = $r->fetch_object()){
                $tags[] = $row;
            }
        }else{
            $resource = Entity::getInstance(0,$ark);
        }
        $html = FrenchPop::render(__DIR__."/resources/author.html", [
            'resource' => $resource,
            'thematics' => $thematics,
            'tags' => $tags
        ]);
        return $html;
    }
    
    public function thematique() {
        
        $id_thematique = $this->getQV('id_thematique');
        
        $r = FrenchPop::query("select * from thematiques where id_thematique=".$id_thematique);
        $thematic = $r->fetch_object();
        
        $r = FrenchPop::query("select types.label as type, ressources.label as ressource, identifiant, id_ressource from thematique_ressource join ressources on num_ressource = id_ressource join types on num_type = id_type where num_thematique = ".$id_thematique);
        $ressources = [];
        if($r->num_rows > 0){
            while($row = $r->fetch_object()){
                $r2 = FrenchPop::query("select num_tag,label from thematique_ressource_tag join tag on num_tag = id_tag join  thematique_ressource on num_thematique_ressource = id_thematique_ressource where num_ressource =".$row->id_ressource);
                $row->tags = [];
                while($row2 = $r2->fetch_object()){
                    $row->tags[] = $row2;
                }
                $ressources[$row->type][] = $row;
            }
        }
        $data = [
            'thematic'=>$thematic,    
            'ressources'=> $ressources       
//  
        ];
        return FrenchPop::render(__DIR__."/resources/thematiques_aleatoire.html", $data);
        
    }
}