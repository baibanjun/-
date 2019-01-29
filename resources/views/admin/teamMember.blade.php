@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="team_member" v-cloak>
		<p class=""><button class="layui-btn layui-btn-primary" @click="to_list()"><<返回</button></p>
		<!--队长-->
		<p class="pl30 mg20 t22 b col-g">队长信息</p>
		<hr style="width: 70%">
		<p class="pl30 mg20">
			<span>昵称： @{{captain_info.nickname}}</span>
			<span style="margin-left: 150px">姓名： @{{captain_info.name}}</span>
		</p>
		<p class="pl30 mg20">电话号码： @{{captain_info.mobile}}</p>
		<!--队员-->
		<p class="pl30 mg20 t22 b col-b">队员信息</p>
		<hr style="width: 70%">
		<div style="width: 40%" class="pl30">
			<table class="layui-table">
				<thead>
					<tr>
						<th>昵称</th>
						<th>卖出产品金额</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="n in list">
						<td>@{{n.member_user.nickname}}</td>
						<td>@{{n.amount_of_product_sold}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc"></div>
		</div>
		
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/team_member.js')}}"></script>
@endsection