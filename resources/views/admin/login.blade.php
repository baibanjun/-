<!DOCTYPE html>
<html lang="zh-CN">
<head>
    	<meta charset="utf-8" >
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>吃喝玩乐成都联盟后台管理系统 </title>
		<meta name="renderer" content="webkit" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="format-detection" content="telephone=no" />
		<link href="{{statics('css/admin/common.css')}}" rel="stylesheet">
		<link href="{{statics('css/admin/global.css')}}" rel="stylesheet">
		<link href="{{statics('css/admin/admin.css')}}" rel="stylesheet">
		<link href="{{statics('css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{statics('js/layui/css/layui.css')}}" rel="stylesheet">	
</head>
<body>	
	<div class="layui-layout layui-layout-admin">
      <div class="layui-header header header-demo">
          <div class="layui-main">
              <span class="logo b">吃喝玩乐成都联盟后台登录</span>             
          </div>
      </div>           
   </div>
   <div class="container-fluid" id="login">
		<div class="login_box">
			<div class="layui-tab layui-tab-card">
				<ul class="layui-tab-title">
				 	<li>登录</li>
				</ul>
				<div class="layui-tab-content form-horizontal">
					<div class="form-group mg20">
						<label class="col-md-3 control-label"><i class="layui-icon layui-icon-cellphone" style="font-size: 25px;"></i></label>
	              	<div class="col-md-7">
	                	<input placeholder="请输入登录帐号" class="layui-input" type="text" v-model="user" @keyup="check()" @blur="check()">
	              	</div>
	            </div>
	            <div class="form-group mg20">
						<label class="col-md-3 control-label"><i class="layui-icon layui-icon-password" style="font-size: 25px;"></i></label>
	              	<div class="col-md-7">
	                	<input placeholder="请输入密码" class="layui-input" type="password" v-model="password" @keyup="check()" @blur="check()">
	              	</div>
	            </div>
				 	<div class="layui-form-item tc mt30">
				 		<button class="layui-btn layui-btn-lg layui-btn-disabled" style="padding: 0 120px" v-show="!pass">确认登录</button>
				 		<button class="layui-btn layui-btn-lg layui-btn-normal" style="padding: 0 120px" @click="login()" v-show="pass">确认登录</button>		
				 	</div>
				</div>
			</div>
		</div>
	</div>
	<script>
        var WEB_CONFIG = {
            API_URL: '{{env('ADMIN_API_URL')}}',
            WEB_URL: '{{url('/web_admin')}}/'
        };
   </script>
   <script src="{{statics('js/jquery.js')}}"></script>
   <script src="{{statics('js/vue.js')}}"></script>
	<script src="{{statics('js/layui/layui.all.js')}}"></script>
	<script src="{{statics('js/jsencrypt.min.js')}}"></script>
	<script src="{{statics('js/admin/base.js')}}"></script>
	<script src="{{statics('js/admin/login.js')}}"></script>
	<script src="{{statics('js/jquery.cookie.js')}}"></script>
</body>
</html>

