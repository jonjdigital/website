<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e61cc6294e989f9d7da456d7bdb7e18
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e61cc6294e989f9d7da456d7bdb7e18::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e61cc6294e989f9d7da456d7bdb7e18::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
