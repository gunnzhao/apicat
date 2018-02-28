<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_code extends MY_Controller {
    
    public function index()
    {
        $this->load->library('verifycode');
        $this->verifycode->get_img();
    }
}