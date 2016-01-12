<?php

namespace OCA\LogExtension\AppInfo;

use \OCP\AppFramework\App;

use \OCA\LogExtension\UserHooks;

class Application extends App {

    public function __construct(array $urlParams=array()){
        parent::__construct('logextension', $urlParams);

        $container = $this->getContainer();

        /**
         * Controllers
         */
        $container->registerService('UserHooks', function($c) {
            return new UserHooks(
                $c->query('ServerContainer')->getUserSession(),
                $c->query('ServerContainer')->getRootFolder()
            );
        });
    }
}