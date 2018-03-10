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

    public function profile()
    {
        $this->render('settings/profile');
    }

    public function email()
    {
        $this->render('settings/email');
    }

    public function admin()
    {
        $this->render('settings/admin');
    }
}