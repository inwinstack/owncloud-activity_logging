<?php

namespace OCA\Activity_Logging;

use OCP\Util;
use OCP\User;
use OC\Files;

class UserHooks {
    private $userManager;
    private $UserFolder;
    private $UserRole;

    public function __construct($userManager, $userFolder){
        $this->userManager = $userManager;
        $this->UserFolder = $userFolder;
    }
    
    public static function formatLogMessage($user,$actionMsg){
        $UserRole = \OC::$server->getConfig()->getUserValue(\OC_User::getUser(), "settings", "role","undefined");
        $regionData = self::formatRegionData();
        $UserRegion = $regionData[0];
        $UserSchoolCode = $regionData[1];
        
        return "user:" . $user.
                " role:" . $UserRole .
                " region:" . $UserRegion .
                " schoolCode:" . $UserSchoolCode .
                " agent:" . self::checkAgent() .
                " action:" . $actionMsg;
    }
    
    
    public static function share($params) {
        
        $regionData = self::formatRegionData();
        $UserRegion = $regionData[0];
        $UserSchoolCode = $regionData[1];
        
        if ( ($params['shareType'] === \OCP\Share::SHARE_TYPE_USER) || ($params['shareType'] === \OCP\Share::SHARE_TYPE_GROUP) ) {
            $params['shareType'] === \OCP\Share::SHARE_TYPE_USER ? $shareType = " with User: " : $shareType = " with Group: ";
            
            Util::writeLog('core',
                            self::formatLogMessage($params['uidOwner'], 
                                    "share " . $params['fileTarget'] . $shareType . $params['shareWith']), 
                            Util::INFO);

        }
        else {
            Util::writeLog('core',
                            self::formatLogMessage($params['uidOwner'], 
                                    "share " . $params['fileTarget'] . ' with link'),
                            Util::INFO);

        }
    }
    
    public static function getOS($userAgent) {
        $os_platform    =   "undefined";
        
        $os_array       =   array(
                '/windows/i'           => 'Windows',
                '/windows nt 10/i'     =>  'Windows',
                '/windows nt 6.3/i'     =>  'Windows',
                '/windows nt 6.2/i'     =>  'Windows',
                '/windows nt 6.1/i'     =>  'Windows',
                '/windows nt 6.0/i'     =>  'Windows',
                '/windows nt 5.2/i'     =>  'Windows',
                '/windows nt 5.1/i'     =>  'Windows',
                '/windows xp/i'         =>  'Windows',
                '/windows nt 5.0/i'     =>  'Windows',
                '/windows me/i'         =>  'Windows',
                '/win98/i'              =>  'Windows',
                '/win95/i'              =>  'Windows',
                '/win16/i'              =>  'Windows',
                '/macintosh|mac os x/i' =>  'MacOS',
                '/mac_powerpc/i'        =>  'MacOS',
                '/linux/i'              =>  'Linux',
                '/ubuntu/i'             =>  'Ubuntu',
                '/iphone/i'             =>  'iOS',
                '/ipod/i'               =>  'iOS',
                '/ipad/i'               =>  'iOS',
                '/android/i'            =>  'Android',
                '/blackberry/i'         =>  'BlackBerry',
                '/webos/i'              =>  'Mobile'
        );
        
        foreach ($os_array as $regex => $value) {
        
            if (preg_match($regex, $userAgent)) {
                $os_platform    =   $value;
            }
        
        }
        
        return $os_platform;
    }
    
    public static function getBrowser($userAgent) {
        $browser        =   "undefined";
        $browser_array  =   array(
                '/msie|trident/i'       =>  'IE',
                '/firefox/i'    =>  'Firefox',
                '/safari/i'     =>  'Safari',
                '/chrome/i'     =>  'Chrome',
                '/edge/i'       =>  'Edge',
                '/opera/i'      =>  'Opera',
                '/netscape/i'   =>  'Netscape',
                '/maxthon/i'    =>  'Maxthon',
                '/konqueror/i'  =>  'Konqueror',
                '/mobile/i'     =>  'Handheld_Browser',
                '/MOE_Storage/i'=>  'Desktop',
                '/ownCloud-android/i' => 'Mobile',
        );
        
        foreach ($browser_array as $regex => $value) {
        
            if (preg_match($regex, $userAgent)) {
                $browser    =   $value;
            }
        
        }
        
        return $browser;
    }
    
    public static function checkAgent(){
        if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
            $agent = $_SERVER['HTTP_USER_AGENT'];
            return self::getBrowser($agent). '_' .self::getOS($agent);
        }
        return 'undefined_undefined';
    }
    
    public static function formatRegionData(){
        $regionData = \OC::$server->getConfig()->getUserValue(\OC_User::getUser(), "settings", "regionData",false);
        //$regionData = ['region':1, 'schoolCode':1234]
        if ($regionData){
            $regionData = json_decode($regionData,true);
            $UserRegion = $regionData['region'];
            $UserSchoolCode = $regionData['schoolCode'];
        }
        else{
            $UserRegion = "undefined";
            $UserSchoolCode = "undefined";
        }
        return [$UserRegion,$UserSchoolCode];
    }
    
    public function register() {
        $loginRecord = function($user) {

            Util::writeLog('core',
                            self::formatLogMessage(User::getDisplayName(), 
                                                    "login success"), 
                            Util::INFO);

        };

        $logoutRecord = function() {
            
            Util::writeLog('core',
                            self::formatLogMessage(User::getDisplayName(), 
                                                    "logout success"), 
                            Util::INFO);
            
        };

        $createRecord = function($node) {
   
            Util::writeLog('core',
                            self::formatLogMessage(User::getDisplayName(), 
                                                    "creates " . $node->getName() ." success"), 
                            Util::INFO);
            
        };

        $deleteRecord = function($node) {

            Util::writeLog('core',
                            self::formatLogMessage(User::getDisplayName(), 
                                                    "deletes " . $node->getName() . " success"),
                            Util::INFO);
            
        };

        $renameRecord = function($node) {

            Util::writeLog('core',
                            self::formatLogMessage(User::getDisplayName(), 
                                                    "renames " . $node->getName() . " success"),
                            Util::INFO);
            
        };

//         $touchRecord = function($node) {
//             $UserRole = \OC::$server->getConfig()->getUserValue(\OC_User::getUser(), "settings", "role","undefined");
            
//             $regionData = self::formatRegionData();
//             $UserRegion = $regionData[0];
//             $UserSchoolCode = $regionData[1];
            
//             Util::writeLog('activity',"user:" . User::getDisplayName() . 
//                                       " role:" . $UserRole .
//                                       " region:" . $UserRegion .
//                                       " schoolCode:" . $UserSchoolCode .
//                                       " action:touches " . $node->getName() . " success", Util::INFO);
//         };

        Util::connectHook('OCP\Share', 'post_shared', 'OCA\Activity_Logging\UserHooks', 'share');

        $this->userManager->listen('\OC\User', 'postLogin', $loginRecord);
        $this->userManager->listen('\OC\User', 'logout', $logoutRecord);

        $this->UserFolder->listen('\OC\Files', 'postCreate', $createRecord);
        $this->UserFolder->listen('\OC\Files', 'postDelete', $deleteRecord);

        $this->UserFolder->listen('\OC\Files', 'postRename', $renameRecord);
        }
}


