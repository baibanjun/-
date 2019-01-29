@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="business_apply_record" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>操作时间：</label>
			    	<input type="text" class="form-control" placeholder="请选择开始时间" id="begin_time"> -
			    	<input type="text" class="form-control" placeholder="请选择结束时间" id="end_time">
			  	</div>
			  	<div class="form-group">
			    	<label>姓名：</label>
			    	<input type="text" class="form-control" v-model="name">
			  	</div>
			  	<div class="form-group">
			    	<label>电话：</label>
			    	<input type="text" class="form-control" v-model='tel'>
			  	</div>
			  	<div class="form-group">
			    	<label>订单状态：</label>
			    	<select class="form-control" v-model="status">
						<option value="all">全部</option>
						<option value="1">已通过</option>
						<option value="2">已驳回</option>
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
					<col width="10%">	
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">	
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>姓名</th>
						<th>电话</th>
						<th>行业</th>
						<th>备注</th>
						<th>申请时间</th>
						<th>处理时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.name}}</td>
						<td>@{{n.tel}}</td>
						<td>@{{n.industry}}</td>
						<td>@{{n.remark}}</td>
						<td>@{{n.created_at}}</td>
						<td>@{{n.updated_at}}</td>
						<td>@{{n.status==1?'已通过':'已驳回'}}</td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/business_apply_record.js')}}"></script>
@endsection