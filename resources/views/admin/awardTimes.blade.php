@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="award_times" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card" v-if="!setting">
	         <div class="layui-card-header" style="background: #ddd">用户抽奖次数设置</div>
	         <div class="layui-card-body">
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户每天抽奖机会次数:</span>
	          		<span class="col-g layui-col-md6">@{{day_has_num}}</span>
	          	</p>	
	          	<p class="mg20 t16 clearfix" style="display:none">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户每天分享抽奖活动机会次数:</span>
	          		<span class="col-g layui-col-md6">@{{day_share_num}}</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户分享抽奖活动后获得抽奖机会次数:</span>
	          		<span class="col-g layui-col-md6">@{{share_get_num}}</span>
	          	</p>	
	          	<p class="tc mt30">
	          		<button class="layui-btn layui-btn-normal" @click="set_alert()">修改</button>
	          		<button class="layui-btn layui-btn-primary" @click="back()"><<返回</button>
	          	</p>
	         </div>
	      </div>
	      <div class="layui-card" v-if="setting">
	        	<div class="layui-card-header" style="background: #ddd">用户抽奖次数设置</div>
	        	<div class="layui-card-body layui-form layui-form-pane">
	        		<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户每天抽奖机会次数:</span>
	          		<span class="layui-col-md6">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_day_has" >
	          		</span>
	          	</p>	
	          	<p class="mg20 t16 clearfix" style="display:none">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户每天分享抽奖活动机会次数:</span>
	          		<span class="layui-col-md6">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_day_share" >
	          		</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md5">用户分享抽奖活动后获得抽奖机会次数:</span>
	          		<span class="layui-col-md6">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_day_get" >
	          		</span>
	          	</p>
	          	<p class="tc mg20">
	          		<button class="layui-btn layui-btn-normal" @click="confirm()">确定</button>
	          		<button class="layui-btn layui-btn-primary" @click="off()">取消</button>
	          	</p>
	        	</div>
	      </div>
		</div>
		
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/award_times.js')}}"></script>
@endsection