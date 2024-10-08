<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc83040a8ac74379e5841351f2899a7ac
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Templates\\' => 10,
        ),
        'I' => 
        array (
            'InvoiceNinja\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Templates\\' => 
        array (
            0 => __DIR__ . '/../..' . '/templates',
        ),
        'InvoiceNinja\\' => 
        array (
            0 => __DIR__ . '/../..' . '/InvoiceNinja',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'InvoiceNinja\\Api\\BaseApi' => __DIR__ . '/../..' . '/InvoiceNinja/Api/BaseApi.php',
        'InvoiceNinja\\Api\\ClientApi' => __DIR__ . '/../..' . '/InvoiceNinja/Api/ClientApi.php',
        'InvoiceNinja\\Api\\InvoiceApi' => __DIR__ . '/../..' . '/InvoiceNinja/Api/InvoiceApi.php',
        'InvoiceNinja\\Api\\ProductApi' => __DIR__ . '/../..' . '/InvoiceNinja/Api/ProductApi.php',
        'InvoiceNinja\\Api\\ProfileApi' => __DIR__ . '/../..' . '/InvoiceNinja/Api/ProfileApi.php',
        'InvoiceNinja\\Controllers\\BaseController' => __DIR__ . '/../..' . '/InvoiceNinja/Controllers/BaseController.php',
        'InvoiceNinja\\Controllers\\ClientController' => __DIR__ . '/../..' . '/InvoiceNinja/Controllers/ClientController.php',
        'InvoiceNinja\\Controllers\\ProductController' => __DIR__ . '/../..' . '/InvoiceNinja/Controllers/ProductController.php',
        'InvoiceNinja\\Controllers\\SettingsController' => __DIR__ . '/../..' . '/InvoiceNinja/Controllers/SettingsController.php',
        'InvoiceNinja\\Controllers\\WidgetController' => __DIR__ . '/../..' . '/InvoiceNinja/Controllers/WidgetController.php',
        'InvoiceNinja\\Init' => __DIR__ . '/../..' . '/InvoiceNinja/Init.php',
        'InvoiceNinja\\Utils\\Formatting' => __DIR__ . '/../..' . '/InvoiceNinja/Utils/Formatting.php',
        'InvoiceNinja\\WordPress\\PostApi' => __DIR__ . '/../..' . '/InvoiceNinja/WordPress/PostApi.php',
        'InvoiceNinja\\WordPress\\SettingsApi' => __DIR__ . '/../..' . '/InvoiceNinja/WordPress/SettingsApi.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc83040a8ac74379e5841351f2899a7ac::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc83040a8ac74379e5841351f2899a7ac::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc83040a8ac74379e5841351f2899a7ac::$classMap;

        }, null, ClassLoader::class);
    }
}
