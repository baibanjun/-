@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="award_detail" v-cloak>
		<p class="pl30 mg20">
			<button class="layui-btn layui-btn-normal" v-if="info.status==2" @click="conceal(info.id)">隐藏</button>
			<button class="layui-btn" v-if="info.status==1" @click="putaway(info.id)">重新上架</button>			
			<button class="layui-btn layui-btn-normal" v-if="info.status==1" @click="edit_award(info.id)">修改</button>
			<button class="layui-btn layui-btn-normal" v-if="info.status==1" @click="del(info.id)">删除</button>
			<button class="layui-btn layui-btn-primary" @click="to_award_list()"><<返回</button>
		</p>
		<div class="pl30 mg10" style="width: 90%">
			<table class="layui-table">
				<colgroup>
					<col width="20%">
					<col width="30%">
					<col width="20%">
					<col width="30%">
				</colgroup>
				<tbody>
					<tr>
						<td class="b tc">抽奖标题</td>
						<td class="tc">@{{info.title}}</td>
						<td class="b tc">状态</td>
						<td class="tc">@{{info.status==1?'已隐藏':'正常'}}</td>
					</tr>
					<tr>
						<td class="b tc">抽奖类型</td>
						<td class="tc">@{{info.lottery_type==1?'九宫格抽奖':'圆盘抽奖'}}</td>
						<td class="b tc">商家名称</td>
						<td class="tc">@{{info.business.name}}</td>
					</tr>
					<tr>
						<td class="b tc">商家联系电话</td>
						<td class="tc">@{{info.business.tel}}</td>
						<td class="b tc">商家地址</td>
						<td class="tc">@{{info.business.address}}</td>
					</tr>
					<tr>
						<td class="b tc">活动海报</td>
						<td class="tc" colspan="3">
							<div class="del-img-box" v-for="n in info.poster">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="90" height="160">
								<!-- <button @click="show_img()">预览海报</button> -->
							</div>
						</td>
					</tr>
					<tr>
						<td class="b tc">活动说明</td>
						<td class="tc" colspan="3">@{{info.description}}</td>
					</tr>
					<tr>
						<td class="b tc">商家介绍信息</td>
						<td class="tc" colspan="3" v-html="info.business_introduce"></td>
					</tr>
				</tbody>
					
			</table>

			<div class="mg20">
				<p><span class="col-b t18">奖品信息</span></p>
				<table class="layui-table" v-for="n in info.lottery_draw_list" style="margin-top: 30px;">
					<colgroup>
						<col width="20%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td class="b tc col-g">奖品名称</td>
						<td class="tc">@{{n.name}}</td>
						<td class="b tc">奖品类型</td>
						<td class="tc">@{{n.draw_type==1?'优惠券':'谢谢惠顾'}}</td>
					</tr>
					<tr>
						<td class="b tc">已发放数量</td>
						<td class="tc">@{{n.has_send_num}}</td>
						<td class="b tc">奖品库存</td>
						<td class="tc">@{{n.inventory}}</td>
					</tr>
					<tr>
						<td class="b tc">中奖概率</td>
						<td class="tc">@{{n.probability*100}}%</td>
						<td class="b tc">此奖品被抽完是否自动将此抽奖活动下线</td>
						<td class="tc">@{{n.is_auto_hidden==1?'是':'否'}}</td>						
					</tr>
					<tr v-if="n.draw_type==1">
						<td class="b tc">奖品有效期</td>
						<td class="tc">@{{n.start_date}}至@{{n.end_date}}</td>
						<td class="b tc">使用条件</td>
						<td class="tc">@{{n.use_condition}}</td>						
					</tr>
					<tr v-if="info.lottery_type!=2">
						<td class="b tc">奖品图片</td>
						<td class="tc">
							<div class="del-img-box">
								<img :src="WEB_CONFIG.PIC_URL+n.pic[0].name" width="90" height="90">
							</div>
						</td>
						<td class="b tc" v-if="n.draw_type==2"></td>
						<td class="tc" v-if="n.draw_type==2"></td>
						<td class="b tc" v-if="n.draw_type==1">奖品说明</td>
						<td class="tc" v-if="n.draw_type==1">@{{n.description}}</td>
					</tr>
				</table>
			</div>
			<!--规格-->
		</div>
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/award_detail.js')}}"></script>
@endsection