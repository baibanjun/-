@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="invite_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>关注时间：</label>
			    	<input type="text" class="form-control" placeholder="请选择开始时间" id="begin_time"> -
			    	<input type="text" class="form-control" placeholder="请选择结束时间" id="end_time">
			  	</div>
			  	<div class="form-group">
			    	<label>用户昵称：</label>
			    	<input type="text" class="form-control" v-model='nickname'>
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="25%">
					<col width="25%">	
					<col width="25%">
					<col width="25%">
				</colgroup>
				<thead>
					<tr>
						<th>用户昵称</th>
						<th>被邀请用户</th>
						<th>奖励金额</th>
						<th>关注时间</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="n in list">
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.object_user.nickname}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.created_at}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/invite_list.js')}}"></script>
@endsection