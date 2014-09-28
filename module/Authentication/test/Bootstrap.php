<?php
namespace AuthenticationTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Bootstrap {
    private static $serviceManager;
    private static $applicationConfig;

    public static function init() {
        error_reporting(E_ALL | E_STRICT);
        self::chroot();
        static::initAutoloader();
        $config = require "config/application.config.php";
        static::$applicationConfig = $config;
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager() {
        return static::$serviceManager;
    }

    public static function getApplicationConfig() {
        return static::$applicationConfig;
    }

    private static function chroot() {
        $rootPath = dirname(static::findParentPath("module"));
        chdir($rootPath);
    }

    private static function initAutoloader() {
        require "init_autoloader.php";
    }

    private static function findParentPath($path) {
        $dir = __DIR__;
        $previousDir = ".";
        while (!is_dir($dir . "/" . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . "/" . $path;
    }
}

Bootstrap::init();