@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="team_list" v-cloak>
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
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="13%">
					<col width="10%">	
					<col width="12%">
					<col width="12%">	
					<col width="13%">
					<col width="13%">
					<col width="10%">
					<col width="12%">
				</colgroup>
				<thead>
					<tr>
						<th>队长昵称</th>
						<th>队长姓名</th>
						<th>队长手机号</th>
						<th>团队人数</th>
						<th>已卖出产品人数</th>
						<th>组建团队分销金额</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.user_talent.name}}</td>
						<td>@{{n.user_talent.mobile}}</td>
						<td>@{{n.number_of_team_users}}</td>
						<td>@{{n.number_of_satisfied_popler}}</td>
						<td>@{{n.account.team_distribution_money}}</td>
						<td>
							<button class="layui-btn layui-btn-sm" @click="to_member(n.uid,n.user.nickname,n.user_talent.name,n.user_talent.mobile)">查看组队成员</button>
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
<script src="{{statics('js/admin/team_list.js')}}"></script>
@endsection