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
      <span id="header">
	      <div class="layui-header header header-demo">
	          <div class="layui-main">
	              <a class="logo b" href="/web_admin">吃喝玩乐成都联盟后台管理系统</a>
	              <ul class="layui-nav">                        
	                  <li class="layui-nav-item ">
	                      <span class="col-b"><i class="layui-icon layui-icon-username"></i>@{{user_name}}</span>
	                  </li>
	                  <li class="layui-nav-item cur">
	                      <span @click="out()">退出</span>
	                  </li> 
	              </ul>
	          </div>
	      </div>
	      <div class="layui-side">
	          <div class="layui-side-scroll layui-bg-black">
	              <ul class="layui-nav layui-nav-tree" lay-filter="test">
	                  <li class="layui-nav-item layui-nav-itemed">                      
	                     <a href="/web_admin" :class="now_id==0?'layui-this':''" @click="memu_this(0)">首页</a>
	                  </li>
	                  <li class="layui-nav-item" :class="open==1?'layui-nav-itemed':''">                      
	                     <a href="javascript:;">用户管理</a>                                             
								<dl class="layui-nav-child">
								  	<dd>
								      <a href="/web_admin/userBills" :class="now_id==1?'layui-this':''" @click="memu_this(1)">用户账号管理</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/expertsList" :class="now_id==2?'layui-this':''" @click="memu_this(2)">达人管理</a>
								  	</dd>                         
								  	<dd>
								      <a href="/web_admin/teamList" :class="now_id==3?'layui-this':''" @click="memu_this(3)">组建团队管理</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/orderList" :class="now_id==4?'layui-this':''" @click="memu_this(4)">订单管理</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/dealerList" :class="now_id==5?'layui-this':''" @click="memu_this(5)">分销管理</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/withdrawList" :class="now_id==6?'layui-this':''" @click="memu_this(6)">提现管理</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/withdrawRecord" :class="now_id==7?'layui-this':''" @click="memu_this(7)">提现申请记录</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/businessApply" :class="now_id==8?'layui-this':''" @click="memu_this(8)">商家入驻申请</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/businessApplyRecord" :class="now_id==9?'layui-this':''" @click="memu_this(9)">商家申请记录</a>
								  	</dd>
								  	<dd>
								      <a href="/web_admin/discountsList" :class="now_id==10?'layui-this':''" @click="memu_this(10)">用户优惠券管理</a>
								  	</dd>
								</dl>
	                  </li>
	                  <li class="layui-nav-item" :class="open==2?'layui-nav-itemed':''">                      
	                     <a href="javascript:;">联盟平台管理</a>                                             
	                     <dl class="layui-nav-child">
	                     	<dd>
	                           <a href="/web_admin/productList" :class="now_id==11?'layui-this':''" @click="memu_this(11)">产品管理</a>
	                        </dd>
	                        <dd>
	                           <a href="/web_admin/awardList" :class="now_id==12?'layui-this':''" @click="memu_this(12)">抽奖管理</a>
	                        </dd>
	                        <dd>
	                           <a href="/web_admin/teamSet" :class="now_id==13?'layui-this':''" @click="memu_this(13)">组建团队设置</a>
	                        </dd>
	                        <dd>
	                           <a href="/web_admin/WXgroupSet" :class="now_id==14?'layui-this':''" @click="memu_this(14)">福利群设置</a>
	                        </dd>
	                        <dd>
	                           <a href="/web_admin/inviteSet" :class="now_id==15?'layui-this':''" @click="memu_this(15)">邀请关注奖励设置</a>
	                        </dd>                         
	                        <dd>
	                           <a href="/web_admin/inviteList" :class="now_id==16?'layui-this':''" @click="memu_this(16)">邀请关注列表</a>
	                        </dd>
	                     </dl>
	                  </li>
	                  <li class="layui-nav-item"  :class="open==3?'layui-nav-itemed':''">                      
	                     <a href="javascript:;">商家管理</a>                                             
	                     <dl class="layui-nav-child">
                           <dd>
                              <a href="/web_admin/businessList" :class="now_id==17?'layui-this':''" @click="memu_this(17)">商家管理</a>
                           </dd>                         
	                     </dl>
	                  </li>
	                  <li class="layui-nav-item"  :class="open==4?'layui-nav-itemed':''">                      
	                     <a href="javascript:;">文案管理</a>                                             
	                     <dl class="layui-nav-child">
                           <dd>
                              <a href="/web_admin/documentAlert" :class="now_id==18?'layui-this':''" @click="memu_this(18)">分享引导文案</a>
                           </dd>                         
	                     </dl>
	                  </li>
	              </ul>
	          </div>
	      </div>
      </span>

		<div class="content_right">
         @yield('content')
      </div>           
   </div>

   
     	<script type="text/javascript">			
        var WEB_CONFIG = {
            API_URL: '{{env("ADMIN_API_URL")}}',
            WEB_URL: '{{url("/web_admin")}}/',
            PIC_URL: '{{env("ADMIN_PIC_URL")}}'
        };
     	</script>
   <script src="{{statics('js/jquery.js')}}"></script>
   <script src="{{statics('js/vue.js')}}"></script>	
	<script src="{{statics('js/jsencrypt.min.js')}}"></script>
	<script src="{{statics('js/admin/base.js')}}"></script>
	<script src="{{statics('js/jquery.cookie.js')}}"></script>
	<script src="{{statics('js/admin/header.js')}}"></script>
	<script src="{{statics('js/layui/layui.all.js')}}"></script>
    @yield('javascript')

</body>
</html>

