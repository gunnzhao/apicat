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
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('project/index');
    }

    public function add()
    {
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('project/add');
    }
}