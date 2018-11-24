<?php
namespace frenchpop\api\doc;

use frenchpop\webservice\Controler;

class GET extends Controler {
    
    public function __construct($request,$response) {
        parent::__construct($request, $response);
        $this->response->setRawResponse();
        $this->setWebService();
    }
    
     private function getParam($value) {
        $ret=[];
        if (preg_match("/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+(.*)/",$value,$m)) {
            $ret['type']=$m[1];
            $ret['datatype']=$m[2];
            $ret['name']=$m[3];
            $ret['desc']=$m[4];
        }
        return $ret;
    }
    
    private function getReturn($value) {
        $ret=[];
        if (preg_match("/([^\s]+)\s+(.*)/",$value,$m)) {
            $ret['type']=$m[1];
            $ret['desc']=$m[2];
        }
        return $ret;
    }
    
    private function analyzeComment($comment) {
        $ret=[];
        if (preg_match("/\/\*\*\s+\*\s+(.*)\s*\*\s@/",$comment,$m)) {
            $ret['desc']=$m[1];
        }
        if (preg_match_all("/@([^\s]+)\s+(.*)\s+\*(\s|\/)/",$comment,$m)) {
            for ($i=0; $i<count($m[1]); $i++) {
                $prefix=$m[1][$i];
                $value=trim($m[2][$i]);
                switch ($prefix) {
                    case "param":
                        $p=$this->getParam($value);
                        $ret['param'][]=$p;
                        break;
                    case "return":
                        $ret['return']=$this->getReturn($value);
                        break;
                    case "abstract":
                        $ret['abstract']=$value;
                        break;
                    case "example":
                        if (substr($value,0,5)=='call ') {
                            $ret['example']['call']=substr($value,5);
                        }
                        if (substr($value,0,7)=='return ') {
                            $ret['example']['return']=substr($value,7);
                        }
                        break;
                    case "access":
                        $ret['access']=$value;
                        break;
                }
            }
        }
        return $ret;
    }
    
    private function getPackage($domain) {
        $ret=false;
        if (is_file("api/".$domain."/package.json")) {
            $packageDesc=json_decode(file_get_contents("api/".$domain."/package.json"),true);
            $packageDesc['dir']=$domain;
            $ret=$packageDesc;
        }
        return $ret;
    }
    
    public function domains($default=false) {
        //Parse des fichiers package.json
        $api=dir("api");
        $ret=[];
        $ret['__stylesheet']=($default?"":"../")."resources/doc.xsl";
        $ret['__called']="domains";
        if ($default) $ret['__default']=true;
        while ($d=$api->read()) {
            if (($d[0]!='.')&&(is_dir("api/".$d))) {
                if ($package=$this->getPackage($d)) {
                    $ret[]=$package;
                }
            }
        }
        return $ret;
    }
    
    /**
     * 
     * @access hide
     */
    public function _default() {
        return $this->domains(true);
    }
    
    public function methods() {
        $map=$this->mapPath(['domain']);
        $ret=[];
        $ret['__stylesheet']="../resources/doc.xsl";
        $ret['__called']="methods";
        $ret['domain']=$this->getPackage($map->domain);
        $httpMethods=["GET","POST","PUT","DELETE"];
        for ($i=0; $i<count($httpMethods);$i++) {
            try {
                if (is_file("api/".$map->domain."/".$httpMethods[$i].".php")) {
                    $refl=new \ReflectionClass("frenchpop\\api\\".$map->domain."\\".$httpMethods[$i]);
                    $methods=$refl->getMethods(\ReflectionMethod::IS_PUBLIC);
                    foreach($methods as $method) {
                        $name=$method->getName();
                        if (($name!=='__construct')&&($name!='callMethod')) {
                            $m=[];
                            $m['name']=$name;
                            $doc=$method->getDocComment();
                            $m['doc']=$this->analyzeComment($doc);
                            if ((!isset($m['doc']["access"]))||($m['doc']["access"]!='hide')) $ret[$httpMethods[$i]][]=$m;
                        }
                    }
                } else $ret[$httpMethods[$i]]=[];
            } catch(\ReflectionException $ex) {
                $ret[$httpMethods[$i]]=[];
            }
        }
        return $ret;
    }
}

