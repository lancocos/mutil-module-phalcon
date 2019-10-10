<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Router;
use Phalcon\Loader;
define('DS',DIRECTORY_SEPARATOR);
define('BASE_PATH',dirname(__DIR__));
define('APP_PATH',BASE_PATH.DS.'app'.DS);
define('APP_NS','App');
define('MODULES',['home','admin','user','worker']);
define('DEFAILT_MODULE','home');
$di = new FactoryDefault();
$di->set('route',function(){
	 $router = new Router();
	 $router->setDefaultModule("home");
	 return $router;
});

// $di->set('dispatcher',function(){
// 	$dispatcher = new Dispatcher();
// 	$dispatcher->setDefaultNamespace('App\Controllers');
// 	return $dispatcher;
// });
try{
	$app = new Application($di);
	
	$modules = [];
	foreach (MODULES as $value) {
		$_module = [
			
				'className'=>APP_NS.'\\'.ucfirst($value).'\\Module',
				'path'=>APP_PATH.$value.DS.'Module.php',
		];
		$modules[$value]=$_module;
	}
	
	$app->registerModules($modules);
	
	echo $app->handle()->getContent();
	//echo (memory_get_usage()/1024)."KB";
}catch(\Exception $e){
	echo $e->getMessage();
	echo "<br/>";
	echo $e->getTraceAsString();
}
