<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
		<meta charset="utf-8">
		<title>修改密码 - ApiCat.net</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="/static/img/favicon.ico">

		<!-- Le styles -->
		<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
		body {
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
		}

		.form-signin {
			max-width: 450px;
			padding: 19px 29px 29px;
			margin: 0 auto 20px;
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
					border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
					box-shadow: 0 1px 2px rgba(0,0,0,.05);
		}

		.btn-lblue {
			border: 1px solid #3079ed;
			color: #ffffff;
			background-color: #4b8cf7;
		}

		.btn-lblue:hover,
		.btn-lblue:focus,
		.btn-lblue:active,
		.btn-lblue.active,
		.btn-lblue.disabled,
		.btn-lblue[disabled] {
			border: 1px solid #3079ed;
			color: #ffffff;
			background-color: #4b8cf7;
		}
		</style>
    </head>

    <body>
        <div class="container">
            <form class="form-signin" action="/forget/do_password" method="post">
                <h3 class="text-center">修改密码</h3><br/>
                <?php echo form_error('<div class="alert alert-warning" role="alert">', '</div>'); ?>
                <div class="form-group">
                    <input type="password" class="form-control" name="newpasswd" placeholder="新密码" style="height:48px">
                </div>
				<div class="form-group">
                    <input type="password" class="form-control" name="re_newpasswd" placeholder="确认新密码" style="height:48px">
                </div>
				<input type="hidden" name="code" value="<?php echo $code; ?>">
                <button type="submit" class="btn btn-lblue btn-lg btn-block">确认修改</button><br/>
            </form>
        </div>
    </body>
</html>
