@extends('../layouts.main')

@section('css')
<link href="{{statics('iconfont/iconfont.css')}}" rel="stylesheet">
@endsection

@section('content')


<div id="burseApp">

    <div class="bs1">
        <p class="bs11" v-cloak>￥@{{cofferData.t1}}</p>
        <p>我在联盟平台总收益</p>
    </div>

    <div class="bs2">
        <span v-cloak>￥@{{cofferData.t2}}<br>可提现</span>
        <span v-cloak>￥@{{cofferData.t3}}<br>已提现</span>
        <span v-cloak>￥@{{cofferData.t4}}<br>直卖收益</span>
        <span v-cloak>￥@{{cofferData.t5}}<br>组建团队收益</span>
    </div>

    <div class="bs3">
        <ul>
            <li v-for="d in data.data" v-cloak>
                <div class="ut41">
                    <p>订单号码：@{{d.order.sn}}</p>
                    <p><a style="text-decoration: underline;" :href="details(d.product.id)">@{{d.product.subtitle}}</a></p>
                </div>
                <div class="ut41">
                    <p class="">用户姓名：@{{d.order.name}}</p>
                    <p class="">联系电话：@{{d.order.tel}}</p>
                    <p class="ut411">一级分销佣金：¥@{{d.money.primary}}</p>
                    <p class="ut411">二级分销佣金：¥@{{d.money.secondary}}</p>
                    <p class="ut411">团队分销佣金：¥@{{d.money.team}}</p>
                    <p v-if="d.order.status==0">当前状态：未支付</p>
                    <p v-if="d.order.status==1">当前状态：已支付</p>
                    <p v-if="d.order.status==2">当前状态：已预约</p>
                    <p v-if="d.order.status==3">当前状态：已发货</p>
                    <p v-if="d.order.status==4">当前状态：已完成</p>
                </div>
            </li>

            <li v-if="noProduct" style="text-align: center;background:none;box-shadow:none;">
                <i class="iconfont icon-wuchanpin-" style="font-size: 6rem;color: #cccccc;"></i><br>暂无订单
            </li>
        </ul>
    </div>

</div>

<div class="nav-c clearfix">
    <a class="active" href="{{url('/burse')}}">我的金库</a>
    <a href="{{url('/withdraw')}}">申请提现</a>
    <a href="{{url('/talent_info')}}">达人信息</a>
</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/burse.js')}}"></script>

@endsection