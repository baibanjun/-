@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="business_alert" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card" v-if="!setting">
	         <div class="layui-card-header" style="background: #ddd">商家入驻申请结果提醒设置</div>
	         <div class="layui-card-body">
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3 r">通过用户提醒字段:</span>
	          		<span class="col-g layui-col-md7">@{{pass_attention}}</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3 r">审核中提醒字段:</span>
	          		<span class="col-g layui-col-md7">@{{wait_attention}}</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3 r">驳回用户提醒字段:</span>
	          		<span class="col-g layui-col-md7">@{{return_attention}}</span>
	          	</p>	
	          	<p class="tc mt30">
	          		<button class="layui-btn layui-btn-normal" @click="set_alert()">修改</button>
	          		<button class="layui-btn layui-btn-primary" @click="back()"><<返回</button>
	          	</p>
	         </div>
	      </div>
	      <div class="layui-card" v-if="setting">
	        	<div class="layui-card-header" style="background: #ddd">商家入驻申请结果提醒设置</div>
	        	<div class="layui-card-body layui-form layui-form-pane">
	        		<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3">通过用户提醒字段:</span>
	          		<span class="layui-col-md7">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_pass_attention" >
	          		</span>
	          	</p>	
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3">审核中提醒字段:</span>
	          		<span class="layui-col-md7">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_wait_attention" >
	          		</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset1 layui-col-md3">驳回用户提醒字段:</span>
	          		<span class="layui-col-md7">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_return_attention" >
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
<script src="{{statics('js/admin/business_alert.js')}}"></script>
@endsection