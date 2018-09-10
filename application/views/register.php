<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
		<meta charset="utf-8">
		<title>用户注册 - ApiCat.net</title>
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
			<form class="form-signin" action="/register/do" method="post">
				<h3 class="text-center">欢迎加入ApiCat</h3><br/>
				<?php echo form_error('<div class="alert alert-warning" role="alert">', '</div>'); ?>
				<div class="form-group">
					<input type="text" class="form-control" name="nickname" placeholder="昵称" style="height:48px" value="<?php echo show_val('nickname'); ?>">
				</div>
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="邮箱" style="height:48px" value="<?php echo show_val('email'); ?>">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="passwd" placeholder="密码" style="height:48px">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="re_passwd" placeholder="确认密码" style="height:48px">
				</div>
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="verify_code" placeholder="验证码" aria-describedby="basic-addon2" style="height:48px">
						<span class="input-group-addon" id="basic-addon2"><img src="/verify_code?tm=123" title="点击更换验证码" onclick="javascript:this.src='/verify_code?tm='+Math.random()" style="height:34px;cursor:pointer;"></span>
					</div>
				</div>
				<button type="submit" class="btn btn-lblue btn-lg btn-block">注 册</button><br/>
				<p class="text-center"><a href="/login">已有账户？马上登录</a></p>
			</form>
		</div>
    </body>
</html>