<?php

class settingsController extends Controller
{
    public function __contstruct() {
        parent::__contstruct();

        $this->assign_var('page_title', 'Settings');
    }

    public function index()
    {
        $this->assign_view('settings/index');
    }
}