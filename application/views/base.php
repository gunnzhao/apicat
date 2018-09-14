<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="<?php echo $_page_description; ?>">
    <link rel="icon" href="/static/img/favicon.ico">

    <title><?php echo $_page_title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/font-awesome.min.css">

    <link href="/static/css/layout.css?v=<?php echo microtime(true); ?>" rel="stylesheet">

    <?php if (!empty($_page_css_file)): ?>
        <?php foreach ($_page_css_file as $_pc): ?>
        <link href="<?php echo $_pc; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($_page_css)): ?>
        <?php foreach ($_page_css as $code): ?>
        <style>
        <?php echo $code; ?>
        </style>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body>
        <!-- Static navbar -->
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/projects"><img src="/static/img/logo.png" class="logo"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <?php foreach ($_page_navigator as $v): ?>
                        <li <?php if ($v['active']) {echo 'class="active"';} ?>><a href="<?php echo $v['url']; ?>"><?php echo $v['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION['uid'])): ?>
                        <li><a href="../navbar/"<span class="icon-bell" aria-hidden="true"></span></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="<?php echo $_page_avatar; ?>" alt="<?php echo $_page_nickname; ?>" class="img-circle nav-avatar">
                                <?php echo $_page_nickname; ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/settings/profile">设置</a></li>
                                <li><a href="/logout">退出</a></li>
                            </ul>
                        </li>
                        <?php else: ?>
                        <li><a href="/login">登录</a></li>
                        <?php endif; ?>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container main-container">
        <?php echo $content_for_layout;?>
        </div>

        <footer>
            <p class="text-center">&copy; ApiCat Beta</p>
        </footer>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="https://v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>

        <?php if (!empty($_page_js_file)): ?>
            <?php foreach ($_page_js_file as $_pj): ?>
            <script src="<?php echo $_pj; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($_page_js)): ?>
            <?php foreach ($_page_js as $code): ?>
            <script type="text/javascript">
            <?php echo $code; ?>
            </script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>
