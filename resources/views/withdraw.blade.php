@extends('../layouts.main')

@section('css')
<link href="{{statics('iconfont/iconfont.css')}}" rel="stylesheet">
@endsection

@section('content')


<div id="withdrawApp">

    <div class="w1"><i class="iconfont icon-qiandai" style="font-size: 4rem;color: #e8541e;"></i></div>
    <div class="w2">佣金余额</div>
    <div class="w3" v-cloak>￥@{{cofferData.t2}}</div>
    <br>
    <div class="w4">
        <input id="p2" type="text" v-model="money" placeholder="请输入提现金额" autocomplete="off">
    </div>
    <div class="w5">
        <button @click="withdraw()">申请提现</button>
    </div>
    <div class="w6">
        满足￥1即可提现，一个工作日内到账！
    </div>

</div>

<div class="nav-c clearfix">
    <a href="{{url('/burse')}}">我的金库</a>
    <a class="active" href="{{url('/withdraw')}}">申请提现</a>
    <a href="{{url('/talent_info')}}">达人信息</a>
</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/withdraw.js')}}"></script>

@endsection