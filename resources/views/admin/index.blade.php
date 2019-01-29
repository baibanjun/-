@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="index" v-cloak>
		欢迎您（@{{user}}）进入吃喝玩乐成都联盟后台

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{env('STATIC_URL')}}js/admin/index.js"></script>
@endsection