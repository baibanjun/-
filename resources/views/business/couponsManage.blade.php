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
					<strong class="am-text-primary am-text-lg">优惠券管理</strong>
					<!-- / <small>Table</small> -->
				</div>
			</div>
			<hr>
			<div class="am-g am-form">
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" id="creatTime" class="am-input-sm" placeholder="创建日期">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" id="editTime" class="am-input-sm" placeholder="最近修改日期">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="title" class="am-input-sm" placeholder="抽奖标题">
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
									<th>抽奖标题</th>
									<th>创建日期</th>
									<th>最近修改日期</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="item in gridData">
									<td>@{{item.title}}</td>
									<td>@{{item.created_at}}</td>
									<td>@{{item.updated_at}}</td>
									<td><button class="am-btn am-btn-primary am-btn-xs" @click="orderDetails(item)">查看详情</button></td>
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
	seajs.use(['/static/business/js/couponsManage']);
</script>
@endsection