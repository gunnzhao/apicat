<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API文档表操作类
 */
class Doc_model extends CI_model
{

    // 表名称
    private $table = 'doc';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 增加文档记录
     * @param  int $pid 项目id
     * @param  int $cid 分类id
     * @param  string $title 文档名称
     * @param  string $url API地址
     * @param  int $method 请求方法
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_record($pid, $cid, $title, $url, $method)
    {
        $data = array(
            'pid'         => $pid,
            'cid'         => $cid,
            'title'       => $title,
            'url'         => $url,
            'method'      => $method,
            'insert_time' => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }
}