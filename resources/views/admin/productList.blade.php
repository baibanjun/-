@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="product_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group">
			    	<label>产品名称：</label>
			    	<input type="text" class="form-control" v-model="name">
			  	</div>
			  	<div class="form-group">
			    	<label>商家名称：</label>
			    	<input type="text" class="form-control" v-model="bus_name">
			  	</div>
			  	<div class="form-group">
			    	<label>城市：</label>
			    	<input type="text" class="form-control" v-model='city_name'>
			  	</div>
			  	<div class="form-group">
			    	<label>类型：</label>
			    	<select class="form-control" v-model="type">
						<option value="all">全部</option>
						<option value="1">吃喝玩乐go产品</option>
						<option value="3">联盟商城产品</option>
			    	</select>
			  	</div>
			  	<div class="form-group">
			    	<label>状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="buying">抢购中</option>
						<option value="count_down">倒计时</option>
						<option value="sold_out">已售罄</option>
						<option value="unline">已下架</option>
						<option value="hidden">已隐藏</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="to_add_product()">新增产品</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="15%">
					<col width="15%">	
					<col width="15%">
					<col width="15%">
					<col width="8%">
					<col width="12%">	
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>产品名称</th>
						<th>类型</th>
						<th>商家名称</th>
						<th>城市</th>
						<th>库存</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.name}}</td>
						<td>@{{n.type==1?'吃喝玩乐go产品':'联盟商城产品'}}</td>
						<td>@{{n.business.name}}</td>
						<td>@{{n.sys_area.name}}</td>
						<td>@{{n.onhand}}</td>
						<td>@{{n.status==3?'已隐藏':n.status==2?'已下架':n.status==4?'已售罄':n.status==5?'倒计时':'抢购中'}}</td>
						<td>
							<button class="layui-btn layui-btn-sm" @click="to_detail(index)">详情</button>
							<button class="layui-btn layui-btn-sm layui-btn-normal" @click="sold_out(n.id,index)" v-if="n.status!=2&&n.status!=3">下架</button>
							<button class="layui-btn layui-btn-sm layui-btn-normal" @click="putaway(n.id,index)"  v-if="n.status==2||n.status==3">上架</button>
							<button class="layui-btn layui-btn-sm layui-btn-danger" @click="conceal(n.id,index)" v-if="n.status!=3">隐藏</button>
							<button class="layui-btn layui-btn-sm layui-btn-danger" @click="del(n.id,index)" v-if="n.status==3">删除</button>
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
<script src="{{statics('js/admin/product_list.js')}}"></script>
@endsection