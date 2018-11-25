<?php
namespace frenchpop\webservice;

class Response {
    const OUTPUT_JSON = 1;
    const OUTPUT_XML = 2;
    const OUTPUT_RAWXML = 3;
    const OUTPUT_HTML = 4;
    
    private $response=null;
    private $isRaw=true;
    private $isError=false;
    private $errorMsg="";
    private $outputFormat=self::OUTPUT_HTML;
    private $isFlushed=false;
    private $directResource=false;
    
    public function setRawResponse() {
        $this->isRaw=true;
    }
    
    public function setResponse($response) {
        $this->response=$response;
    }
    
    public function setError($message) {
        $this->isError=true;
        $this->errorMsg=$message;
    }
    
    public function setDirectResource($resource) {
        $this->directResource=$resource;
    }
    
    public function setResponseCode($code) {
        http_response_code($code);
    }
    
    public function empac() {
        if ($this->isError) {
            $this->response=['error'=>true,'message'=>$this->errorMsg];
        } else {
            $this->response=['error'=>false,'result'=>$this->response];
        }
    }
    
    private function array2xml($data, &$xmlData) {
        foreach( $data as $key => $value ) {
            $isNumeric=false;
            if( is_numeric($key) ){
                $i=$key;
                $key = 'item'; 
                $isNumeric=true;
            }
            if( is_array($value) ) {
                $subnode = $xmlData->addChild($key);
                $this->array2xml($value, $subnode);
            } else {
                $subnode=$xmlData->addChild("$key",htmlspecialchars("$value"));
            }
            if ($isNumeric) {
                $subnode->addAttribute('index',$i);
            }
         }
    }

    private function toXML($data) {
        $xml="<?xml version='1.0'?>";
        if (isset($data['__stylesheet'])) {
            $xml.="<?xml-stylesheet type='text/xsl' href='".$data['__stylesheet']."'?>";
        }
        $xml.="<response></response>";
        $xmlData=new \SimpleXMLElement($xml);
        $this->array2xml($data,$xmlData);
        return $xmlData->asXML();
    }

    public function formatResponse() {
        switch ($this->outputFormat) {
            case self::OUTPUT_JSON:
                header('Content-Type: application/json');
                return json_encode($this->response);
                break;
            case self::OUTPUT_XML:
                header('Content-Type: application/xml');
                return $this->toXML($this->response);
                break;
            case self::OUTPUT_RAWXML:
                header('Content-Type: application/xml');
                return $this->response;
                break;
            case self::OUTPUT_HTML:
                header('Content-Type: text/html');
                return $this->response;
                break;
        }

    }
    
    public function getResponse() {
        if (!$this->isRaw) {
            $this->empac();
        }
        return $this->formatResponse();
    }
    
    public function setOutputFormat($format) {
        if (($format!=self::OUTPUT_JSON)&&($format!=self::OUTPUT_XML)&&($format!=self::OUTPUT_RAWXML)&&($format!=self::OUTPUT_HTML)) return;
        $this->outputFormat=$format;
    }
    
    public function getOutputFormat() {
        return $this->outputFormat;
    }
    
    public function flush() {
        $this->isFlushed=true;
        if ($this->directResource) {
            readfile ($this->directResource);
            return;
        }
        print $this->getResponse();
    }
    
    public function isFlushed() {
        return $this->isFlushed;
    }
}
