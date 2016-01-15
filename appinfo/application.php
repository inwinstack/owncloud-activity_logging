<?php

namespace OCA\Activity_Logging\AppInfo;

use \OCP\AppFramework\App;

use \OCA\Activity_Logging\UserHooks;

class Application extends App {

    public function __construct(array $urlParams=array()){
        parent::__construct('activity_logging', $urlParams);

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