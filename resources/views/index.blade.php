@extends('../layouts.main')

@section('content')

<div id="app_header">
    <div class="header">
        <div class="headers clearfix">
            <div class="user-avatar">
                <img :src="wxInfo.headimgurl">
            </div>
            <div class="user-name" v-cloak>
                @{{wxInfo.nickname}}
            </div>
            <div class="city-tab" v-on:click="tab_city()">
                <span v-cloak>@{{curCity.name}}</span>
                <i class="fa" :class="is_tab_city?'fa-chevron-down':'fa-chevron-up'"></i>
            </div>
        </div>
    </div>
    <div class="city-tab-list" v-bind:style="styleObject" v-if="!is_tab_city">
        <ul class="clearfix">
            <li class="active" v-on:click="tab_city_active(d)" v-for="d in cityArr" v-cloak>@{{d.name}}</li>
        </ul>
    </div>
</div>

<div class="goods" id="goodsApp" @scroll="getData(1)">
    <ul>
        <li v-for="d in data.data" v-cloak>
            <a :href="detailsUrl+'?id='+d.id+'&f=0'+'&s=0'">

                <div class="status"     v-if="d.status==1&&d.is_on_hand&&d.is_countdown==0">抢购中</div>
                <div class="time_limit" v-if="d.status==1&&d.is_on_hand&&d.is_countdown==1">@{{d.djs}}</div>
                <div class="status c1"  v-if="d.status==1&&!d.is_on_hand">已告罄</div>
                <div class="status c1"  v-if="d.status==2">已下架</div>

                <div class="img">
                    <img :src="d.pics[0].name|cosPic(750)">
                </div>
                <div class="text" v-html="d.subtitle"></div>
                <div class="info clearfix">
                    <div class="price">
                        <span class="p1">￥@{{d.standards[0].sale_price}}</span>
                        <span class="p2">门市价￥@{{d.standards[0].price}}</span>
                        <span class="p3">返￥@{{d|rePrice}}</span>
                    </div>
                    <div class="num">销售量：@{{d.standards[0].quantity_sold}}</div>
                </div>
            </a>
        </li>
        <li class="more">
            <div v-if="moreAny1"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></div>
            <div v-if="moreAny">没有更多了</div>
        </li>
    </ul>
</div>

<div class="nav-b clearfix">
    <a style="width: 50%;" class="{{Request::path()=='/'?'active':''}}" href="{{url('/')}}"><i class="fa fa-tachometer"></i><br>吃喝玩乐go</a>
    <!--<a class="{{Request::path()=='business'?'active':''}}" href="{{url('/business')}}"><i class="fa fa-globe"></i><br>联盟商城</a>-->
    <a style="width: 50%;" class="{{Request::path()=='user'?'active':''}}" href="{{url('/user')}}"><i class="fa fa-user"></i><br>我的</a>
</div>

@endsection

@section('javascript')

<script>
var detailsUrl = '{{url('/details')}}';
var productType = {{Request::path()=='business'?3:1}};
</script>

<script src="{{statics('js/ints/index.js')}}"></script>

@endsection