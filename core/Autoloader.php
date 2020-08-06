<?php
/**
 * easy autload of classes so you don't need to include the file. it auto includes it.
 */

require_once ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Logger.php';

class Autoloader
{
    /**
      * substitude the common backslash to forward.
      *
      * @param string $path the path to escape
      * @return void
      */
    private static function _escape($path)
    {
        if (!is_string($path)) {
            return null;
        }

        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * will return an array with all directory paths and subdirectories of the given $parent path
     *
     * @param string $parent the parent include path to traverse down
     * @return array array of include paths
     */
    private static function _getDirectories($parent)
    {

        if (empty($parent) || !is_string($parent)) {
            return null;
        }

        $dirs = array();
        $parent = self::_escape($parent);
        if (is_readable($parent) && is_dir($parent)) {
            $dirs[] = $parent;

            $list = scandir($parent);
            foreach ($list as $item) {
                $child = $parent . DIRECTORY_SEPARATOR . $item;
                if (is_readable($child) && is_dir($child) && !in_array($item, array('.', '..'))) {
                    $dirs[] = $child;
                    $dirs = array_merge($dirs, self::_getDirectories($child));
                }
            }
        }

        return $dirs;
    }

    /**
     * Project defined user include locations and their subdirectories.
     *
     * @return void
     */
    private static function _registerDirs()
    {
        spl_autoload_register(function ($class) {
            $directories = [
                ROOT  . DIRECTORY_SEPARATOR . 'core',
                ROOT  . DIRECTORY_SEPARATOR . 'lib',
                ROOT  . DIRECTORY_SEPARATOR . 'controllers',
                ROOT  . DIRECTORY_SEPARATOR . 'modules'];

            $paths = array();

            foreach ($directories as $d) {
                $paths = array_merge($paths, self::_getDirectories($d));
            }

            $paths = array_unique($paths);

            foreach ($paths as $directory) {
                $file = $directory . DIRECTORY_SEPARATOR . $class.'.php';
                $file = self::_escape($file);

                if (is_readable($file) && is_file($file)) {
                    require_once $file;
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Autoloader registration for PSR-4 standards
     * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
     *
     * @return void
     */
    private static function _registerPSR4()
    {
        /**
         *
         * After registering this autoload function with SPL, the following line
         * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
         * from /path/to/project/src/Baz/Qux.php:
         *
         *      new \Foo\Bar\Baz\Qux;
         *
         * @param string $class The fully-qualified class name.
         * @return void
         */
        spl_autoload_register(function ($class) {
            $sources = array(
                'Zu\\Panda\\' => 'lib' . DIRECTORY_SEPARATOR
            );
            $found = false;

            // does the class use the namespace prefix?
            foreach ($sources as $_prefix => $_base_dir) {
                $len = strlen($_prefix);
                if (strncmp($_prefix, $class, $len) === 0) {
                    $prefix = $_prefix;
                    $base_dir = $_base_dir;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return;
            }

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {

                require_once $file;
            }
        });
    }

    /**
     * registers the autoloader
     *
     * @return void
     */
    public static function register()
    {
        self::_registerPSR4();
        self::_registerDirs();

        require_once ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    }
}
Autoloader::register();