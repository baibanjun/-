@extends('../layouts.main')

@section('css')
<link href="{{statics('css/swiper.min.css')}}" rel="stylesheet">
<style>
.header{top: 1.3rem;}
#detailsApp{padding-top: 1.3rem;position: relative;}
.details_t_top{    background: #000000;
                   position: fixed;
                   top: 0;
                   left: 0;
                   width: 9.52rem;
                   padding: 0 0.24rem;
                   height: 1.3rem;    z-index: 2;}
.details_t_top .l_sd{float:left;width:50%;color:#ffffff;line-height:1.3rem;
    overflow: hidden;
    white-space: nowrap;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;}
.details_t_top button{    float: right;
                          height: 0.7rem;
                          padding: 0 0.2rem;
                          margin-top: 0.28rem;
                          border: 0;
                          background: #09bd02;
                          color: #ffffff;
                          border-radius: 5px;}
</style>
@endsection

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
        </div>
    </div>
</div>

<div id="detailsApp">
    <div class="details_t_top clearfix">
        <div class="l_sd ell">@{{topContent}}</div>
        <button @click="app_ewm()">立即关注</button>
    </div>

    <div class="details-box">

        <div class="details_time_limit" v-if="data.status==1&&data.is_on_hand&&data.is_countdown==1" v-cloak>@{{data.djs}}</div>

        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="d in data.pics" :style="styles(d.name,750,750)"></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="db-info" v-cloak>
            <p class="content" v-html="data.subtitle"></p>
            <div class="standards">
                <div class="price">
                    <span class="p1">￥@{{curStandards.sale_price}}</span>
                    <span class="p2">门市价￥@{{curStandards.price}}</span>
                </div>
                <div class="sold clearfix">
                    <span class="quantity_sold">已售：@{{curStandards.quantity_sold}}</span>
                    <span class="onhand">库存：@{{curStandards.onhand}}</span>
                </div>
            </div>
        </div>

        <div class="business" v-if="data.type===1">
            <div class="title">商家信息</div>
            <div class="content clearfix">
                <div class="c1" @click="openLocation()"><i class="fa fa-map-marker"></i></div>
                <div class="c2" @click="openLocation()">
                    <div class="c21 q_ell" v-cloak>@{{data.business?data.business.name:''}}</div>
                    <div class="c22 q_ell" v-cloak>@{{data.business?data.business.address:''}}</div>
                </div>
                <div class="c3"><a :href="data.business?'tel:'+data.business.tel:'javascript:;'"><i class="fa fa-phone"></i></a></div>
            </div>
        </div>

        <div class="standards_tab" v-if="data.type===3&&data.standards.length>1">
            <div class="title">规格选择</div>
            <div class="content">
                <ul class="clearfix">
                    <li v-cloak v-for="st in data.standards" @click="curStandardsFn(st)" :class="{'active':st.id===curStandards.id}">
                        @{{st.name}}
                    </li>
                </ul>
            </div>
        </div>

        <div class="float_r" v-if="floatR">
            <div class="close" @click="close()"></div>
            <div class="title">会员分享</div>
            <div class="content">
                <div class="c1" v-cloak>返￥@{{data|rePrice}}</div>
                <div class="c2">下单即得佣金</div>
                <div class="c3"><button @click="poster()">立即分享</button></div>
            </div>
        </div>

        <div class="poster_s" v-if="is_poster_s" :style="poster_s_style">
            <div class="ps1">请点击右上角按钮进行分享</div>
            <div class="ps2">
                <button @click="poster_show()" class="active">产品宣传海报</button>
                <button @click="poster_close()">取消</button>
            </div>
        </div>

        <div class="details_content">
            <div class="title">详细信息</div>
            <div class="article" v-html="data.content"></div>
        </div>

    </div>

    <div class="nav-bb clearfix">
        <a href="{{url('/')}}">商城首页</a>
        <a href="tel:18848465761">咨询客服</a>
        <a class="b1" href="javascript:;" v-if="curStandards.onhand>=1&&data.status==1" @click="buy(data)">立即购买</a>
        <a class="b2" href="javascript:;" v-if="curStandards.onhand>=1&&data.status==2">已下架</a>
        <a class="b2" href="javascript:;" v-if="curStandards.onhand==0">已告罄</a>
    </div>
</div>

@endsection

@section('javascript')
<script>
var app_ewm = "{{config('console.app_ewm')}}";
</script>

<script src="{{statics('js/swiper.min.js')}}"></script>
<script src="{{statics('js/ints/details.js')}}"></script>

@endsection