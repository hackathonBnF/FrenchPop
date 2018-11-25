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
}