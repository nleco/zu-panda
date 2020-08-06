<?php

class Router
{
    static public function parse($request)
    {
        $url = trim($request->url);
        $url = explode('?', $url);
        $url = $url[0];

        $explode_url = explode('/', $url);

        $request->controller = !empty($explode_url[1]) ? $explode_url[1] : 'index';
        $request->action = !empty($explode_url[2]) ? $explode_url[2] : 'index';
        $request->params = array();

        if (!empty($explode_url[3])) {
            $request->params = array_slice($explode_url, 3);
        }

        $name = $request->controller . "Controller";
        $file = ROOT . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $name . '.php';
    }
}