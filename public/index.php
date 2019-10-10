<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Loader;
define('DS',DIRECTORY_SEPARATOR);
define('BASE_PATH',dirname(__DIR__));
define('APP_PATH',BASE_PATH.DS.'app'.DS);
define('APP_NS','App');
define('MODULES',['home','admin']);
define('DEFAILT_MODULE','home');


$di = new FactoryDefault();
$di->setShared('router',function(){
	 $router = new Router();
	 $router->setDefaultModule("home");
	
	 $router->add("/:controller",[
        'module'=>'home',
        'controller' => 1,
    	]
	);
    $router->add("/:controller/:action",[
        'module'=>'home',
        'controller' => 1,
        'action'     => 2,
    	]
	);
    $router->add("/:controller/:action/:param", [
        'module'=>'home',
        'controller' => 1,
        'action'     => 2,
        'param'      => 3,
    	]
	);


    //
     $router->add("/admin/:controller", [
        'module'=>'admin',
        'namespace'=>'App\Admin\Controllers', 
        'controller' => 1,
        'action'=>'index',
    ]);
    $router->add("/admin/:controller/:action",[
        'module'=>'admin',
        'namespace'=>'App\Admin\Controllers',
        'controller' => 1,
        'action'     => 2,
    ]);
    $router->add("/admin/:controller/:action/:param",[
        'module'=>'admin',
        'namespace'=>'App\Admin\Controllers',
        'controller' => 1,
        'action'     => 2,
        'param'      => 3,
    ]);
	return $router;
});

		


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

