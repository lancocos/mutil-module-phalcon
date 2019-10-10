<?php
namespace App\Home;

use Phalcon\Mvc\ModuleDefinitionInterface,
	Phalcon\Loader,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Mvc\View\Engine\Volt as VoltEngine,
	Phalcon\DiInterface;


class Module implements ModuleDefinitionInterface{
		
		public function registerAutoloaders(DiInterface $di=NULL){
			$loader = new Loader();
			 $loader->registerNamespaces([
			 	'App\Home\Controllers'=>__DIR__.'/controllers/',
			 	'App\Home\Models'=>__DIR__.'/models/',
			 ]);
			 $loader->registerDirs([
			 		'../app/home/controllers',
			 ]);
			$loader->register();
		}

		public function registerServices(DiInterface $di){
			$di->setShared('view',function(){
				$view = new \Phalcon\Mvc\View();
				$view->setViewsDir(__DIR__.'/views/');
				$view->registerEngines([
					'.volt'=>function($view) {
						$volt = new VoltEngine($view, $this);
						$volt->setOptions(
							[
						 		'compiledPath' => __DIR__.'/cacheDir/',
	                	 		'compiledSeparator' => '_'
							]);
						return $volt;

					}]
				);
				
		
				return $view;
			});
			$di->set('dispatcher',function(){
		 		$dispatcher = new Dispatcher();
		 		$dispatcher->setDefaultNamespace('App\Home\Controllers');
				return $dispatcher;
			});
		}
		
}