@extends('../layouts.main')

@section('css')
<link href="{{statics('iconfont/iconfont.css')}}" rel="stylesheet">
@endsection

@section('content')

<div id="lotteryApp">

    <div class="lottery_top" :style="styles()">
        <div class="lottery_top_a" v-if="all_data.lottery_type===1">
            <div class="lottery_top_a_i" v-for="(d,i) in all_data.lottery_draw_list" :class="'l'+(i+1)" v-if="i<8" v-cloak>
                <div class="msk" v-if="i+1===index"></div>
                <img :src="d.pic[0].name|cosPic1(100,100)" style="width:1.4rem;margin-top:0.2rem;"><br>
                @{{d.name}}
            </div>

            <div class="lottery_top_a_b" @click="lottery()"></div>

        </div>
        <div class="lottery_top_aa" v-if="all_data.lottery_type===2">
            <div class="lottery_top_aa_i" v-for="(d,i) in all_data.lottery_draw_list" :class="'l'+(i+1)" v-if="i<6" v-cloak>@{{d.name}}</div>

            <div class="lottery_top_aa_b" @click="lottery()"></div>

        </div>
        <div class="lottery_top_b">
            您还有<span v-cloak>@{{all_data.number.number}}</span>次机会
            <span style="font-size: 12px;text-decoration: underline;" v-show="all_data.number.number==0&&all_data.user_today_prize_number.is_share==0" @click="get_share_3()" v-cloak>点击分享获取@{{all_data.share_get_num&&all_data.share_get_num.value&&all_data.share_get_num.value.share_get_num}}次机会</span>
            <br>
            已有<span v-cloak>@{{all_data.join_number}}</span>人参与活动
        </div>
    </div>

    <div class="lottery_content">
        <div class="lottery_content_c clearfix" @click="weichat_group()">
            <div class="lottery_content_c_f">
                <img :src="all_data.weichat_group.value.group_qr_code.name|cosPic1(100)">
            </div>
            <div class="lottery_content_c_r">
                <p class="p1" v-cloak>@{{all_data.weichat_group.value.group_name}}</p>
                <p class="p2" v-cloak>@{{all_data.weichat_group.value.group_title}}</p>
                <p class="p3">
                    <button>点我加入</button>
                </p>
            </div>
        </div>
        <div class="lottery_content_t">
            <div></div>
            <span><b>中奖用户</b></span>
        </div>
        <div class="lottery_content_c">
            <div class="lottery_content_c_u">
                <ul :style="data_user_style">
                    <li class="clearfix" v-for="d in data_user" v-cloak>
                        <div class="img"><img :src="d.user.headimgurl"></div>
                        <div class="text">
                            <p><span class="t1">@{{d.user.nickname}}</span><span class="t2">@{{d.created_at}}</span></p>
                            <p>获得<span class="t3">@{{d.prize.name}}</span></p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="lottery_content_c_u1">
                <button @click="window.location.href=app_config.WEB_URL + 'my_coupon'">我的奖券</button>
            </div>
        </div>

        <div class="lottery_content_t">
            <div></div>
            <span><b>活动说明</b></span>
        </div>
        <div class="lottery_content_c">
            <div class="article" v-cloak v-html="all_data.description"></div>
        </div>

        <div class="lottery_content_t">
            <div></div>
            <span><b>商家信息</b></span>
        </div>
        <div class="lottery_content_c">
            <div class="article" v-cloak v-html="all_data.business_introduce"></div>
        </div>

        <div class="lottery_content_c1">
            营销合作电话：18848465762
        </div>
        <div class="lottery_content_c2">
            <a href="{{url('business_apply')}}">我是商户，我要发券</a>
        </div>

    </div>

    <div class="poster_s" v-if="is_poster_s" :style="poster_s_style">
        <div class="ps1">点这里分享到朋友圈<br>才可以领取奖励哦</div>
    </div>

    <div class="lottery_float">
        <a :href="'tel:'+all_data.business.tel"><i class="iconfont lf1 icon-phone-channel"></i></a><br>
        <hr>
        <i class="iconfont lf2 icon-dizhi_" @click="openLocation()"></i>
    </div>


</div>


@endsection

@section('javascript')

<script src="{{statics('js/ints/lottery.js')}}"></script>

@endsection