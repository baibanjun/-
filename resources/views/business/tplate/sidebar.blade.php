<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
		<div class="am-offcanvas-bar admin-offcanvas-bar">
		  <ul class="am-list admin-sidebar-list">
			<li class="@if($_SERVER['REQUEST_URI']=='/web_business') bgk-primary @endif"><a href="/web_business"><!-- <span class="am-icon-home"></span> --> 核销订单</a></li>
			<li class="@if($_SERVER['REQUEST_URI']=='/web_business/coupons') bgk-primary @endif"><a href="/web_business/coupons"><!-- <span class="am-icon-home"></span> --> 优惠券核销</a></li>
			<li class="@if(REQUEST_URI($_SERVER['REQUEST_URI'],['/web_business/couponsManage','/web_business/couponsDetails'])) bgk-primary @endif"><a href="/web_business/couponsManage"><!-- <span class="am-icon-home"></span> --> 优惠券管理</a></li>
			<li class="@if($_SERVER['REQUEST_URI']=='/web_business/record') bgk-primary @endif"><a href="/web_business/record"><!-- <span class="am-icon-table"></span> --> 核销记录</a></li>
			<li class="@if($_SERVER['REQUEST_URI']=='/web_business/verification') bgk-primary @endif"><a href="/web_business/verification"><!-- <span class="am-icon-pencil-square-o"></span> --> 未核销订单</a></li>
			<li class="@if(REQUEST_URI($_SERVER['REQUEST_URI'],['/web_business/order','/web_business/orderDetails'])) bgk-primary @endif"><a href="/web_business/order"><!-- <span class="am-icon-sign-out"></span> --> 联盟商城订单管理</a></li>
		  </ul>
		</div>
	</div>
	{{ $slot }}