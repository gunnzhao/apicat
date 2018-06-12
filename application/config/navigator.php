<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['nav'] = array(
    '/projects' => array(
        'title'   => '我的项目',
        'include' => array('invite', 'project', 'projects')
    )
);