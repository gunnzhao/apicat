<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 位置表
 */
class Location_model extends CI_Model
{
    protected $table = 'location';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取所有省份
     */
    public function get_all_provinces()
    {
        $this->db->select('id,name');
        $this->db->order_by('id', 'asc');
        return $this->db->get_where($this->table, array('leveltype' => 1))->result_array();
    }

    /**
     * 获取所有城市
     */
    public function get_all_cities()
    {
        $this->db->select('id,name,parentid');
        $this->db->order_by('id', 'asc');
        return $this->db->get_where($this->table, array('leveltype' => 2))->result_array();
    }

    /**
     * 获取父级记录ID
     * @param  int $child_id 子ID
     * @return int 父ID
     */
    public function get_pid($child_id)
    {
        $this->db->select('parentid');
        $record = $this->db->get_where($this->table, array('id' => $child_id))->result_array();
        return $record ? $record[0]['parentid'] : 0;
    }
}