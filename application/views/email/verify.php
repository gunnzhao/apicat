<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>ApiCat</title>

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 50px;
            }
            .starter-template {
                padding: 40px 15px;
                text-align: center;
            }
            a {
                font-size: 21px;
                font-weight: 300;
                line-height: 1.4;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="starter-template">
                <h1><?php echo $msg; ?></h1>
                <a href="/projects">返回</a>
            </div>
        </div>
    </body>
</html>
