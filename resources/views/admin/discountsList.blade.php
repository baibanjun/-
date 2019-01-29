@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="discounts_list" v-cloak>
		<div class="search_bar">
			<div class="form-inline">
			  	<div class="form-group">
			    	<label>微信昵称：</label>
			    	<input type="text" class="form-control" v-model="nickname">
			  	</div>
			  	<div class="form-group pl30">
					<button class="layui-btn" @click="get_list(1,true)">搜索</button>
			  	</div>				  	
			</div>
		</div>
		<div>
			<table class="layui-table">
				<colgroup>
					<col width="20%">
					<col width="20%">	
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>微信昵称</th>
						<th>未使用优惠券数量</th>
						<th>已使用优惠券数量</th>
						<th>已过期优惠券数量</th>						
						<th>已转赠优惠券数量</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(n,index) in list">
						<td>@{{n.nickname}}</td>
						<td><a class="col-g" @click="to_discount(n.id,'not_use',n.nickname)">@{{n.not_use_num}}</a></td>
						<td><a class="col-g" @click="to_discount(n.id,'has_use',n.nickname)">@{{n.has_use_num}}</a></td>
						<td><a class="col-g" @click="to_discount(n.id,'overdue',n.nickname)">@{{n.overdue_num}}</a></td>
						<td><a class="col-g" @click="to_discount(n.id,'has_send',n.nickname)">@{{n.has_send_num}}</a></td>
					</tr>
				</tbody>
			</table>
			<div id="pages" class="tc mg20"></div>
		</div>
		<!--弹出层-->
		<div class="cen" v-show="discount_show" v-cloak style="width: 800px;">
			<p class="tc mg20" style="width: 95%">
				<span class="col-g">@{{discount_user}}</span>
				<span>@{{discount_type=="not_use"?'未使用优惠券':discount_type=="has_use"?'已使用优惠券':discount_type=="overdue"?'已过期优惠':'已转赠优惠'}}</span>
				<span class="r"><button class="layui-btn layui-btn-xs layui-btn-danger" @click="close()">X</button></span>
			</p>
			<table class="layui-table" style="width: 94%; margin:0 3%;">
			   <thead>
					<tr>
						<th>抽奖标题</th>
						<th>优惠券名称</th>
						<th>开始有效日期</th>
						<th>有效截止日期</th>
						<th v-if="discount_type=='has_use'">使用日期</th>
						<th v-if="discount_type=='has_send'">转赠日期</th>
				   </tr>
			   </thead>
			   <tbody>
					<tr v-for="n in discount_list">
						<td>@{{n.lottery_draw==null?'':n.lottery_draw.title}}</td>
						<td>@{{n.prize.name}}</td>
						<td>@{{n.start_date}}</td>
						<td>@{{n.end_date}}</td>
						<td v-if="discount_type=='has_use'">@{{n.send_date}}</td>
						<td v-if="discount_type=='has_send'">@{{n.send_date}}</td>
				   </tr>
			   </tbody>
			</table>
			<p class="mg20 tc" id="page_1"></p>								
		</div>
		<div class="cover" v-show="cover" v-cloak></div>
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/discounts_list.js')}}"></script>
@endsection