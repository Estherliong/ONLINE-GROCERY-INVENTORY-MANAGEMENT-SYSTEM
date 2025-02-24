<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit14edfafc991e296319671f98289891ff
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

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit14edfafc991e296319671f98289891ff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit14edfafc991e296319671f98289891ff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit14edfafc991e296319671f98289891ff::$classMap;

        }, null, ClassLoader::class);
    }
}
