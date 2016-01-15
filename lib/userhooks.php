<?php

namespace OCA\Activity_Logging;

use OCP\Util;
use OCP\User;
use OC\Files;

class UserHooks {

    private $userManager;
    private $UserFolder;

    public function __construct($userManager, $userFolder){
        $this->userManager = $userManager;
        $this->UserFolder = $userFolder;
    }

    public function register() {
        $loginRecord = function($user) {
            \OCP\Util::writeLog('core',"user:" . \OCP\User::getDisplayName() . " action:login success", \OCP\Util::INFO);
        };

        $logoutRecord = function() {
            \OCP\Util::writeLog('core',"user:" . \OCP\User::getDisplayName() . " action:logout success", \OCP\Util::INFO);
        };

        $createRecord = function($node) {
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " action:cretes " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $deleteRecord = function($node) {
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " action:deletes " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $renameRecord = function($node) {
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " action:renames " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $touchRecord = function($node) {
            \OCP\Util::writeLog('activity',"user:" . \OCP\User::getDisplayName() . " action:touches " . $node->getName() . " sucess", \OCP\Util::INFO);
        };

        $this->userManager->listen('\OC\User', 'postLogin', $loginRecord);
        $this->userManager->listen('\OC\User', 'logout', $logoutRecord);

        $this->UserFolder->listen('\OC\Files', 'postCreate', $createRecord);
        $this->UserFolder->listen('\OC\Files', 'postDelete', $deleteRecord);

        $this->UserFolder->listen('\OC\Files', 'postRename', $renameRecord);
        }
}

