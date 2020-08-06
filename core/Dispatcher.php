<?php

class Dispatcher
{
    public function dispatch()
    {
        $request = new Request();
        Router::parse($request);

        $controller = $this->loadController($request);
        $controller->render();
    }

    public function loadController($request)
    {
        $controller_name = $request->controller;
        $action_name     = $request->action;
        $params          = $request->params;

        $controller_class_name = $controller_name . 'Controller';
        $found = false;

        if (class_exists($controller_class_name)) {
            $controller = new $controller_class_name();
            if (method_exists($controller, $action_name)) {
                $found = true;
            }
        }

        if (!$found) {
            $controller_name = 'error';
            $controller_class_name = $controller_name . 'Controller';
            $action_name     = 'html404';

            $controller = new $controller_class_name();
        }

        call_user_func_array([$controller, $action_name], $request->params);

        $controller->assign_env('controller',        $controller_name);
        $controller->assign_env('controller_action', $action_name);
        $controller->assign_env('params',            $params);

        $controller->assign_env('is_api', $controller->is_api);

        return $controller;
    }
}