<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function index()
    {
        // $a = password_hash("wahaha", PASSWORD_DEFAULT);
        // var_dump($a);
        // $b = password_hash("wahaha", PASSWORD_DEFAULT);
        // var_dump($b);
        // var_dump(password_verify('wahaha', $a));
        // var_dump(password_verify('wahaha', $b));
        $this->load->view('login');
    }

    public function register()
    {
        $this->load->view('register');
	}
}