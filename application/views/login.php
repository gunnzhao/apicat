<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
		<meta charset="utf-8">
		<title>用户登录 - ApiCat.net</title>
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
			<form class="form-signin" action="/login/do" method="post">
				<h3 class="text-center">用户登录</h3><br/>
				<?php echo form_error('<div class="alert alert-warning" role="alert">', '</div>'); ?>
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="邮箱" style="height:48px" value="<?php echo show_val('email'); ?>">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="passwd" placeholder="密码" style="height:48px">
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<div class="checkbox">
								<label><input type="checkbox" name="remember_me" value="1"> 记住我</label>
							</div>
						</div>
						<div class="col-sm-6">
							<p class="text-right" style="margin-top: 10px;margin-bottom: 10px;"><a href="/forget">找回密码</a></p>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-lblue btn-lg btn-block">登录</button><br/>
				<p class="text-center"><a href="/register">还没有账户，马上注册</a></p>
			</form>
		</div>
    </body>
</html>
