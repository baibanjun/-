@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="order_detail_alliance" v-cloak>
		<p class=""><button class="layui-btn layui-btn-primary" @click="to_list()"><<返回</button></p>
		<p class="pl30 mg20 t22 b col-g">订单详情</p>
		<hr style="width: 70%">
		<p class="pl30 mg20 clearfix" style="width: 70%">
			<span class="layui-col-md3 mg20 t16"><span class="col-b">订单号：&nbsp;</span>@{{alliance_info.sn}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">产品名称：&nbsp;</span> @{{alliance_info.product.name}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">商家名称：&nbsp;</span> @{{alliance_info.business.name}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">购买数量：&nbsp;</span> @{{alliance_info.quantity}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">购买金额：&nbsp;</span> @{{alliance_info.money}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">买家昵称：&nbsp;</span> @{{alliance_info.user.nickname}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">用户姓名：&nbsp;</span> @{{alliance_info.name}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">联系电话：&nbsp;</span> @{{alliance_info.tel}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">所在地区：&nbsp;</span> @{{alliance_info.area_value.length==0?'':(alliance_info.area_value[0].name+alliance_info.area_value[1].name+alliance_info.area_value[2].name)}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">详细地址：&nbsp;</span> @{{alliance_info.address}}</span>
			<span class="layui-col-md3 mg20 t16"><span class="col-b">备注：&nbsp;</span> @{{alliance_info.remark}}</span>
			<span class="layui-col-md9 mg20 t16"><span class="col-b">订单状态：&nbsp;</span> @{{alliance_info.status==1?'已支付':alliance_info.status==2?'已预约':alliance_info.status==3?'已发货':alliance_info.status==4?'已完成':'未支付'}}</span>
		</p>
		<p class="pl30 mg20 t22 b col-g">订单物流详情</p>
		<hr style="width: 70%">
		<p class="pl30 mg20 clearfix" style="width: 70%">
			<span class="layui-col-md6 mg20 t16"><span class="col-b">快递单号：</span>@{{alliance_info.express_company}}&nbsp;&nbsp;@{{alliance_info.express_number}}</span>			
		</p>
	</div>
</div>

	
@endsection

@section('javascript')
<script src="{{statics('js/admin/order_detail_alliance.js')}}"></script>
@endsection