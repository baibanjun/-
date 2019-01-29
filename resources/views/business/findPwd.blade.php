@extends('business/tplate.main')

@section('css')

@endsection

@section('content')

<div id="findPwd" v-cloak>
<div class="am-g">
  <div class="am-u-lg-4 am-u-md-7 am-u-sm-centered">
	<br>
	<br>
	<br>
	<br>
    <div class="am-form">
		<input type="text" name="mobile" v-model="mobile" placeholder="请输入绑定的手机号码" id="mobile" value="">
		<br>
		<div class="am-g doc-am-g">
			<div class="am-u-sm-6 am-u-md-6 am-u-lg-7">
				<input class="am-u-sm-12 am-u-md-12 am-u-lg-12" v-model="code" type="text" name="code" placeholder="请输入验证码" id="code" value="">
			</div>
			<div class="am-u-sm-6 am-u-md-6 am-u-lg-5">
				<input class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-btn am-btn-primary am-text-truncate" id="sendBtn" @click="getCode" type="button" name="" value="获取短信验证码">
			</div>
		</div>
		<br>
		<div class="am-cf">
			<a href="javascript:void(0)" @click="findNext" name="" class="am-btn am-btn-secondary am-btn-block">提  交</a>
		</div>
    </div>
  </div>
</div>


</div>

@endsection

@section('javascript')
<script type="text/javascript">
	seajs.use(["/static/business/js/findPwd"]);
</script>
@endsection