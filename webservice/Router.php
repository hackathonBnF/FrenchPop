<?php
namespace frenchpop\webservice;

class Router {
    protected $request=null;
    protected $response=null;
    protected $controller=null;
    
    private function classExists($className) {
        $className=explode('\\',$className);
        if ($className[0]!='frenchpop') return false;
        unset($className[0]);
        $className=implode('/',$className);
        if (!is_file($className.".php")) return false;
        return true;
    }
    
    public function __construct() {
        $this->request=new Request();
        $this->response=new Response();

        $className="\\frenchpop\api\\".$this->request->getPath()[0]."\\".$this->request->getMethod();
        
        if ($this->classExists(substr($className,1))) {
            $this->controller=new $className($this->request,$this->response);
            $this->controller->callMethod();
        } else {
            $this->response->setError("Domain not found");
        }
        if (!$this->response->isFlushed()) {
            $this->response->flush();
        }
    }
}
