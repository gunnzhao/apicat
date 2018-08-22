<!DOCTYPE html>
<html lang="zh-CN">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="/static/img/favicon.ico">
		<title>404 Page Not Found</title>
		<!-- Bootstrap core CSS -->
		<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
			/*
			* Base structure
			*/
			html,
			body {
				height: 100%;
			}
			body {
				text-align: center;
			}

			/* Extra markup and styles for table-esque vertical and horizontal centering */
			.site-wrapper {
				display: table;
				width: 100%;
				height: 100%; /* For at least Firefox */
				min-height: 100%;
			}
			.site-wrapper-inner {
				display: table-cell;
				vertical-align: top;
			}
			.cover-container {
				margin-right: auto;
				margin-left: auto;
			}

			/* Padding for spacing */
			.inner {
				padding: 30px;
			}

			/*
			* Cover
			*/
			.cover {
				padding: 0 20px;
			}
			.cover .btn-lg {
				padding: 10px 20px;
				font-weight: bold;
			}

			/*
			* Affix and center
			*/
			@media (min-width: 768px) {
				/* Start the vertical centering */
				.site-wrapper-inner {
					vertical-align: middle;
				}
				/* Handle the widths */
				.cover-container {
					width: 100%; /* Must be percentage or pixels for horizontal alignment */
				}
			}

			@media (min-width: 992px) {
				.cover-container {
					width: 700px;
				}
			}
		</style>
	</head>
	<body>
		<div class="site-wrapper">
			<div class="site-wrapper-inner">
				<div class="cover-container">
					<div class="inner cover">
						<h1 class="cover-heading">404</h1>
						<p class="lead">你来到了一个无人之境</p>
						<p class="lead"><a href="javascript:history.back();" class="">返回</a></p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
