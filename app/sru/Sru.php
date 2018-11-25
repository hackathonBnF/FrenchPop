<?php
namespace frenchpop\app\sru;

class Sru {
    const personURL="http://catalogue.bnf.fr/api/SRU?version=1.2&operation=searchRetrieve&query=aut.accesspoint%20all%20%22{{query}}%22%20and%20aut.type%20all%20%22pep%22&recordSchema=unimarcxchange&maximumRecords=20&startRecord=1";
    
    private $curl;
    
    public function __construct() {
        $this->curl= curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    private function prepareURL($url,$query) {
        return str_replace("{{query}}", $query, $url);
    }
    
    private function parseResult($xml,$fields=false) {
        $doc=new \DOMDocument();
        $doc->loadXML($xml);
        $xpath = new \DOMXpath($doc);
        
        $xpath->registerNamespace("srw", "http://www.loc.gov/zing/srw/");
        $xpath->registerNamespace("mxc", "info:lc/xmlns/marcxchange-v2");

        $recordnodes=$xpath->query("//srw:record");
        $results=[];
        foreach ($recordnodes as $record) {
            $res=[];
            $ark=$xpath->query("srw:recordIdentifier",$record);
            $res['ark']=$ark->item(0)->nodeValue;
            if ($fields) {
                $res["accesPoint"]="";
                $start=true;
                foreach($fields as $field) {
                    $field=explode("/",$field);
                    $f=$field[0];
                    $s=$field[1];
                    $val=$xpath->query("srw:recordData/mxc:record/mxc:datafield[@tag='$f']/mxc:subfield[@code='$s']",$record);
                    if ($val->length) {
                        $res["accesPoint"].=($start?"":" ").$val->item(0)->nodeValue;
                        $start=false;
                    }
                }
            }
            $results[]=$res;
        }
        return $results;
    }
    
    public function searchPerson($query) {
        curl_setopt($this->curl, CURLOPT_URL, $this->prepareURL(self::personURL,$query));
        $result=curl_exec($this->curl);
        return $this->parseResult($result,["200/b","200/a"]);
    }
}

