@extends('business/tplate.main')

@section('css')

@endsection

@section('content')

<div id="resetPwd" v-cloak>
<div class="am-g">
  <div class="am-u-lg-4 am-u-md-7 am-u-sm-centered">
	<br>
	<br>
	<br>
	<br>
    <form method="post" class="am-form">
		<div>
			用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：@{{pwdInfo.username}}
		</div>
		<br>
		<div>
			绑定手机号码：@{{pwdInfo.mobile}}
		</div>
		<br>
		<input type="password" name="pwd" v-model="pwd" placeholder="设置密码(6-16位可以包含大小写字母,数字,下划线)" id="pwd" value="">
		<br>
		<input type="password" name="agen_pwd" v-model="agen_pwd" placeholder="确认密码" id="agen_pwd" value="">
		<br>
		<div class="am-cf">
			<a href="javascript:void(0)" @click="resetPwdClick" name="" class="am-btn am-btn-secondary am-btn-block">提  交</a>
		</div>
    </form>
  </div>
</div>


</div>

@endsection

@section('javascript')
<script type="text/javascript">
	seajs.use(["/static/business/js/resetPwd.js"]);
</script>
@endsection