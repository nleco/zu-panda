<?php

if (!file_exists(ROOT . '/Config/environment/config.bootstrap.ini')) {
    die('Missing config.bootstrap.ini env file.');
}
Config::load(ROOT . '/Config/environment/config.bootstrap.ini');
$ENV = Config::get('env.environment');

Config::load(
    ROOT . 'config.master.ini',
    ROOT . "config.{$ENV}.ini",
    ROOT . 'config.ini'
);
