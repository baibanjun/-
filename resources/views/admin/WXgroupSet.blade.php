@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="wxgroup_set" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card" v-if="!setting">
	         <div class="layui-card-header" style="background: #ddd">福利群设置</div>
	         <div class="layui-card-body">
	          	<p class="mg20 t16 lh2">
	          		<span class="layui-col-md-offset2 layui-col-md3 r">福利群名称:</span>
	          		<span class="col-r layui-col-md6">@{{group_name}}</span>
	          	</p>	
	          	<p class="mg20 t16 lh2">
	          		<span class="layui-col-md-offset2 layui-col-md3 r">福利群标语:</span>
	          		<span class="col-r layui-col-md6">@{{group_title}}</span>
	          	</p>
	          	<p class="mg20 t16 lh2">
	          		<span class="layui-col-md-offset2 layui-col-md3 r">福利群二维码:</span>
	          		<span class="col-r layui-col-md6">
	          		    <img :src="WEB_CONFIG.PIC_URL+(group_qr_code&&group_qr_code.name)" width="100">
	          		</span>
	          	</p>
	          	<p class="tc mg20"><button style="margin-top: 15px;" class="layui-btn layui-btn-normal" @click="set_money()">修改</button></p>
	         </div>
	      </div>
	      <div class="layui-card" v-if="setting">
	        	<div class="layui-card-header" style="background: #ddd">福利群设置</div>
	        	<div class="layui-card-body layui-form layui-form-pane">
	        		<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset2 layui-col-md3">福利群名称:</span>
	          		<span class="layui-col-md6">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_group_name" >
	          		</span>
	          	</p>	
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset2 layui-col-md3">福利群标语:</span>
	          		<span class="layui-col-md6">
	          			<input type="text" class="layui-input" style="display: inline-block;" v-model.trim="set_group_title" >
	          		</span>
	          	</p>
	          	<p class="mg20 t16 clearfix">
	          		<span class="layui-col-md-offset2 layui-col-md3">福利群二维码:</span>
	          		<span class="layui-col-md6">
	          		        <img :src="WEB_CONFIG.PIC_URL+(set_group_qr_code&&set_group_qr_code.name)" width="100">
							<button class="layui-btn" @click="file_post()">上传二维码</button>
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
<script src="{{statics('js/admin/wxgroup_set.js')}}"></script>
@endsection