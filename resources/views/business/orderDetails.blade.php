@extends('business/tplate.main')

@section('css')

@endsection

@section('content')
	<!-- header start -->
	@component('business.tplate.header')

	@endcomponent
	<!-- header end -->
<div class="am-cf admin-main">
	<!-- sidebar start -->
	@component('business.tplate.sidebar')

	@endcomponent
	<!-- sidebar end -->

	<!-- content start -->
	<div class="admin-content" id="app" v-cloak>
		<div class="admin-content-body">
			<div class="am-cf am-padding am-padding-bottom-0">
				<div class="am-fl am-cf">
					<strong class="am-text-primary am-text-lg">订单详情</strong>
					<!-- / <small>Table</small> -->
				</div>
			</div>
			<hr>
			<div class="am-g">
				<div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
					<h4>订单信息</h4>
					<hr/>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">订单号：@{{gridData.sn}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">产品名称：@{{gridData.product&&gridData.product.name}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">商家名称：@{{gridData.business.name}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">购买数量：@{{gridData.quantity}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">购买金额：@{{gridData.money}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">买家微信号：@{{gridData.user.nickname}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">用户姓名：@{{gridData.name}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">联系电话：@{{gridData.tel}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">所在地区：@{{gridData.area_value | address}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">详细地址：@{{gridData.address}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">备注：@{{gridData.remark}}</div>
					<div class="am-u-sm-12 am-u-md-6 am-u-lg-6 am-margin-top">订单状态：@{{gridData.status|statusText}}</div>
				</div>
				<div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-top">
					<h4>物流信息</h4>
					<hr/>
					<div class="am-form am-form-horizontal" v-if="isDelivery">
						<div class="am-form-group">
							<div class="am-cf am-u-sm-6 am-u-sm-centered am-margin-top">
								<input class="am-u-sm-12" v-model="courierNumber" type="text" placeholder="请输入快递单号">
							</div>
							<div class="am-cf am-u-sm-6 am-u-sm-centered am-margin-top">
								<input class="am-u-sm-12" v-model="courierType" type="text" placeholder="请输入配送方式(如:EMS,顺丰快递)">
							</div>
							<div class="am-cf am-u-sm-2 am-u-sm-centered am-margin-top">
								<button class="am-btn am-btn-primary" @click="clickConfirm">确认已发送</button>
							</div>
						</div>
					</div>
					<div class="am-cf" v-if="isDeliveryDetals">
						<div class="am-u-lg-12 am-u-sm-centered">
							快递单号：@{{gridData.express_number}}
						</div>
						<div class="am-u-lg-12 am-u-sm-centered am-margin-top">
							快递公司：@{{gridData.express_company}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- content end -->
</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

@endsection

@section('javascript')
<script type="text/javascript">
	seajs.use(['/static/business/js/orderDetails']);
</script>
@endsection