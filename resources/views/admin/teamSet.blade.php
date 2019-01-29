@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="team_set" v-cloak>
		<div class="mg20 pl30" style="width: 50%">
			<div class="layui-card" v-if="!setting">
	        <div class="layui-card-header" style="background: #ddd">组建团队条件设置</div>
	        <div class="layui-card-body">
	          <p class="tc mg20 t16">团队人数不少于<span class="col-r">@{{team_number}}</span>人</p>
	          <p class="tc mg20 t16">团队中卖出产品人数不少于<span class="col-r">@{{sale_team_number}}</span>人</p>	
	          <p class="tc mg20"><button class="layui-btn layui-btn-normal" @click="set_condition()">修改条件</button></p>
	        </div>
	      </div>
	      <div class="layui-card" v-if="setting">
	        	<div class="layui-card-header" style="background: #ddd">组建团队条件设置</div>
	        	<div class="layui-card-body layui-form layui-form-pane">
	          	<p class="tc mg20 t16">
					   团队人数不少于&nbsp;&nbsp;
					   <input type="text" class="layui-input" style="display: inline-block; width:100px;" v-model.trim="set_team_number" onkeyup="base.IntNum(this)">&nbsp;&nbsp;人
					</p>
					<p class="tc mg20 t16">
					   团队中卖出产品人数不少于&nbsp;&nbsp;
					   <input type="text" class="layui-input" style="display: inline-block; width:100px;" v-model.trim="set_sale_number" onkeyup="base.IntNum(this)">&nbsp;&nbsp;人
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
<script src="{{statics('js/admin/team_set.js')}}"></script>
@endsection