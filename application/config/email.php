<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户代理（user agent）
 */
$config['useragent'] = 'ApiCat';

/**
 * 邮件发送协议
 * 支持mail, sendmail, smtp
 */
$config['protocol'] = 'sendmail';

/**
 * 服务器上 Sendmail 的实际路径
 * 当protocol为sendmail时需要设置
 */
$config['mailpath'] = '/usr/sbin/sendmail';

/**
 * SMTP 服务器地址
 * 当protocol为smtp时需要设置
 */
$config['smtp_host'] = '';

/**
 * SMTP 用户名
 * 当protocol为smtp时需要设置
 */
$config['smtp_user'] = '';

/**
 * SMTP 密码
 * 当protocol为smtp时需要设置
 */
$config['smtp_pass'] = '';

/**
 * SMTP 端口
 */
$config['smtp_port'] = 25;

/**
 * SMTP 超时时间（单位：秒）
 */
$config['smtp_timeout'] = 5;

/**
 * 是否启用 SMTP 持久连接
 * true为是 false为否
 */
$config['smtp_keepalive'] = false;

/**
 * SMTP 加密方式
 * 2种加密方式(tls 和 ssl)
 */
$config['smtp_crypto'] = '';

/**
 * 是否启用自动换行
 * true是 false否
 */
$config['wordwrap'] = true;

$config[''] = '';

$config[''] = '';

$config[''] = '';

$config[''] = '';

$config[''] = '';

$config[''] = '';

$config[''] = '';