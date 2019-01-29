@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="account_record" v-cloak>
		<p class=""><button class="layui-btn layui-btn-primary" @click="to_list()"><<返回</button></p>
		<!--队长-->
		<p class="pl30 mg20 t22 b col-g">用户信息</p>
		<hr style="width: 70%">
		<p class="pl30 mg20">
			<span>昵称： @{{cash_info.user.nickname}}</span>
			<span style="margin-left: 150px">姓名： @{{cash_info.user_talent.name}}</span>
		</p>
		<p class="pl30 mg20">
			<span>手机号码： @{{cash_info.user_talent.mobile}}</span>
			<span style="margin-left: 90px">提现后剩余金额： @{{cash_info.balance}}</span>
		</p>
		<!--队员-->
		<p class="pl30 mg20 t22 b col-b">流水信息</p>
		<hr style="width: 70%">
		<div style="width: 40%" class="pl30">
			<table class="layui-table">
				<thead>
					<tr>
						<th>时间</th>
						<th>金库操作</th>
						<th>备注</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="n in list">
						<td>@{{n.created_at}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.object_type==1?'分销收入':n.object_type==2?'团队收入':n.object_type==3?'推荐用户收入':n.object_type==4?'提现扣款':'提现驳回'}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc"></div>
		</div>
		
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/account_record.js')}}"></script>
@endsection