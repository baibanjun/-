@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="award_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
			  	<div class="form-group pl30">
			    	<label>抽奖标题：</label>
			    	<input type="text" class="form-control" v-model="title">
			  	</div>
			  	<div class="form-group">
			    	<label>商家名称：</label>
			    	<input type="text" class="form-control" v-model='business_name'>
			  	</div>
			  	<div class="form-group">
			    	<label>类型：</label>
			    	<select class="form-control" v-model="lottery_type">
						<option value="all">全部</option>
						<option value="1">九宫格抽奖</option>
						<option value="2">圆盘抽奖</option>
			    	</select>
			  	</div>
			  	<div class="form-group">
			    	<label>状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="1">已隐藏</option>
						<option value="2">正常</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="to_add()">新增抽奖+</button>
					<button class="layui-btn layui-btn-normal" @click="to_set()">抽奖次数设置</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="20%">
					<col width="20%">	
					<col width="10%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>抽奖标题</th>
						<th>商家名称</th>
						<th>奖品剩余总数量</th>
						<th>类型</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.title}}</td>
						<td>@{{n.business.name}}</td>
						<td>@{{n.surplus_number}}</td>
						<td>@{{n.lottery_type==1?'九宫格抽奖':'圆盘抽奖'}}</td>
						<td>@{{n.status==1?'已隐藏':'正常'}}</td>
						<td>
							<button class="layui-btn layui-btn-sm" @click="to_detail(n.id,index)" >详情</button>
							<button class="layui-btn layui-btn-sm layui-btn-danger" @click="conceal(n.id,index)" v-if="n.status==2">隐藏</button>
							<button class="layui-btn layui-btn-sm layui-btn-normal" @click="putaway(n.id,index)" v-if="n.status==1">重新上架</button>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/award_list.js')}}"></script>
@endsection