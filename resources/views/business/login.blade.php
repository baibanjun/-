@extends('business/tplate.main')

@section('css')

@endsection

@section('content')

<div id="login_box" v-cloak>
<div class="am-g">
  <div class="am-u-lg-4 am-u-md-7 am-u-sm-centered">
	<br>
	<br>
	<br>
	<br>
    <div class="am-form">
      <label for="account">用户名:</label>
		<input type="text" placeholder="请输入用户名/手机号码" id="account" v-model="user" value="">
      <br>
      <label for="password">密码:</label>
		<input type="password" placeholder="请输入登录密码" id="password" v-model="pwd" value="23dsdssd">
      <br>
	  <div class="am-cf">
		<a href="find_pwd" class="am-btn am-btn-default am-btn-sm am-fr">忘记密码?</a>
	  </div>
	  <br>
      <div class="am-cf">
		<input type="submit" name="" @click="loginClick" value="登 录" class="am-btn am-btn-secondary am-btn-block">
      </div>
    </div>
  </div>
</div>


</div>

@endsection

@section('javascript')
<script type="text/javascript">
	seajs.use(["/static/business/js/login"]);
</script>
@endsection