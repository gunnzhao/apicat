<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 地区类
 */
class Location extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 所有省市记录
     */
    public function all_cities()
    {
        $this->load->model('location_model');
        $records = $this->location_model->get_all_cities();

        $result = array();
        foreach ($records as $v) {
            $result[$v['parentid']][] = array('id' => $v['id'], 'name' => $v['name']);
        }
        $this->response_json_ok(array('records' => $result));
    }
}