@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="experts_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
			  	<div class="form-group pl30">
			    	<label>昵称：</label>
			    	<input type="text" class="form-control" v-model="nickname">
			  	</div>
			  	<div class="form-group pl30">
			    	<label>姓名：</label>
			    	<input type="text" class="form-control" v-model='name'>
			  	</div>
			  	<div class="form-group pl30">
			    	<label>手机号码：</label>
			    	<input type="text" class="form-control" v-model="mobile">
			  	</div>
			  	<div class="form-group pl30">
			    	<label>状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="1">正常</option>
						<option value="2">冻结</option>
			    	</select>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="show_code()">达人注册二维码</button>
					<button class="layui-btn layui-btn-normal" @click="out_list()">导出</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="10%">
					<col width="8%">	
					<col width="10%">
					<col width="12%">	
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr>
						<th>昵称</th>
						<th>姓名</th>
						<th>手机号</th>
						<th>一级分销金额</th>
						<th>二级分销金额</th>
						<th>团队分销金额</th>
						<th>已提现总金额</th>
						<th>账户余额</th>
						<th>达人资格状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.name}}</td>
						<td>@{{n.mobile}}</td>
						<td>@{{n.account?n.account.primary_distribution_money:0}}</td>
						<td>@{{n.account?n.account.secondary_distribution_money:0}}</td>
						<td>@{{n.account?n.account.team_distribution_money:0}}</td>
						<td>@{{n.account?n.account.withdraw_money:0}}</td>
						<td>@{{n.account?n.account.balance:0}}</td>
						<td>@{{n.status==1?'正常':'冻结'}}</td>
						<td>
							<button class="layui-btn layui-btn-sm layui-btn-danger" v-if="n.status==1" @click="freeze(n.uid,index)">冻结</button>
							<button class="layui-btn layui-btn-sm" v-if="n.status!=1" @click="unfreeze(n.uid,index)">解冻</button>
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
<script src="{{statics('js/admin/experts_list.js')}}"></script>
@endsection