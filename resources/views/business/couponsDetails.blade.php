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
			<div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom-sm    ">
			    <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
			        抽奖标题:@{{orderDetails_sd.title}}
			    </div>
               <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
                    创建日期:@{{orderDetails_sd.created_at}}
                </div>
                <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
                    最近修改日期:@{{orderDetails_sd.updated_at}}
                </div>
            </div>
            <hr class="am-margin-sm">
			<div class="am-g am-form">
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<div class="am-form-group">
						<div class="am-form-group">
							<input type="text" v-model="name" class="am-input-sm" placeholder="优惠券名称">
						</div>
					</div>
				</div>
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-2">
					<a class="am-btn am-btn-primary am-btn-sm am-fr" href="/web_business/couponsManage" type="button">< 返回</a>
					<button class="am-btn am-btn-primary am-btn-sm am-fr am-margin-right-sm" @click="getList(1)" type="button">搜索</button>
				</div>
			</div>
			<div class="am-g">
				<div class="am-u-sm-12">
					<div class="am-scrollable-horizontal">
						<table class="am-table am-table-striped am-text-nowrap am-table-hover">
							<thead>
								<tr>
									<th>优惠券名称</th>
									<th>未使用数量</th>
									<th>已使用数量</th>
									<th>已过期数量</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="item in gridData">
									<td>@{{item.name}}</td>
									<td>@{{item.inventory}}</td>
									<td>@{{item.has_send_num}}</td>
									<td>@{{item.expired}}</td>
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
	seajs.use(['/static/business/js/couponsDetails']);
</script>
@endsection