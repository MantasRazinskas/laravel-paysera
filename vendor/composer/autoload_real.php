<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit2b2dfd6a3fa75405fefd943b80a06e72
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit2b2dfd6a3fa75405fefd943b80a06e72', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit2b2dfd6a3fa75405fefd943b80a06e72', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit2b2dfd6a3fa75405fefd943b80a06e72::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
