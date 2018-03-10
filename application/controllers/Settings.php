<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登出类
 */
class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->load->library('session');
        var_dump($this->session);
    }
}