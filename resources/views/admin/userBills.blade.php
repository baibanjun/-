@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="user_bills" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
			  	<div class="form-group pl30">
			    	<label>昵称：</label>
			    	<input type="text" class="form-control" v-model="nickname">
			  	</div>
			  	<div class="form-group pl30">
			    	<label>角色：</label>
			    	<select class="form-control" v-model="role">
						<option value="all">全部</option>
						<option value="0">普通用户</option>
						<option value="1">达人</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
			    	<label>状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="0">正常</option>
						<option value="1">冻结</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="12%">
					<col width="14%">	
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="10%">
					<col width="10%">
					<col width="12%">
				</colgroup>
				<thead>
					<tr>
						<th>昵称</th>
						<th>已完成订单数量</th>
						<th>已预约订单数量</th>
						<th>已支付订单数量</th>
						<th>未支付订单数量</th>
						<th>角色</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.nickname}}</td>
						<td>@{{n.statistic?n.statistic.completed_order_quantity:0}}</td>
						<td>@{{n.statistic?n.statistic.subscribe_order_quantity:0}}</td>
						<td>@{{n.statistic?n.statistic.paid_order_quantity+n.statistic.shipped_order_quantity:0}}</td>
						<td>@{{n.statistic?n.statistic.unpaid_order_quantity:0}}</td>
						<td>@{{n.role==0?'普通用户':'达人'}}</td>
						<td>@{{n.status==0?'正常':'冻结'}}</td>
						<td>
							<button class="layui-btn layui-btn-sm layui-btn-danger" v-if="n.status==0" @click="freeze(n.id,index)">冻结</button>
							<button class="layui-btn layui-btn-sm" v-if="n.status!=0" @click="unfreeze(n.id,index)">解冻</button>
						</td>
					</tr>
				</tbody>
			</table>
			<div id='pages' class="tc mg20"></div>
		</div>
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/user_bills.js')}}"></script>
@endsection