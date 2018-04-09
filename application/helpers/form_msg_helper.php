<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 页面表单反馈信息显示辅助函数
 * 每次页面提交的表单到后台需要做判断，如果表单验证不通过，需要返回指出错误信息，并将
 * 之前表单中的数据重新填进其中。该文件下的辅助函数就是帮助开发者在表单页面能更方便地
 * 展示错误信息，省去一些重复性的代码、判断等。
 * 如果表单提交成功，要给用户显示成功的信息。
 */

if (!function_exists('set_error')) {
    /**
     * 设置错误信息
     * @param  string $error_msg 错误信息
     * @return void
     */
    function set_error($error_msg)
    {
        $_SESSION['_formmsg_error_msg'] = $error_msg;
    }
}

if (!function_exists('form_error')) {
    /**
     * 显示错误信息
     * @return string
     */
    function form_error($prefix = '<p>', $suffix = '</p>')
    {
        if (isset($_SESSION['_formmsg_error_msg']) and !empty($_SESSION['_formmsg_error_msg'])) {
            $tmp = $_SESSION['_formmsg_error_msg'];
            unset($_SESSION['_formmsg_error_msg']);
            return $prefix . $tmp . $suffix;
        } else {
            return '';
        }
    }
}

if (!function_exists('set_ok')) {
    /**
     * 设置成功信息
     * @param  string $msg 成功提示信息
     * @return void
     */
    function set_ok($msg)
    {
        $_SESSION['_formmsg_ok_msg'] = $msg;
    }
}

if (!function_exists('form_ok')) {
    /**
     * 显示成功信息
     * @return string
     */
    function form_ok($prefix = '<p>', $suffix = '</p>')
    {
        if (isset($_SESSION['_formmsg_ok_msg']) and !empty($_SESSION['_formmsg_ok_msg'])) {
            $tmp = $_SESSION['_formmsg_ok_msg'];
            unset($_SESSION['_formmsg_ok_msg']);
            return $prefix . $tmp . $suffix;
        } else {
            return '';
        }
    }
}

if (!function_exists('init_form_post')) {
    /**
     * 初始化form数据
     * 将表单提交的数据放入指定数组中
     * @return void
     */
    function init_form_post()
    {
        $_SESSION['_formmsg_form_data'] = array();
        
        if (empty($_POST)) {
            return;
        }

        foreach ($_POST as $k => $v) {
            if ($v !== '') {
                $_SESSION['_formmsg_form_data'][$k] = $v;
            }
        }
    }
}

if (!function_exists('show_val')) {
    /**
     * 在input中显示提交的数据
     * @param  string $key input名称
     * @return string
     */
    function show_val($key, $default = '')
    {
        if (isset($_SESSION['_formmsg_form_data']) and isset($_SESSION['_formmsg_form_data'][$key])) {
            $tmp = $_SESSION['_formmsg_form_data'][$key];
            unset($_SESSION['_formmsg_form_data'][$key]);
            return $tmp;
        } else {
            return $default;
        }
    }
}

if (!function_exists('show_select')) {
    /**
     * 决定是否在select的option中是否呈选中状态
     * @param  string $key select名称
     * @param  string $default 默认值
     * @param  string $val option的value值
     * @return string
     */
    function show_select($key, $default = '', $val = 1)
    {
        if (isset($_SESSION['_formmsg_form_data']) and isset($_SESSION['_formmsg_form_data'][$key])) {
            if ($_SESSION['_formmsg_form_data'][$key] == $val) {
                unset($_SESSION['_formmsg_form_data'][$key]);
                return 'selected';
            }
        } else {
            if ($default == $val) {
                return 'selected';
            }
            return '';
        }
    }
}

if (!function_exists('show_radio')) {
    /**
     * 决定是否将radio选中
     * @param  string $key radio名称
     * @param  string $default 默认值
     * @param  string $val radio中对应的value值
     * @return string
     */
    function show_radio($key, $default = '', $val = 1)
    {
        if (isset($_SESSION['_formmsg_form_data']) and isset($_SESSION['_formmsg_form_data'][$key])) {
            if ($_SESSION['_formmsg_form_data'][$key] == $val) {
                unset($_SESSION['_formmsg_form_data'][$key]);
                return 'checked';
            }
        } else {
            if ($default == $val) {
                return 'checked';
            }
            return '';
        }
    }
}

if (!function_exists('show_checkbox')) {
    /**
     * 决定是否将checkbox选中
     * @param  string $key checkbox名称
     * @param  string $default 默认值
     * @param  string $val checkbox的value值
     * @return string
     */
    function show_checkbox($key, $default = '', $val = 1)
    {
        if (isset($_SESSION['_formmsg_form_data']) and isset($_SESSION['_formmsg_form_data'][$key])) {
            if ($_SESSION['_formmsg_form_data'][$key] == $val) {
                unset($_SESSION['_formmsg_form_data'][$key]);
                return 'checked';
            }
        } else {
            if ($default == $val) {
                return 'checked';
            }
            return '';
        }
    }
}