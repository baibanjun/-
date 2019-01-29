@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="dealer_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>购买时间：</label>
			    	<input type="text" class="form-control" placeholder="请选择开始时间" id="begin_time"> -
			    	<input type="text" class="form-control" placeholder="请选择结束时间" id="end_time">
			  	</div>
			  	<div class="form-group">
			    	<label>订单号：</label>
			    	<input type="text" class="form-control" v-model="sn">
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="out_list()">导出</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="10%">
					<col width="10%">	
					<col width="8%">
					<col width="10%">
					<col width="10%">
					<col width="10%">	
					<col width="10%">
					<col width="10%">	
					<col width="10%">
					<col width="12%">
				</colgroup>
				<thead>
					<tr>
						<th>订单号</th>
						<th>买家昵称</th>
						<th>消费金额</th>
						<th>一级分销手机号</th>
						<th>一级分销金额</th>
						<th>二级分销手机号</th>
						<th>二级分销金额</th>
						<th>团队分销手机号</th>
						<th>团队分销金额</th>
						<th>购买时间</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td><a @click="show_detail(n.id)">@{{n.sn}}</a></td>
						<td>@{{n.nickname}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.first_mobile}}</td>
						<td>@{{n.first_money}}</td>
						<td>@{{n.second_mobile}}</td>
						<td>@{{n.second_money}}</td>
						<td>@{{n.team_mobile}}</td>
						<td>@{{n.team_money}}</td>
						<td>@{{n.pay_time}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/dealer_list.js')}}"></script>
@endsection