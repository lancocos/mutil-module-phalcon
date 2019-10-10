<?php
namespace App\Home;

use Phalcon\Mvc\ModuleDefinitionInterface,
	Phalcon\Loader,
	Phalcon\Mvc\Dispatcher,
	 Phalcon\DiInterface;


class Module implements ModuleDefinitionInterface{
		protected  $curr_ns='Home';
		public function registerAutoloaders(DiInterface $di=NULL){
			$loader = new Loader();
			$loader->registerNamespaces([
				APP_NS.'\\'.$this->curr_ns.'\\Controllers'=>__DIR__.DS.'controllers',
				APP_NS.'\\'.$this->curr_ns.'\\Models'=>__DIR__.DS.'models',
			]);
			$loader->registerDirs();
			$loader->register();
		}

		public function registerServices(DiInterface $di){
			$di->setShared('view',function(){
				$view = new \Phalcon\Mvc\View();
				$view->setViewsDir(__DIR__.'views');
				$view->registerEngines([
					'.volt'=>function($view) {
						$volt = new VoltEngine($view, $this);
						$volt->setOptions(
							[
						 		'compiledPath' => __DIR__.DS.'cacheDir'.DS,
	                	 		'compiledSeparator' => '_'
							]);
						return $volt;

					}]
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
 				$dispatcher->setDefaultNamespace(APP_NS.'\\'.$this->curr_ns.'\\Controllers');
 				return $dispatcher;
 			});
		}
}