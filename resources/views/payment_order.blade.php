@extends('../layouts.main')

@section('content')

<div id="payment_orderApp">
    <div class="payment_order_box">
        <div class="title">输入订单信息</div>
        <div class="ipt">
            <label for="p1">收货姓名：</label>
            <input id="p1" type="text" v-model="name" placeholder="请输入姓名" autocomplete="off">
        </div>
        <div class="ipt">
            <label for="p2">手机号码：</label>
            <input id="p2" type="text" v-model="tel" placeholder="请输入手机号码" autocomplete="off">
        </div>
        <div class="ipt clearfix" v-if="data.type==3">
            <label for="p3" class="ls" style="float: left;">所在地区：</label>
            <div class="rs" style="float: left;">
                <div>
                    <select v-model="area_province_cur" @change="area_change(1)">
                      <option v-for="option in area_province" v-bind:value="option.name" v-cloak>
                        @{{ option.name }}
                      </option>
                    </select>
                </div>

                <div>
                    <select v-model="area_city_cur" @change="area_change(2)">
                      <option v-for="option in area_city" v-bind:value="option.name" v-cloak>
                        @{{ option.name }}
                      </option>
                    </select>
                </div>

                <div>
                    <select v-model="area_county_cur" @change="area_change(3)">
                      <option v-for="option in area_county" v-bind:value="option.name" v-cloak>
                        @{{ option.name }}
                      </option>
                    </select>
                </div>
            </div>

        </div>
        <div class="ipt" v-if="data.type==3">
            <label for="p4">详细地址：</label>
            <input id="p4" type="text" v-model="address" placeholder="请输入详细地址" autocomplete="off">
        </div>
        <div class="ipt">
            <textarea placeholder="请输入备注" v-model="remark" autocomplete="off"></textarea>
        </div>
    </div>
    <div class="payment_order_box">
        <div class="title">商品信息</div>
        <div class="pro1 clearfix">
            <div class="img">
                <img :src="data.pics[0].name|cosPic(100)">
            </div>
            <div class="text" v-cloak>@{{data.name}}</div>
        </div>
        <div class="pro2 clearfix">
            <div class="pro21" v-cloak>@{{curStandards.name}}</div>
            <div class="pro22" v-cloak>￥@{{curStandards.sale_price}}</div>
        </div>
        <div class="pro3 clearfix">
            <div class="pro31">购买数量：</div>
            <span @click="ct(2)">+</span>
            <span class="active" v-cloak>@{{quantity}}</span>
            <span @click="ct(1)">-</span>
        </div>
    </div>
    <div class="payment_order_fl clrarfix">
        <span class="active">&nbsp;&nbsp;&nbsp;小计：@{{total}}</span>
        <span @click="buy()">微信支付</span>
    </div>
</div>

@endsection

@section('javascript')

<script>
var three_area = '{{statics('json/three_area.json')}}';
</script>

<script src="{{statics('js/ints/payment_order.js')}}"></script>

@endsection