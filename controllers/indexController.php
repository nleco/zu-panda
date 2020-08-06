<?php

class indexController extends Controller
{

    public function index()
    {
        $this->assign_view('index/index');

        $this->assign_var('page_title', 'Status');
        $this->assign_var('var2', 'v2');
    }
}