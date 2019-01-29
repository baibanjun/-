<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>核销系统</title>
		<meta name="description" content="核销系统">
		<meta name="keywords" content="index">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" /><!-- Viewport可以加速页面的渲染 -->
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<!-- <link rel="icon" type="image/png" href="/i/favicon.png"> -->
		<meta content="yes" name="apple-mobile-web-app-capable"><!--IOS中safari允许全屏浏览-->
		<meta content="black" name="apple-mobile-web-app-status-bar-style"><!--IOS中Safari顶端状态条样式-->
		<meta content="telephone=no" name="format-detection"><!-- 忽略将数字变为电话号码 -->
		<meta content="email=no" name="format-detection"><!-- 忽略识别email -->
		
		
		<link rel="stylesheet" type="text/css" href="/static/business/css/amazeui.min.css">
		<link rel="stylesheet" type="text/css" href="/static/business/css/main.css">
		<script type="text/javascript" src="/static/business/js/global/sea.js"></script>
		<!-- <script src="http://192.168.20.34:8081/target/target-script-min.js#anonymous"></script> -->
		@yield('css')
		<script type="text/javascript">
			var app_config = {
				BUS_API_URL: '{{env('BUS_API_URL')}}',
				APP_ID: '{{env('APPID')}}',
				WEB_URL: '{{url('/')}}',
				VERSION:'{{env('STATIC_V')}}',
			};
		</script>
	</head>
	<body>
		<!--[if lte IE 9]>
		<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
		  以获得更好的体验！</p>
		<![endif]-->
		@yield('header')
		
		@yield('content')
		
		<!--[if lte IE 8 ]>
		<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
		<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
		<![endif]-->
		<script type="text/javascript" src="/static/business/js/global/jquery.min.js"></script>
		<script type="text/javascript" src="/static/business/js/global/amazeui.min.js"></script>
		<script type="text/javascript" src="/static/business/js/global/layer-v3.1.1/layer/layer.js"></script>
		<script type="text/javascript" src="/static/business/js/global/layDate-v5.0.9/laydate/laydate.js"></script>
		<script type="text/javascript" src="/static/js/jsencrypt.min.js"></script>
		<script type="text/javascript" src="/static/business/js/global/vue.js"></script>
		<script type="text/javascript" src="/static/business/js/global/md5.js"></script>
		<script type="text/javascript" src="/static/business/js/global/sha256.js"></script>
		<script type="text/javascript">
			seajs.config({
				alias:{
					'base':'/static/business/js/global/base.js',
					'page':'/static/business/js/page.js',
					'jweixin':'https://res2.wx.qq.com/open/js/jweixin-1.4.0.js',
					'sha1':'/static/business/js/global/sha1.js',
				},
				map: [
					//可配置版本号
					['.css', '.css?v=' + app_config.VERSION],
					['.js', '.js?v=' + app_config.VERSION]
				],
				//编码
				charset: 'utf-8'
		
			});
		</script>
		
		@yield('javascript')
	</body>
</html>