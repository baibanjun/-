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
				<strong class="am-text-primary am-text-lg">
					核销订单
				</strong>
				/
				<small>
					电子码核销
				</small>
			</div>
		</div>
    <hr>

    <div class="am-tabs am-margin" data-am-tabs>
      <ul class="am-tabs-nav am-nav am-nav-tabs">
        <li class="am-active"><a href="#tab1">电子码核销</a></li>
        <li v-if="isWei"><a href="#tab2" >扫码核销</a></li>
      </ul>

      <div class="am-tabs-bd">
        <div class="am-tab-panel am-fade am-in am-active" id="tab1">
					<div class="am-g am-margin-top">
						<div class="am-g">
							<div class="am-u-sm-centered am-u-md-8 am-u-lg-6">
								<div class="am-input-group am-input-group-primary">
									<input type="text" v-model="code" placeholder="请输入订单电子码" class="am-form-field">
									<span class="am-input-group-btn">
										<button class="am-btn am-btn-primary" @click="inputCode" type="button">
											<span class="am-icon-search">
											</span>
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div id="tab1Steps" v-show="tab1Steps">
						<div class="am-g doc-am-g am-margin-top am-u-sm-centered am-u-md-8 am-u-lg-6">
							<div class="am-u-sm-6 am-u-md-4 am-u-lg-3">
								<div class="steps am-text-center am-text-lg">
									1
								</div>
							</div>
							<div class="am-u-sm-6 am-u-md-8 am-u-lg-9">
								<div class="am-text-lg">
									搜索电子码
								</div>
								<small class="am-link-muted">
									请顾客出示订单，输入电子码
								</small>
							</div>
						</div>
						<div class="am-g doc-am-g am-margin-top am-u-sm-centered am-u-md-8 am-u-lg-6">
							<div class="am-u-sm-6 am-u-md-4 am-u-lg-3">
								<div class="steps am-text-center am-text-lg">
									2
								</div>
							</div>
							<div class="am-u-sm-6 am-u-md-8 am-u-lg-9">
								<div class="am-text-lg">
									验证
								</div>
								<small class="am-link-muted">
									根据搜索出的订单信息，商家自行核对验证
								</small>
							</div>
						</div>
						<div class="am-g doc-am-g am-margin-top am-u-sm-centered am-u-md-8 am-u-lg-6">
							<div class="am-u-sm-6 am-u-md-4 am-u-lg-3">
								<div class="steps am-text-center am-text-lg">
									3
								</div>
							</div>
							<div class="am-u-sm-6 am-u-md-8 am-u-lg-9">
								<div class="am-text-lg">
									验证完成
								</div>
								<small class="am-link-muted">
									验证完成后可在‘核销记录’里查看核销信息
								</small>
							</div>
						</div>
					</div>
					<div id="tab1Order" v-show="tab1Order" class="am-margin-top">
						<div class="am-u-sm-centered am-u-md-8 am-u-lg-6 index-order">
							<div class="am-margin-top">
								电子码：@{{gridData.code}}
							</div>
							<hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
							<div class="am-margin-top">
								订单号码：@{{gridData.sn}}
							</div>
							<div class="am-margin-top">
								产品名称：@{{gridData.product&&gridData.product.name}}
							</div>
							<div class="am-margin-top">
								数量：@{{gridData.quantity}}
							</div>
							<div class="am-margin-top am-text-xl">
								¥@{{gridData.money}}
							</div>
							<hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
							<div class="am-margin-top">
								用户姓名：@{{gridData.name}}
							</div>
							<div class="am-margin-top">
								联系电话：@{{gridData.tel}}
							</div>
							<div class="am-margin-top">
								备注：@{{gridData.remark}}
							</div>
							<div class="am-margin-top am-cf am-g">
								<div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-g">
									状态：
									<span :class="{'am-text-success':gridData.status==1 || gridData.status==2,'am-text-danger':true}">
										@{{gridData.status==0?'未支付':gridData.status==1?'已支付':gridData.status==2?'已预约':gridData.status==3?'已发货':gridData.status==4?'已完成':''}}
									</span>
								</div>
								<div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-g" v-show="verifyBtn">
									<button @click="orderVerification" type="button" class="am-btn am-btn-primary am-btn-sm am-radius">
										确定验证
									</button>
								</div>
							</div>
							<br>
						</div>
					</div>
        </div>

        <div class="am-tab-panel am-fade" id="tab2">
          <div class="am-vertical-align" style="height: 150px;">
          	<div class="am-vertical-align-middle am-u-sm-centered am-u-md-centered am-u-lg-centered am-text-center">
          		<button type="button" @click="wxScanning()" class="am-btn am-btn-primary">
          			扫描二维码
          		</button>
          	</div>
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
	seajs.use(['/static/business/js/index']);
</script>
@endsection