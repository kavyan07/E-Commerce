<?php

class Core_Autoload
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            // Converts Model_Product_Resource -> libs/Model/Product/Resource.php
            $path = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

            // Search in libs
            $libsFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . $path;
            if (file_exists($libsFile)) {
                require_once $libsFile;
                return;
            }
        });
    }
}
