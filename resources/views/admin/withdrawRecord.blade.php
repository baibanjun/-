@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="witndraw_record" v-cloak>
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
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="15%">
					<col width="10%">	
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">	
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>昵称</th>
						<th>姓名</th>
						<th>手机号码</th>
						<th>申请提现金额</th>						
						<th>申请时间</th>
						<th>操作时间</th>
						<th>状态</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.user.nickname}}</td>
						<td>@{{n.user_talent.name}}</td>
						<td>@{{n.user_talent.mobile}}</td>
						<td>@{{n.money}}</td>
						<td>@{{n.created_at}}</td>
						<td>@{{n.updated_at}}</td>
						<td>@{{n.status==1?'已扣款':n.status==2?'已打款':'已驳回'}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/witndraw_record.js')}}"></script>
@endsection