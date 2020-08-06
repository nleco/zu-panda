<?php

ini_set('display_errors', 'off');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('WEBROOT', getcwd());
define('ROOT', dirname(WEBROOT));
define('MODULEROOT', ROOT . DIRECTORY_SEPARATOR . 'Modules');
require_once ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

// set up configs
// let's get the environment file settings. REQUIRED.  if not here, copy from config.boostrap.template.ini and set proper 'environment'
$configs_dir = ROOT . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'environment';
$bootstrap_config_file = $configs_dir . DIRECTORY_SEPARATOR . 'config.bootstrap.ini';

if (!file_exists($bootstrap_config_file)) {
    die('Missing "config/environment/config.bootstrap.ini" file.  Copy "config/environment/config.bootstrap.template.ini" and rename to set the file.');
}
Config::load($bootstrap_config_file);

// run the config files, set default
$env = 'dev';
if (!empty(Config::get('env.environment'))) {
    $env = Config::get('env.environment');
}

Config::load(
    $configs_dir . DIRECTORY_SEPARATOR . 'config.master.ini',
    $configs_dir . DIRECTORY_SEPARATOR . 'config.' . $env . '.ini',
    $configs_dir . DIRECTORY_SEPARATOR . 'config.ini'
);


// set up showing of errors or not
if (Config::get('env.display_errors')) {
    error_reporting(E_ALL);
    ini_set('log_errors', 'on');
}

// template cache dir
$compiled_templates_dir = ROOT . DIRECTORY_SEPARATOR . 'views_c';
if (!file_exists($compiled_templates_dir)) {
    mkdir($compiled_templates_dir, 0777, true);
}

$dispatch = new Dispatcher();
$dispatch->dispatch();
