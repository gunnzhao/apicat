<?php
/**
 * 布局类
 * 用于定义一个基类模板搭建框架，其他模板文件嵌入基类模板中
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout {

    public $obj;
    public $layout;

    function __construct($layout = array('base')) {
        $this->obj =& get_instance();
        // 加载layout的视图
        $this->layout = $layout[0];
    }

    function set_layout($layout) {
        $this->layout = $layout;
    }

    /**
     * 加载视图
     * @param string $view
     * @param array $data
     * @param bool $return
     */
    public function view($view, $data=null, $return=false) {
        $data['content_for_layout'] = $this->obj->load->view($view, $data['subpage_data'], true);
        unset($data['subpage_data']);
        if ($return) {
            $output = $this->obj->load->view($this->layout, $data, true);
            return $output;
        } else {
            $this->obj->load->view($this->layout, $data, false);
        }
    }

}