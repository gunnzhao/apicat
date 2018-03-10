<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 验证码调用类
 */
class Verify_code extends MY_Controller {
    
    public function index()
    {
        $this->load->library('verifycode');
        $this->load->library('session');
        $this->verifycode->create_code();
        $this->session->set_userdata('verify_code', $this->verifycode->get_code());
        $this->verifycode->get_img();
    }
}