@extends('../layouts.main')

@section('content')

<div id="my_couponApp">

    <div class="my_couponApp_header clearfix">
        <div class="lsd">
            <img :src="wxInfo.headimgurl">
        </div>
        <div class="rsd">
            <p class="p1" v-cloak>@{{wxInfo.nickname}}</p>
            <p class="p2" v-cloak>剩余优惠券 @{{data_length}}张</p>
        </div>
    </div>
    <div class="my_couponApp_nav clearfix">
        <span :class="{'active':type==1}" @click="tab(1)">未使用</span>
        <span :class="{'active':type==2}" @click="tab(2)">已使用</span>
        <span :class="{'active':type==3}" @click="tab(3)">已过期</span>
        <span :class="{'active':type==4}" @click="tab(4)">已转赠</span>
    </div>
    <div class="my_couponApp_list">
        <ul>
            <li class="clearfix" :class="{'active':type!=1}" v-cloak v-for="d in data" @click="type==1?details(d):''">
                <div class="lsd">@{{d.prize.name}}</div>
                <div class="rsd">
                    <div class="r1 ell"><span>店铺</span>@{{d.lottery_draw&&d.lottery_draw.business&&d.lottery_draw.business.name}}</div>
                    <div class="r2 ell">
                        [@{{d.lottery_draw&&d.lottery_draw.business&&d.lottery_draw.business.name}}]  @{{d.prize.name}}
                    </div>
                    <div class="r3 clearfix">
                        <span>@{{d.start_date}} - @{{d.end_date}}</span>
                        <button v-if="type==1">立即使用 </button>
                    </div>
                </div>
            </li>
            <li v-if="data.length<1" v-cloak style="text-align: center;color: #999;padding: 0.3rem 0;">暂无数据</li>
        </ul>
    </div>

    <div class="poster_s" v-if="is_poster_s" :style="poster_s_style">
        <div class="ps1">点这里分享到朋友圈<br>分享给好友</div>
    </div>

</div>


@endsection

@section('javascript')

<script src="{{statics('js/ints/my_coupon.js')}}"></script>

@endsection