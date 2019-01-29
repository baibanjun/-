@extends('../layouts.main')

@section('css')
<link href="{{statics('iconfont/iconfont.css')}}" rel="stylesheet">
@endsection

@section('content')


<div id="talent_infoApp">

    <div class="user_top">
        <div class="talent_info_tt"></div>
        <div class="talent_info_t">

            <div class="title">个人信息</div>
            <div class="c1">
                <p v-cloak>姓名：@{{data.name}}</p>
                <p v-cloak>手机：@{{data.mobile}}</p>
            </div>

            <br>

            <div class="title">店铺信息</div>
            <div class="c1">
                <p v-cloak class="clearfix">
                    <span class="ls">店铺LOGO：</span>
                    <span class="rs">
                        <img :src="data.user&&data.user.headimgurl">
                    </span>
                </p>
                <p v-cloak class="clearfix">
                    <span class="ls">店名：</span>
                    <span class="rs">
                        @{{data.user&&data.user.nickname}}
                    </span>
                </p>
            </div>

            <br>

            <div class="title">组建团队二维码</div>
            <div class="c1" style="text-align: center;border: 0 none;">
                <img :src="teamData" style="width:5rem;">
            </div>

        </div>
    </div>

</div>

<div class="nav-c clearfix">
    <a href="{{url('/burse')}}">我的金库</a>
    <a href="{{url('/withdraw')}}">申请提现</a>
    <a class="active" href="{{url('/talent_info')}}">达人信息</a>
</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/talent_info.js')}}"></script>

@endsection