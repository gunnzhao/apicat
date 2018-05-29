<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/static/img/favicon.ico">
        <title>项目邀请</title>
        <!-- Bootstrap core CSS -->
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/layout.css?v=<?php echo microtime(true); ?>" rel="stylesheet">
        <style>
            html {
                position: relative;
                min-height: 100%;
            }
            body {
            /* Margin bottom by footer height */
                margin-bottom: 60px;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                /* Set the fixed height of the footer here */
                height: 30px;
            }
            .container {
                width: auto;
                max-width: 680px;
                padding: 0 15px;
            }
        </style>
    </head>

    <body>

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
        <h1>项目邀请</h1>
        </div>
        <p class="lead"><?php echo $main_text; ?></p>
        <form action="/invite/accept?invite_code=<?php echo $invite_code; ?>" class="form-horizontal" method="POST">
            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-sm-2 control-label">登录密码</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" placeholder="密码">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                    <button type="submit" class="btn btn-lblue">确认加入</button>
                </div>
            </div>
        </form>
    </div>

    <footer class="footer">
        <p class="text-center">&copy; ApiCat</p>
    </footer>
    </body>
</html>
