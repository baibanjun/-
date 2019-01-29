@extends('../admin.layout.main')

@section('content')
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="product_detail" v-cloak>
		<p class="pl30 mg20">
			<button class="layui-btn" v-if="info.status!=2&&info.status!=3" @click="sold_out(info.id)">下架</button>
			<button class="layui-btn" v-if="info.status==2||info.status==3" @click="putaway(info.id)">上架</button>
			<button class="layui-btn layui-btn-normal" v-if="info.status!=3" @click="conceal(info.id)">隐藏</button>
			<button class="layui-btn layui-btn-normal" v-if="info.status==3" @click="edit_product()">修改</button>
			<button class="layui-btn layui-btn-normal" v-if="info.status==3" @click="del(info.id)">删除</button>
			<button class="layui-btn layui-btn-primary" @click="to_product_list()"><<返回</button>
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
						<td class="b tc">产品名称</td>
						<td class="tc">@{{info.name}}</td>
						<td class="b tc">产品类型</td>
						<td class="tc">@{{info.type==1?'吃喝玩乐go产品':'联盟商城产品'}}</td>
					</tr>
					<!--其他状态-->
					<tr  v-if="info.status!=5">
						<td class="b tc">状态</td>
						<td class="tc">@{{info.status==2?'已下架':info.status==3?'已隐藏':info.status==4?'已售罄':'抢购中'}}</td>
						<td class="b tc">城市</td>
						<td class="tc">@{{info.sys_area.name}}</td>
					</tr>
					<!--倒计时状态-->
					<tr v-if="info.status==5">
						<td class="b tc">状态</td>
						<td class="tc">倒计时</td>
						<td class="b tc" rowspan="2">城市</td>
						<td class="tc" rowspan="2">@{{info.sys_area.name}}</td>
					</tr>
					<tr v-if="info.status==5">
						<td class="b tc">剩余时间</td>
						<td class="tc col-r">@{{time}}</td>
					</tr>

					<tr>
						<td class="b tc">商家名称</td>
						<td class="tc">@{{info.business.name}}</td>
						<td class="b tc">商家联系电话</td>
						<td class="tc">@{{info.business.tel}}</td>
					</tr>
					<!--无预约短信-->
					<tr v-if="info.type==1&&info.send_sms_or_not==0">
						<td class="b tc">商家地址</td>
						<td class="tc">@{{info.business.address}}</td>
						<td class="b tc">是否发送预约短信</td>
						<td class="tc">@{{info.send_sms_or_not==1?'发送':'不发送'}}</td>
					</tr>
					<!--有预约短信-->
					<tr v-if="info.type==1&&info.send_sms_or_not==1">
						<td class="b tc" rowspan="2">商家地址</td>
						<td class="tc" rowspan="2">@{{info.business.address}}</td>
						<td class="b tc">是否发送预约短信</td>
						<td class="tc">@{{info.send_sms_or_not==1?'发送':'不发送'}}</td>
					</tr>
					<tr v-if="info.type==1&&info.send_sms_or_not==1">
						<!--<td class="b tc">预约链接</td>
						<td class="tc">@{{info.booking_information}}</td>-->
					</tr>
					
					<tr v-if="info.type==1">
						<td class="b tc">销售价</td>
						<td class="tc">@{{info.standards[0].sale_price}}</td>
						<td class="b tc">门市价</td>
						<td class="tc">@{{info.standards[0].price}}</td>
					</tr>
					<tr v-if="info.type==1">
						<td class="b tc">已售数量</td>
						<td class="tc">@{{info.standards[0].quantity_sold}}</td>
						<td class="b tc">库存</td>
						<td class="tc">@{{info.standards[0].onhand}}</td>
					</tr>
					<tr>
						<td class="b tc">一级分销方式</td>
						<td class="tc">@{{info.primaryd_distribution.type==1?'按比例':'按固定金额'}}</td>
						<td class="b tc" v-if="info.primaryd_distribution.type==1">一级分销比例</td>
						<td class="tc" v-if="info.primaryd_distribution.type==1">@{{info.primaryd_distribution.value*100}}%</td>
						<td class="b tc" v-if="info.primaryd_distribution.type==2">一级分销金额</td>
						<td class="tc" v-if="info.primaryd_distribution.type==2">@{{info.primaryd_distribution.value}}元</td>
					</tr>
					<tr>
						<td class="b tc">二级分销方式</td>
						<td class="tc">@{{info.secondary_distribution.type==1?'按比例':'按固定金额'}}</td>
						<td class="b tc" v-if="info.secondary_distribution.type==1">二级分销比例</td>
						<td class="tc" v-if="info.secondary_distribution.type==1">@{{info.secondary_distribution.value*100}}%</td>
						<td class="b tc" v-if="info.secondary_distribution.type==2">二级分销金额</td>
						<td class="tc" v-if="info.secondary_distribution.type==2">@{{info.secondary_distribution.value}}元</td>
					</tr>
					<tr>
						<td class="b tc">团队分销方式</td>
						<td class="tc">@{{info.team_distribution.type==1?'按比例':'按固定金额'}}</td>
						<td class="b tc" v-if="info.team_distribution.type==1">团队分销比例</td>
						<td class="tc" v-if="info.team_distribution.type==1">@{{info.team_distribution.value*100}}%</td>
						<td class="b tc" v-if="info.team_distribution.type==2">团队分销金额</td>
						<td class="tc" v-if="info.team_distribution.type==2">@{{info.team_distribution.value}}元</td>
					</tr>
					<tr>
						<td class="b tc">产品标题</td>
						<td class="tc" colspan="3" v-html="info.subtitle">
							
						</td>
					</tr>
					<tr>
						<td class="b tc">产品分享海报</td>
						<td class="tc" colspan="3">
							<div class="del-img-box" v-for="n in info.poster">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="90" height="160">
								<button @click="show_img()">预览海报</button>
							</div>

						</td>
					</tr>
					<tr>
						<td class="b tc">产品轮播图</td>
						<td class="tc" colspan="3">
							<div class="del-img-box" v-for="n in info.pics">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="120" height="60">
							</div>
						</td>
					</tr>
					<tr>
						<td class="b tc">产品详细信息</td>
						<td class="tc" colspan="3" v-html="info.content"></td>
					</tr>
				</tbody>
					
			</table>

			<div class="mg20" v-if="info.type==3">
				<p><span class="col-b t18">产品规格信息</span></p>
				<table class="layui-table" v-for="(n,index) in info.standards">
					<colgroup>
						<col width="20%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td class="b tc">
							规格名称
						</td>
						<td class="tc col-g">@{{info.standards[index].name}}</td>
						<td class="b tc">销售价</td>
						<td class="tc">@{{info.standards[index].sale_price}}</td>
					</tr>
					<tr>
						<td class="b tc">门市价</td>
						<td class="tc">@{{info.standards[index].price}}</td>
						<td class="b tc">已售数量</td>
						<td class="tc">@{{info.standards[index].quantity_sold}}</td>
					</tr>
					<tr>
						<td class="b tc">库存</td>
						<td class="tc">@{{info.standards[index].onhand}}</td>
						<td class="b tc"></td>
						<td class="tc"></td>
					</tr>
				</table>
			</div>
			<!--规格-->
		</div>
	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/admin/product_detail.js')}}"></script>
@endsection