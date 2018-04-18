<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目类
 */
class Project extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('project_model');
    }

    public function index()
    {
        $this->add_page_css('/static/css/project.css');
        $this->render('project/index');
    }
}