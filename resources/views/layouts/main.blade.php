<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>吃喝玩乐成都联盟</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <script type="text/javascript" src="{{statics('js/flexible.min.js')}}"></script>
    <script>
        var app_config = {
            API_URL: '{{config('console.api_url')}}',
						APP_ID: '{{env('APPID')}}',
            WEB_URL: '{{url('/')}}/',
            CUR_URL: '{{Request::path()}}',
            PIC_URL: '{{config('console.pic_url')}}',
            APP_ID: '{{config('console.appId')}}'
        };
    </script>
    <link href="{{statics('css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{statics('css/main.css')}}" rel="stylesheet">
    @yield('css')
</head>
<body style="opacity: 0;">

    @yield('content')

    <script src="{{statics('js/jquery.js')}}"></script>
    <script src="{{statics('js/jsencrypt.min.js')}}"></script>
    <script src="{{statics('js/vue.js')}}"></script>
    <script src="{{statics('js/sha1.js')}}"></script>
    <script src="{{statics('js/md5.js')}}"></script>
	<script src="{{statics('js/rotate.js')}}"></script>
	<script src="{{statics('js/layui/layui.all.js')}}"></script>
    <script src="https://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <!--<script src="https://map.qq.com/api/js?v=2.exp"></script>-->
	<script src="{{statics('js/base.js')}}"></script>
	<script src="{{statics('js/vueBase.js')}}"></script>

    @yield('javascript')

</body>
</html>
