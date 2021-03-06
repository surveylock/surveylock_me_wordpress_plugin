<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit11e2386126b1c264a11b47152ee20c94
{
    public static $files = array (
        '5986aba6c5607f5c5605c645753bb69d' => __DIR__ . '/../..' . '/config/providers.php',
        'b57a7d126c35fd37aeddb9b8bfb795dc' => __DIR__ . '/../..' . '/utils/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TenPixls\\SurveyLockMe\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TenPixls\\SurveyLockMe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit11e2386126b1c264a11b47152ee20c94::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit11e2386126b1c264a11b47152ee20c94::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
