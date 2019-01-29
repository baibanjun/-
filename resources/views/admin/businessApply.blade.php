@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="business_apply" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
				<div class="form-group pl30">
			    	<label>申请时间：</label>
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
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
					<button class="layui-btn layui-btn-normal" @click="to_set_alert()">结果提醒设置</button>
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
					<col width="20%">	
					<col width="25%">
				</colgroup>
				<thead>
					<tr>
						<th>姓名</th>
						<th>手机号码</th>
						<th>行业</th>
						<th>备注</th>
						<th>申请时间</th>
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
						<td>
							<button class="layui-btn layui-btn-sm layui-btn-normal" @click="pass(n.id)">通过</button>
							<button class="layui-btn layui-btn-sm layui-btn-danger" @click="refuse(n.id)">驳回</button>
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
<script src="{{statics('js/admin/business_apply.js')}}"></script>
@endsection