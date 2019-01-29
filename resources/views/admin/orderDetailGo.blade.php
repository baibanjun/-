@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="order_detail_go" v-cloak>
		<p class=""><button class="layui-btn layui-btn-primary" @click="to_list()"><<返回</button></p>
		<p class="pl30 mg20 t22 b col-g">go订单详情</p>
		<hr style="width: 70%">
		<p class="pl30 mg20 clearfix" style="width: 70%">
			<span class="layui-col-md3 mg20 t16"><span class="col-b">订单号：</span>@{{go_info.sn}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">产品名称：</span> @{{go_info.product.name}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">商家名称：</span> @{{go_info.business.name}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">购买数量：</span> @{{go_info.quantity}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">购买金额：</span> @{{go_info.money}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">买家昵称：</span> @{{go_info.user.nickname}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">用户姓名：</span> @{{go_info.name}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">联系电话：</span> @{{go_info.tel}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">备注：</span> @{{go_info.remark}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">订单状态：</span> @{{go_info.status==1?'已支付':go_info.status==2?'已预约':go_info.status==3?'已发货':go_info.status==4?'已完成':'未支付'}}</span>
		</p>
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/order_detail_go.js')}}"></script>
@endsection