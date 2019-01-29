@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="document_alert" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card">
	        <div class="layui-card-header" style="background: #ddd">分享引导文案<span class="t12 col-g">(用户扫描分享海报的二维码后，顶部的引导关注文字)</span></div>
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
<script src="{{statics('js/admin/document_alert.js')}}"></script>
@endsection