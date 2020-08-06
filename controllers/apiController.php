<?php

class apiController extends Controller
{
    public $JSON = array();

    public function __construct() {
        parent::__construct();

        $this->isAPI = true;
    }

    public function getStatus()
    {
        //$json =
    }
}