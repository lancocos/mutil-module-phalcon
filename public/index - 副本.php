<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Loader;
define('DS',DIRECTORY_SEPARATOR);
define('BASE_PATH',dirname(__DIR__));
define('APP_PATH',BASE_PATH.DS.'app'.DS);
define('APP_NS','App');
define('MODULES',['home','admin','user','worker']);
define('DEFAILT_MODULE','home');
$di = new FactoryDefault();
$loader = new Loader();
$loader->registerDirs([
	APP_PATH.'controllers',
	APP_PATH.'models',
]);
$loader->registerNamespaces([
	'App\Controllers'=>APP_PATH.'controllers',
	'App\Models'=>APP_PATH.'models',

]);
$loader->register();

$di->setShared('view',function(){
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir(APP_PATH.DS.'views');
	$view->registerEngines([
		'.volt'=>function($view) {
			$volt = new VoltEngine($view, $this);
			$volt->setOptions(
				[
					 'compiledPath' => BASE_PATH.'/cacheDir/',
                	 'compiledSeparator' => '_'
				]);
			return $volt;

		}
	]
	);
	/*
	return new class{
		public function start(){

		}
		public function render(){
		
		}
		public function finish(){

		}
		public function getContent(){
			return "<h1>:)</h1>";
		}
	};
	*/
	return $view;
});
$di->set('dispatcher',function(){
	$dispatcher = new Dispatcher();
	$dispatcher->setDefaultNamespace('App\Controllers');
	return $dispatcher;
});
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
