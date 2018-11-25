<?php
namespace frenchpop\api\frenchpop;

use frenchpop\webservice\Controler;
use frenchpop\FrenchPop;

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
	
      $map = $this->mapPath(['id_thematique']);
      $r = FrenchPop::query("select label from thematiques where id_thematique=".$map->id_thematique);
      $label = $r->fetch_object()->label;
      
      $r = FrenchPop::query("select id_type, label from types order by label");
      $types = $r->fetch_all(MYSQLI_ASSOC);
   
      $data = [
	  'id'=>$map->id_thematique, 
	  'label'=>$label,
	  'types'=>$types,
      ];
      return FrenchPop::render(__DIR__."/resources/thematiques.html", $data);

    }
    
}