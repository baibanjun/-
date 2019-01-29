@extends('../layouts.main')

@section('content')


<div id="u_orderApp">

    <div class="user_top" style="background:none;">

        <div class="ut4">
            <ul>
                <li v-cloak style="border-radius:0;box-shadow:none;">
                    <div class="ut41" v-if="d.type==1" v-cloak>
                        电子码：@{{d.code}}
                    </div>
                    <div class="ut42">
                        <p class="ut421">订单号码：@{{d.sn}}</p>
                        <p class="ut422"><a style="text-decoration: underline;" :href="details(d.product&&d.product.id)">@{{d.product&&d.product.subtitle}}</a></p>
                        <p class="ut421">￥@{{d.money}}</p>
                    </div>
                    <div class="ut42">
                        <p class="ut421">用户姓名：@{{d.name}}</p>
                        <p class="ut421">联系电话：@{{d.tel}}</p>

                        <p class="ut421" v-if="d.type==3">所在地区：<span v-for="dd in d.area_value">@{{dd.name}}</span></p>
                        <p class="ut421" v-if="d.type==3">详细地址：@{{d.address}}</p>

                        <p class="ut421">备注：@{{d.remark}}</p>
                    </div>

                    <div style="padding: 0.4rem 0;" v-if="d.status!=3&&d.type!=3">
                        订单二维码
                    </div>
                    <div style="text-align: center;" v-if="d.status!=3&&d.type!=3">
                        <img :src="ewm">
                    </div>

                    <div style="padding: 0.4rem 0;" v-if="d.status>=3&&d.type==3" v-cloak>
                        快递单号：@{{d.express_number}}
                        @{{d.express_company}}
                    </div>

                    <div class="ut43" style="padding: 0.4rem 0;text-align: center;" v-if="d.type==3&&d.status==3">
                        <button style="float:none;" @click="mmp()">确认收货</button>
                    </div>

                </li>

            </ul>

        </div>
    </div>


</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/u_order.js')}}"></script>

@endsection