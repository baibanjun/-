@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="withdraw_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>申请时间：</label>
			    	<input type="text" class="form-control" placeholder="请选择开始时间" id="begin_time"> -
			    	<input type="text" class="form-control" placeholder="请选择结束时间" id="end_time">
			  	</div>
			  	<div class="form-group">
			    	<label>昵称：</label>
			    	<input type="text" class="form-control" v-model="nickname">
			  	</div>
			  	<div class="form-group">
			    	<label>手机号码：</label>
			    	<input type="text" class="form-control" v-model='mobile'>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="to_set_alert()">提现提醒设置</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="12%">
					<col width="10%">	
					<col width="15%">
					<col width="12%">
					<col width="12%">
					<col width="16%">	
					<col width="23%">
				</colgroup>
				<thead>
					<tr>
						<th>昵称</th>
						<th>姓名</th>
						<th>手机号码</th>
						<th>申请提现金额</th>
						<th>提现后余额</th>
						<th>申请时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.user_talent.name}}</td>
						<td>@{{n.user_talent.mobile}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.balance}}</td>
						<td>@{{n.created_at}}</td>
						<td>
							<button class="layui-btn layui-btn-sm" @click="to_account(index)">账户流水</button>
							<button class="layui-btn layui-btn-sm layui-btn-normal" @click="pass_cash(n.id)">提现成功</button>
							<button class="layui-btn layui-btn-sm layui-btn-danger" @click="refuse(n.id)">驳回申请</button>
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
<script src="{{statics('js/admin/withdraw_list.js')}}"></script>
@endsection