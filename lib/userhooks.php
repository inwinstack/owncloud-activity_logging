<?php

namespace OCA\LogExtension;

use OCP\Util;
use OCP\User;

class UserHooks {

    private $userManager;
    private $UserFolder;
    public $class = '\OC\Files';

    public function __construct($userManager, $userFolder){
        $this->userManager = $userManager;
        $this->UserFolder = $userFolder;
        $this->class = '\OC\Files';
    }

    public function register() {
        $loginRecord = function($user) {
            \OCP\Util::writeLog('core',"user:" . \OCP\User::getDisplayName() . " login success", \OCP\Util::INFO);
        };

        $logoutRecord = function() {
            \OCP\Util::writeLog('core',"user:" . \OCP\User::getDisplayName() . " logout success", \OCP\Util::INFO);
        };

        $createRecord = function($node) {
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " cretes " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $deleteRecord = function($node) {
            \OCP\Util::writeLog('activity',get_class($node), \OCP\Util::INFO);
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " deletes " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $renameRecord = function($node) {
            \OCP\Util::writeLog('activity',get_class($node), \OCP\Util::INFO);
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " renames " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $touchRecord = function($node) {
            \OCP\Util::writeLog('activity',get_class($node), \OCP\Util::INFO);
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " touches " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $this->userManager->listen('\OC\User', 'postLogin', $loginRecord);
        $this->userManager->listen('\OC\User', 'logout', $logoutRecord);

        $this->UserFolder->listen('\OC\Files', 'postCreate', $createRecord);
        $this->UserFolder->listen('\OC\Files', 'postDelete', $deleteRecord);
        $this->UserFolder->listen('\OC\Files', 'preCreate', $createRecord);
        $this->UserFolder->listen('\OC\Files', 'preDelete', $deleteRecord);
        $this->UserFolder->listen('\OC\Files', 'postRename', $renameRecord);
        $this->UserFolder->listen('\OC\Files', 'postTouch', $touchRecord);
        }
}

