<?php

class errorController extends Controller
{
    public function code404()
    {
        $this->assign_view('error/code404');

        $this->assign_var('page_title', '404');
        http_response_code(404);
    }

    public function html404()
    {
        $this->assign_view('error/html404');

        $this->assign_var('page_title', '404');
        http_response_code(404);
    }
}