<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目类
 */
class Projects extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->add_page_css('/static/css/projects.css');
        $this->render('projects/index');
    }
}