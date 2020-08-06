<?php

class Controller
{
    public $layout = "default";
    public $css_files = [];
    public $js_files = [];
    public $module = null;
    public $is_api = false;
    public $view = 'error/html404';

    public $t;

    public function __construct()
    {
        $caching = Config::get('smarty.caching') ? true : false;

        // smarty templating: https://www.smarty.net/docs/en/installing.smarty.basic.tpl
        $this->t = new Smarty();
        $this->t->setTemplateDir(ROOT . DIRECTORY_SEPARATOR . 'views');
        $this->t->setCompileDir(ROOT . DIRECTORY_SEPARATOR . 'views_c');
        $this->t->setConfigDir(ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Smarty');
        $this->t->setCacheDir(ROOT . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'Smarty');

        $this->t->debugging_ctrl = Config::get('smarty.debug') ? 'URL' : 'NONE';

        if (!$caching) {
            $this->t->clearAllCache();
            $this->t->clearCompiledTemplate();
            $this->t->force_compile = true;
        }

        $this->_init_blank_vars();
    }

    private function _init_blank_vars()
    {
        // set these to null
        $nulls = ['controller'];
        foreach ($nulls as $value) {
            $this->t->assign($value, null);
        }
    }

    public function get_env($key=null)
    {
        $env = $this->t->getTemplateVars('env');
        if ($key === null) {
            return $env;
        } else if (is_string($key) && !empty($key) && isset($env[$key])) {
            return $env[$key];
        } else {
            return null;
        }
    }

    public function assign_env($key, $value)
    {
        $env = $this->t->getTemplateVars('env');
        $env[$key] = $value;
        $this->t->assign('env', $env);
    }

    public function get_var($key=null)
    {
        return !empty($key) ? $this->getTemplateVars($key) : null;
    }

    public function assign_var($key, $value)
    {
        if (!empty($key) && $key !== 'env') {
            $this->t->assign($key, $value);
        }
    }

    public function assign_view($view)
    {
        $this->view = $view . '.tpl';
    }

    public function assign_css($file)
    {
        $this->css_files[] = $file;
    }

    public function assign_js($file)
    {
        $this->js_files[] = $file;
    }

    public function render()
    {
        $this->assign_var('css_files', $this->css_files);
        $this->assign_var('js_files', $this->js_files);

        $this->t->display('pages' . DIRECTORY_SEPARATOR . $this->view);
    }
}