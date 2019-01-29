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
					<strong class="am-text-primary am-text-lg">核销记录</strong>
					<!-- / <small>Table</small> -->
				</div>
			</div>
			<hr>
			<div class="am-g am-form">
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="name" class="am-input-sm" placeholder="用户姓名">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="tel" class="am-input-sm" placeholder="联系电话">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="code" class="am-input-sm" placeholder="电子码">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="sn" class="am-input-sm" placeholder="订单号">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<button class="am-btn am-btn-primary am-btn-sm am-fr" @click="getList(1)" type="button">搜索</button>
				</div>
			</div>
			<div class="am-g">
				<div class="am-u-sm-12">
					<div class="am-scrollable-horizontal">
						<table class="am-table am-table-striped am-text-nowrap am-table-hover">
							<thead>
								<tr>
									<th class="">电子码</th>
									<th class="">订单号</th>
									<th style="width:200px;" class="">产品名称</th>
									<th class="">数量</th>
									<th class="">金额</th>
									<th class="">用户姓名</th>
									<th class="">联系电话</th>
									<th class="">购买时间</th>
									<th class="am-hide-sm-only">验证时间</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="item in gridData">
									<td>@{{item.code}}</td>
									<td>@{{item.sn}}</td>
									<td>@{{item.product.name}}</td>
									<td>@{{item.quantity}}</td>
									<td>@{{item.money}}</td>
									<td>@{{item.name}}</td>
									<td>@{{item.tel}}</td>
									<td>@{{item.pay_time}}</td>
									<td class="am-hide-sm-only">@{{item.verification_time}}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="am-cf"> 
						<pagination @getlist="getList" :is-page="isPage" :cur-page="curPage" :show-pages="showPages" :total-pages="totalPages" ref="pagination"></pagination>
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
	seajs.use(['/static/business/js/record']);
</script>
@endsection