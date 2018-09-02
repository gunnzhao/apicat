<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Markdown文档类
 */
class Markdown extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
    }

    public function add()
    {
        $this->load->view('markdown/add_markdown');
    }
}