@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="order_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>提交订单时间：</label>
			    	<input type="text" class="form-control" placeholder="请选择开始时间" id="begin_time"> -
			    	<input type="text" class="form-control" placeholder="请选择结束时间" id="end_time">
			  	</div>
			  	<div class="form-group">
			    	<label>订单号：</label>
			    	<input type="text" class="form-control" v-model="sn">
			  	</div>
			  	<div class="form-group">
			    	<label>买家昵称：</label>
			    	<input type="text" class="form-control" v-model='nickname'>
			  	</div>
			  	<div class="form-group">
			    	<label>订单类型：</label>
			    	<select class="form-control" v-model="type">
						<option value="all">全部</option>
						<option value="1">吃喝玩乐go订单</option>
						<option value="3">联盟商城订单</option>
			    	</select>
			  	</div>
			  	<div class="form-group">
			    	<label>订单状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="0">未支付</option>
						<option value="1">已支付</option>
						<option value="2">已预约</option>
						<option value="3">已发货</option>
						<option value="4">已完成</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn " @click="out_list()">导出</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="10%">
					<col width="10%">	
					<col width="10%">
					<col width="10%">
					<col width="8%">
					<col width="8%">	
					<col width="8%">
					<col width="8%">	
					<col width="10%">
					<col width="10%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr>
						<th>订单号</th>
						<th>产品名称</th>
						<th>商家名称</th>
						<th>买家昵称</th>
						<th>购买数量</th>
						<th>购买金额</th>
						<th>订单类型</th>
						<th>订单状态</th>
						<th>提交订单时间</th>
						<th>订单完成时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.sn}}</td>
						<td>@{{n.product.name}}</td>
						<td>@{{n.business.name}}</td>
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.quantity}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.type==1?'吃喝玩乐go订单':'联盟商城订单'}}</td>
						<td>@{{n.status==1?'已支付':n.status==2?'已预约':n.status==3?'已发货':n.status==4?'已完成':'未支付'}}</td>
						<td>@{{n.created_at}}</td>
						<td>@{{n.complete_time}}</td>
						<td>
							<button class="layui-btn layui-btn-sm" @click="to_go_detail(n.uid,index)" v-if="n.type==1">查看详情</button>
							<button class="layui-btn layui-btn-sm" @click="to_alliance_detail(n.uid,index)" v-if="n.type==3">查看详情</button>
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
<script src="{{statics('js/admin/order_list.js')}}"></script>
@endsection