<?php
namespace {
    spl_autoload_register(function($className) {
        $className=explode('\\',$className);
        if ($className[0]!='frenchpop') return false;
        unset($className[0]);
        $className=implode('/',$className);
        if (!is_file($className.".php")) throw new Exception("Unknown class ".$className);
        require_once($className.".php");
    });

        require_once 'lib/arc2/ARC2.php';
}

namespace frenchpop {    
    class FrenchPop {
        static public $dt;
        
        static private $arc2Config=[];
        static private $store=null;     
        
        static private $eventsCall;
        
        const prefixes = [
            'pmblex'=>'http://www.pmbservices.fr/pmblex/'
        ];

        private function __construct() {

        }

        public static function subscribe($event,$subtype,$callback) {
            if (!self::$eventsCall[$event][$subtype]) 
                self::$eventsCall[$event][$subtype]=[];
            self::$eventsCall[$event][$subtype][]=$callback;
        }
        
        public static function publish($event,$subtype,...$params) {
            if (isset(self::$eventsCall[$event][$subtype])) {
                foreach (self::$eventsCall[$event][$subtype] as $callback) {
                    call_user_func($callback,$event,$subtype,$params);
                }
            }
            if (isset(self::$eventsCall[$event]["*"])) {
                foreach (self::$eventsCall[$event]["*"] as $callback) {
                    call_user_func($callback,$event,$subtype,$params);
                }
            }
        }
        
        public static function connectDb() {
            self::$dt=new \mysqli(app\config\Config::host, app\config\Config::user, app\config\Config::pass, app\config\Config::name, app\config\Config::port);
        }
          
        public static function query($query) {
            $result=self::$dt->query($query);
            return $result;
        }  
        
        public static function prepareQuery($query) {
            foreach (self::prefixes as $ns=>$uri) {
                $query="PREFIX $ns: <$uri> .\n".$query;
            }
            return $query;
        } 
        
        public static function getStore() {
            return self::$store;
        }
        
        public static function storeQuery($query) {
            $q=self::prepareQuery($query);
            $result=self::$store->query($q); if (!$result) throw new \Exception('Erreur dans la requête '.print_r(self::$store->getErrors(),true));
            return $result['result'];
        }
        
        public static function initStore($clearStore=false,$clearFiles=false) {
            self::connectDb();
             //Création des tables
            $q= file_get_contents("app/config/createTables.sql");
            self::$dt->query($q);
            
            self::$arc2Config=[
                'db_name'=> app\config\Config::name,
                'db_user'=> app\config\Config::user,
                'db_pwd'=> app\config\Config::pass,
                'db_host'=> app\config\Config::host,
                'store_name'=>'pmblex',
                'keep_time_limit'=>true
            ];

            self::$store=\ARC2::getStore(self::$arc2Config);
            
            if (!self::$store->isSetUp()) {
                self::$store->setUp();
            } else {
                if ($clearStore) {
                     self::$store->reset();
                }
            }
        }
        
        public static function initApi() {
            $router=new webservice\Router();
        }
    }
}