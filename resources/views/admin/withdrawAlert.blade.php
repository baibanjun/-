@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="withdraw_alert" v-cloak>

		<p class="mg20"><button class="layui-btn layui-btn-primary" @click="to_list()"><<返回</button></p>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card">
	        <div class="layui-card-header" style="background: #ddd">提现提醒设置</div>
	        <div class="layui-card-body">
	          <p class="tc mg20">@{{content}}</p>	
	          <p class="tc mg20"><button class="layui-btn" @click="set_alert()">修改</button></p>
	        </div>
	      </div>
		</div>
		
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/withdraw_alert.js')}}"></script>
@endsection