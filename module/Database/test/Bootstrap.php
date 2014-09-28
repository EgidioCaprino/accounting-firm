<?php
namespace DatabaseTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Bootstrap {
    private static $serviceManager;

    public static function init() {
        error_reporting(E_ALL | E_STRICT);
        self::chroot();
        static::initAutoloader();
        $config = require "config/application.config.php";
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