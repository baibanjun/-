@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="invite_set" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card" v-if="!setting">
	        <div class="layui-card-header" style="background: #ddd">邀请关注奖励设置</div>
	        <div class="layui-card-body">
	          <p class="tc mg20 t16">邀请关注公众号奖励金额:<span class="col-r">￥@{{money}}</span></p>	
	          <p class="tc mg20"><button class="layui-btn layui-btn-normal" @click="set_money()">修改奖励</button></p>
	        </div>
	      </div>
	      <div class="layui-card" v-if="setting">
	        	<div class="layui-card-header" style="background: #ddd">邀请关注奖励设置</div>
	        	<div class="layui-card-body layui-form layui-form-pane">
	          	<p class="tc mg20 t16">
					   邀请关注公众号奖励金额:&nbsp;&nbsp;
					   <input type="text" class="layui-input" style="display: inline-block; width:100px;" v-model.trim="set_money_num" onkeyup="base.floatNum(this)"  onblur="base.floatNum(this)">&nbsp;&nbsp;元
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
<script src="{{statics('js/admin/invite_set.js')}}"></script>
@endsection