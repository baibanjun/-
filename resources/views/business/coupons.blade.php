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
					优惠券核销
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
        <li class="am-active"><a href="#tab1">劵码核销</a></li>
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
							搜索劵码
						</div>
						<small class="am-link-muted">
							请顾客出示优惠券，输入劵码
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
							根据搜索出的优惠券信息，商家自行核对验证
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
							验证完成后可在‘优惠券管理’里更新优惠券数据
						</small>
					</div>
				</div>
			</div>
			<div id="tab1Order" v-show="tab1Order" class="am-margin-top">
				<div class="am-u-sm-centered am-u-md-8 am-u-lg-6 index-order">
					<div class="am-margin-top">
						劵码：@{{gridData.code}}
					</div>
					<hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
					<div class="am-margin-top">
						备注：@{{gridData.prize && gridData.prize.description}}
					</div>
					<div class="am-margin-top">
						使用条件：@{{gridData.prize && gridData.prize.use_condition}}
					</div>
					
					<div class="am-margin-top am-cf am-g">
						<div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-g">
							状态：
							<span :class="{'am-text-success':gridData.status==1 || gridData.status==2,'am-text-danger':true}">
								@{{gridData.status==0?'未使用':gridData.status==1?'已使用':''}}
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
	seajs.use(['/static/business/js/coupons']);
</script>
@endsection