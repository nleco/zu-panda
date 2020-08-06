<?php

class presetsController extends Controller
{
    public function __contstruct() {
        parent::__contstruct();

        $this->assign_var('page_title', 'Presets');
    }

    public function index()
    {
        $this->assign_view('presets/index');
    }
}