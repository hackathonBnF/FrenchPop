<?php
namespace frenchpop\webservice;

class Controler {
    protected $request=null;
    protected $response=null;
    
    public function __construct($request,$response) {
        $this->request=$request;
        $this->response=$response;
        
        $rf=$this->request->getQ("rf");
        
        switch($rf) {
            case "xml":
                $this->response->setOutputFormat(Response::OUTPUT_XML);
                break;
            case "rawxml":
                $this->response->setOutputFormat(Response::OUTPUT_RAWXML);
                $this->setRawResponse();
                break;
            case "json":
                $this->response->setOutputFormat(Response::OUTPUT_JSON);
                break;
            default:
                $this->response->setOutputFormat(Response::OUTPUT_HTML);
                break;
        }
    }
    
    protected function getQV($valName) {
        return $this->request->getQ($valName);
    }
    
    protected function getPV($valName) {
        return $this->request->getP($valName);
    }
    
    protected function error($msg) {
        $this->response->setError($msg);
        return null;
    }
    
    protected function mapPath($properties,$implode=false) {
        $map=new \stdClass();
        if (count($properties)<(count($this->request->getPath())-2)) {
            $c=count($properties)-1;
            $lastPath=array_slice($this->request->getPath(),count($properties)+1);
            if (!$implode) {
                $map->{$properties[count($properties)-1]}=$lastPath;
            } else  {
                $map->{$properties[count($properties)-1]}=implode("/",$lastPath);
            }
        } else {
            $c=count($properties);
        }
        for ($i=0; $i<$c; $i++) {
            $map->{$properties[$i]}=$this->request->getPath()[$i+2];
        }
        return $map;
    }
    
    public function callMethod() {
        if (isset($this->request->getPath()[1])&&($this->request->getPath()[1]))
            $method=$this->request->getPath()[1];
        else
            $method="_default";
        $reflection=new \ReflectionClass($this);
        try {
            $methods=$reflection->getMethods();
            $found=false;
            foreach($methods as $meth) {
                if ($meth->name==$method) {
                    $found=true;
                    $this->response->setResponse($this->$method());
                    break;
                }
            }
            if (!$found) {
                $this->response->setError("Method not found");
            }
        } catch (Exception $ex) {
            $this->response->setError("Error in method");
        }
    }
    
    /**
     * @access hide
     */
    public function resources() {
        $map=$this->mapPath(["path"]);
        $file="api/".$this->request->getPath()[0]."/resources/".$map->path;
        if (is_file($file)) 
            $this->response->setDirectResource($file);
        else {
            $this->response->setResponseCode(404);
            $this->response->setDirectResource("webservice/resources/404.html");
        }
    }
    
    /**
     * @access hide
     */
    public function setRawResponse() {
        $this->response->setRawResponse();
    }
    
    /**
     * @access hide
     */
    public function setOutputFormat($format) {
        $this->response->setOutputFormat($format);
    }
    
    /**
     * @access hide
     */
    public function getOutputFormat() {
        return $this->response->getOutputFormat();
    }
    
    public function setWebService() {
        $this->response->setOutputFormat(Response::OUTPUT_JSON);
    }
}

