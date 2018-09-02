<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <link rel="icon" href="/static/img/favicon.ico">

    <title>ApiCat-创建Markdown文档</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/font-awesome.min.css">
    <link href="/static/css/layout.css?v=<?php echo microtime(true); ?>" rel="stylesheet">

    <link href="/static/css/simplemde.min.css?v=<?php echo microtime(true); ?>" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/highlight/default.css?v=<?php echo microtime(true); ?>">
            
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    html, body {
        height: 100%;
        margin: 0px;
        padding: 20px 0px 100px 0px;
    }
    .main-container, .row, .CodeMirror {
        height: 100%;
    }
    </style>
    </head>

    <body>
        <div class="container main-container">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="javascript:history.back();">返回</a></li>
                </ol>
                <textarea id="markdown-editor" class="form-control"></textarea>
                <p class="text-center"><button type="button" id="create" class="btn btn-lblue" style="width:150px;">创建</button></p>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="https://v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>

        <script src="/static/js/simplemde.min.js?v=<?php echo microtime(true); ?>"></script>
        <script src="/static/js/highlight.pack.js?v=<?php echo microtime(true); ?>"></script>
        <script src="/static/js/markdown-textarea.js?v=<?php echo microtime(true); ?>"></script>
    </body>
</html>