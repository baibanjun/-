@extends('../layouts.main')

@section('css')
<link href="{{statics('iconfont/iconfont.css')}}" rel="stylesheet">
@endsection

@section('content')


<div id="userApp">

    <div class="user_top">
        <div class="ut1">
            <p class="ut11"><img src="{{statics('images/u1.png')}}"></p>
            <p class="ut12"><img :src="wxInfo.headimgurl"></p>
            <p class="ut13" v-cloak>@{{wxInfo.nickname}}</p>
        </div>
        <div class="ut2">
            <a href="{{url('user')}}" :class="{'active':type==1,'www2':!is_burse}">
                <i class="iconfont icon-dingwei-" style="font-size:20px;"></i><br>
                全部订单
            </a>
            <a href="{{url('user')}}?type=2" :class="{'active':type==2,'www2':!is_burse}">
                <i class="iconfont icon-yizhifudingdan" style="line-height: 1.5;font-size: 14px;"></i><br>
                已支付
            </a>
            <a href="{{url('user')}}?type=3" :class="{'active':type==3,'www2':!is_burse}">
                <i class="iconfont icon-yiwanchengdingdan1" style="line-height: 1.5;font-size: 14px;"></i><br>
                已预约
            </a>
            <a href="{{url('user')}}?type=4" :class="{'active':type==4,'www2':!is_burse}">
                <i class="iconfont icon-yiwanchengdingdan" style="line-height: 1.5;"></i><br>
                已完成
            </a>
            <a href="{{url('burse')}}" v-if="is_burse">
                <i class="iconfont icon-0017" style="line-height: 1.8;"></i><br>
                我的金库
            </a>
            <a href="{{url('user')}}?type=ewm" :class="{'active':type=='ewm','www2':!is_burse}">
                <i class="iconfont icon-erweima" style="line-height: 1.5;"></i><br>
                我的二维码
            </a>
        </div>
        <div class="ut3" v-if="type!='ewm'">
        如有预约或售后问题，请添加咨询吃喝玩乐成都联盟客服微信：cdhxgy
        </div>

        <div class="ut5" v-if="type=='ewm'">
            <p>邀请其他用户扫描二维码关注吃喝玩乐成都联盟公众号奖励等着你</p>
            <p><img :src="index"></p>
        </div>

        <div class="ut4">
            <ul>
                <li v-cloak v-for="d in data.data">
                    <div class="ut41" v-if="d.type==1" v-cloak>
                        电子码：@{{d.code}}
                    </div>
                    <div class="ut42">
                        <p class="ut421">订单号码：@{{d.sn}}</p>
                        <p class="ut422"><a style="text-decoration: underline;" :href="details(d.product.id)">@{{d.product.subtitle}}</a></p>
                        <p class="ut421">用户姓名：@{{d.name}}</p>
                        <p class="ut421">联系电话：@{{d.tel}}</p>
                        <p class="ut421">备注：@{{d.remark}}</p>
                    </div>
                    <div class="ut43 clearfix">
                        <span>￥@{{d.money}}</span>
                        <button v-if="d.status!=0" @click="u_order(d)">查看详情</button>
                        <button class="active" v-if="d.status==0" @click="pay(d)">立即支付</button>
                    </div>
                </li>

                <li v-if="noProduct" style="text-align: center;background:none;box-shadow:none;">
                    <i class="iconfont icon-wuchanpin-" style="font-size: 6rem;color: #cccccc;"></i><br>暂无订单
                </li>
            </ul>

        </div>
    </div>


</div>

<div class="nav-b clearfix">
    <a style="width: 50%;" class="{{Request::path()=='/'?'active':''}}" href="{{url('/')}}"><i class="fa fa-tachometer"></i><br>吃喝玩乐go</a>
    <!--<a class="{{Request::path()=='business'?'active':''}}" href="{{url('/business')}}"><i class="fa fa-globe"></i><br>联盟商城</a>-->
    <a style="width: 50%;" class="{{Request::path()=='user'?'active':''}}" href="{{url('/user')}}"><i class="fa fa-user"></i><br>我的</a>
</div>

@endsection

@section('javascript')

<script>
var qr_url = '{{config('console.qr_url')}}'
</script>

<script src="{{statics('js/ints/user.js')}}"></script>

@endsection