<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目表操作类
 */
class Projects_model extends CI_model
{

    // 表名称
    private $table = 'projects';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建项目
     * @param  string $title 项目名称
     * @param  int $authority 项目权限
     * @param  string $description 项目描述
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_project($title, $authority, $description)
    {
        $data = array(
            'title'       => $title,
            'authority'   => $authority,
            'description' => $description,
            'insert_time' => time(),
            'update_time' => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }
}