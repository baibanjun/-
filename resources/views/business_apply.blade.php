@extends('../layouts.main')

@section('css')
<style>html{background:#fff;}</style>
@endsection

@section('content')

<div id="business_applyApp">

    <div class="business_apply_header">
        <img src="{{statics('images/u1787.png')}}">
    </div>

    <div class="business_apply_posted" v-if="data.uid" v-cloak>
        <p v-if="data.status==0">@{{data.attention.wait_attention}}</p>
        <p v-if="data.status==1">@{{data.attention.pass_attention}}</p>
        <p v-if="data.status==2">@{{data.attention.return_attention}}</p>
    </div>

    <div class="business_apply_post" v-if="!data.uid" v-cloak>
        <ul>
            <li>
                <label for="p1">姓名</label>
                <input  id="p1" type="text" v-model="name" placeholder="请输入姓名">
            </li>
            <li>
                <label for="p2">电话</label>
                <input  id="p2" type="text" v-model="tel" placeholder="请输入手机号码">
            </li>
            <li>
                <label for="p3">行业</label>
                <input  id="p3" type="text" v-model="industry" placeholder="请输入行业类型">
            </li>
            <li>
                <label for="p4">备注</label>
                <input  id="p4" type="text" v-model="remark" placeholder="其他">
            </li>
            <li>
                <button @click="post()">提交</button>
            </li>
        </ul>
    </div>

</div>


@endsection

@section('javascript')

<script src="{{statics('js/ints/business_apply.js')}}"></script>

@endsection