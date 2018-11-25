<?php
namespace frenchpop\app\datas;

use frenchpop\FrenchPop;

class Datas {
    public function getTags($tag) {
        $r=FrenchPop::query("select id_tag,label from tag where label like '".addslashes($tag)."%'");
        if ($r->num_rows) return $r->fetch_all(MYSQLI_ASSOC); else return [];
    }
}
