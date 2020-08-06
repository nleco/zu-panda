<?php

/**
 * This is designed as an optional/test logging method
 * Output file is set in config.ini. defaults to 'test_log'
 * Ex entry = test_log = /tmp/ktest.log
 * can pass config entry to overrider (ex: 'media_test_log')
 * to prevent logging, remove call to this func, or comment out or remove config param
 * All log entries preceeded by date-time
 *
 * @param $chunk required - can be var or array. Array auto exploded
 * @param $label optional - used as marker
 * @param $logfile optional - defaults to config::get('system.test_log');
 */
function logger($chunk, $label = '')
{
    //$logfile = $logfile != '' ? config::get('system.'.$logfile, true) : config::get('system.test_log', true);
    $logfile = ROOT . '/log.logger.log';

    if ($logfile && file_exists($logfile)) {
        //get where it came from
        $bt = debug_backtrace();

        //$levels = 1;
        $levels = 0;
        if (isset($bt[$levels])) {
            $caller = $bt[$levels]['function'].'()';

            if (isset($bt[$levels]['class'])) {
                $caller = $bt[$levels]['class'] . "::" . $caller;
            }

            if (isset($bt[$levels]['line'])) {
                $caller = $bt[$levels]['line'] . " - " . $caller;
            }

            if (isset($bt[$levels]['file'])) {
                $match = array();
                preg_match('@.*/([^/]*)?/([^/]+\.php)$@', $bt[$levels]['file'], $match);
                $caller = ($match[1]?$match[1].'/':'') . $match[2] . " - line " . $caller;
            }
        } else {
            $caller = 'main';
        }

        if (is_array($chunk)) {
            $chunk = print_r($chunk, 1);
        }
        $chunk = date('Y-m-d H:i:s') . "| $label $chunk";

        file_put_contents($logfile, $caller . "\n", FILE_APPEND);
        file_put_contents($logfile, $chunk . "\n\n", FILE_APPEND);
    }
}