@extends('../admin.layout.main')

@section('content')
<style>
.layui-table tbody tr:hover{ background-color: transparent; }
</style>
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="add_product" v-cloak>
	
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
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.name"></td>
						<td class="b tc">产品类型</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" name="type1" value="1" v-model="req.type"> 吃喝玩乐go产品
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type1" value="3" v-model="req.type"> 联盟商城产品
							</label>
						</td>
					</tr>
					<!--倒计时状态-->
					<tr>
						<td class="b tc" v-if="req.is_countdown==1">是否倒计时</td>
						<td class="tc"  v-if="req.is_countdown==1">
							<label class="radio-inline">
							  	<input type="radio" name="type2" value="1" v-model="req.is_countdown"> 是
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type2" value="0" v-model="req.is_countdown"> 否
							</label>
						</td>
						<td class="b tc" v-if="req.is_countdown!=1"  rowspan="2">是否倒计时</td>
						<td class="tc"  v-if="req.is_countdown!=1"  rowspan="2">
							<label class="radio-inline">
							  	<input type="radio" name="type2" value="1" v-model="req.is_countdown"> 是
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type2" value="0" v-model="req.is_countdown"> 否
							</label>
						</td>
						<td class="b tc" rowspan="2">城市</td>
						<td class="tc" rowspan="2">
							<div class="form-group">
						    	<select class="form-control" v-model="req.city_code">
									<option :value="n.city_code" v-for="n in city_select">@{{n.city_name}}</option>
						    	</select>
						  	</div>
			  			</td>
					</tr>
					<tr>
						<td class="b tc" v-if="req.is_countdown==1">剩余时间</td>
						<td class="tc col-r" v-if="req.is_countdown==1">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.hour" onkeyup="base.IntNum(this)">
						      <div class="input-group-addon">时</div>
						      <input type="text" class="form-control" v-model="req.min" onkeyup="base.IntNum(this)">
						      <div class="input-group-addon">分</div>
						      <input type="text" class="form-control" v-model="req.sec" onkeyup="base.IntNum(this)">
						      <div class="input-group-addon">秒</div>
						   </div>
						</td>
					</tr>

					<tr>
						<td class="b tc">商家名称</td>
						<td class="tc">
							<div class="form-group">
						    	<select class="form-control" v-model="req.business_id" @change="choose_business()">
									<option :value="n.id" v-for="n in business_select">@{{n.name}}</option>
						    	</select>
						  	</div>
						</td>
						<td class="b tc">商家联系电话</td>
						<td class="tc">@{{tel}}</td>
					</tr>
					<tr v-if="req.type==1">
						<td class="b tc" rowspan="2">商家地址</td>
						<td class="tc" rowspan="2">@{{address}}</td>
						<td class="b tc"  v-if="req.send_sms_or_not==1">是否发送预约短信</td>
						<td class="tc"  v-if="req.send_sms_or_not==1">
							<label class="radio-inline">
							  	<input type="radio" name="type3" value="1" v-model="req.send_sms_or_not"> 是
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type3" value="0" v-model="req.send_sms_or_not"> 否
							</label>
						</td>
						<td class="b tc" v-if="req.send_sms_or_not!=1"  rowspan="2">是否发送预约短信</td>
						<td class="tc" v-if="req.send_sms_or_not!=1"  rowspan="2">
							<label class="radio-inline">
							  	<input type="radio" name="type3" value="1" v-model="req.send_sms_or_not"> 是
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type3" value="0" v-model="req.send_sms_or_not"> 否
							</label>
						</td>
					</tr>
					<tr v-if="req.type==1">
						<!--<td class="b tc" v-if="req.send_sms_or_not==1">预约链接</td>
						<td class="tc" v-if="req.send_sms_or_not==1"><input type="text" class="layui-input" v-model.trim="req.booking_information"></td>-->
					</tr>
					<tr v-if="req.type==1">
						<td class="b tc">销售价</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standard[0].sale_price"  onkeyup="base.floatNum(this)"></td>
						<td class="b tc">门市价</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standard[0].price"  onkeyup="base.floatNum(this)"></td>
					</tr>
					<tr v-if="req.type==1">
						<td class="b tc">已售数量</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standard[0].quantity_sold"  onkeyup="base.IntNum(this)"></td>
						<td class="b tc">库存</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standard[0].onhand"  onkeyup="base.IntNum(this)"></td>
					</tr>
					<tr>
						<td class="b tc">一级分销方式</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" name="type4" value="1" v-model="req.distribution[0].type"> 按比例分销
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type4" value="2" v-model="req.distribution[0].type"> 按固定金额分销
							</label>
						</td>
						<td class="b tc" v-if="req.distribution[0].type==1">一级分销比例</td>
						<td class="tc" v-if="req.distribution[0].type==1">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[0].value" onkeyup="base.floatNum(this)" >
						      <div class="input-group-addon">%</div>						      
						   </div>
						</td>
						<td class="b tc" v-if="req.distribution[0].type==2">一级分销金额</td>
						<td class="tc" v-if="req.distribution[0].type==2">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[0].value" onkeyup="base.floatNum(this)" >
						      <div class="input-group-addon">元</div>						      
						   </div>
						</td>
					</tr>
					<tr>
						<td class="b tc">二级分销方式</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" name="type5" value="1" v-model="req.distribution[1].type"> 按比例分销
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type5" value="2" v-model="req.distribution[1].type"> 按固定金额分销
							</label>
						</td>
						<td class="b tc" v-if="req.distribution[1].type==1">二级分销比例</td>
						<td class="tc" v-if="req.distribution[1].type==1">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[1].value" onkeyup="base.floatNum(this)" >
						      <div class="input-group-addon">%</div>						      
						   </div>
						</td>
						<td class="b tc" v-if="req.distribution[1].type==2">二级分销金额</td>
						<td class="tc" v-if="req.distribution[1].type==2">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[1].value" onkeyup="base.floatNum(this)" >
						      <div class="input-group-addon">元</div>						      
						   </div>
						</td>
					</tr>
					<tr>
						<td class="b tc">团队分销方式</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" name="type6" value="1" v-model="req.distribution[2].type"> 按比例分销
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type6" value="2" v-model="req.distribution[2].type"> 按固定金额分销
							</label>
						</td>

						<td class="b tc" v-if="req.distribution[2].type==1">团队分销比例</td>
						<td class="tc" v-if="req.distribution[2].type==1">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[2].value" onkeyup="base.floatNum(this)">
						      <div class="input-group-addon">%</div>						      
						   </div>
						</td>

						<td class="b tc" v-if="req.distribution[2].type==2">团队分销金额</td>
						<td class="tc" v-if="req.distribution[2].type==2">
							<div class="input-group">						      
						      <input type="text" class="form-control" v-model="req.distribution[2].value" onkeyup="base.floatNum(this)" >
						      <div class="input-group-addon">元</div>						      
						   </div>
						</td>
					</tr>
					<tr>
						<td class="b tc">产品标题</td>
						<td class="tc" colspan="3">
							<input type="text" class="layui-input" v-model="req.subtitle">
						</td>
					</tr>
					<tr>
						<td class="b tc">产品分享海报<br><span class="col-r t12">(尺寸750*1334)</span></td>
						<td colspan="3">
							<p><a class="layui-btn layui-btn-sm layui-btn-normal" @click="file_post(1)">上传海报</a></p>
							<div class="del-img-box" v-for="n in req.poster">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="90" height="160">
								<div @click="deleteImg(1)" class="del-img-x"><i class="layui-icon layui-icon-close"></i></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b tc">产品轮播图<br><span class="col-r t12">(尺寸比例2:1)</span></td>
						<td colspan="3">
							<p><a class="layui-btn layui-btn-sm layui-btn-normal" @click="file_post(2)">上传轮播图</a></p>
							<div class="del-img-box" v-for="(n,index) in req.pics">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="120" height="60">
								<div @click="deleteImg(2,index)" class="del-img-x"><i class="layui-icon layui-icon-close"></i></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b tc">产品详细信息</td>
						<td colspan="3">
							<div id="contentEditor"></div>
						</td>
					</tr>
				</tbody>					
			</table>
			<div class="mg20"  v-if="req.type==3">
				<p><span class="col-b t18">产品规格信息</span></p>
				<table class="layui-table" v-for="(n,index) in req.standards">
					<colgroup>
						<col width="20%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td class="b tc">
							<button class="layui-btn layui-btn-sm layui-btn-danger" style="position: absolute;left: 0;top: 0" v-if="index!=0" @click="del_standard(index)">
								<i class="layui-icon layui-icon-delete"></i>
							</button>
							规格名称
						</td>
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.standards[index].name"></td>
						<td class="b tc">销售价</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standards[index].sale_price"  onkeyup="base.floatNum(this)"></td>
					</tr>
					<tr>
						<td class="b tc">门市价</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standards[index].price"  onkeyup="base.floatNum(this)"></td>
						<td class="b tc">已售数量</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standards[index].quantity_sold" onkeyup="base.IntNum(this)"></td>
					</tr>
					<tr>
						<td class="b tc">库存</td>
						<td class="tc"><input type="text" class="layui-input" v-model="req.standards[index].onhand" onkeyup="base.IntNum(this)"></td>
						<td class="b tc"></td>
						<td class="tc"></td>
					</tr>
				</table>
				<p><button class="layui-btn layui-btn-sm" @click="add_standard()">添加产品规格+</button></p>
			</div>
			
			<!--商品属性-->
				
			<!--提交-->
			<p class="mg20 tc">
				<button class="layui-btn layui-btn-normal" @click="post()">提交上架</button>
				<button class="layui-btn layui-btn-primary" @click="close()">取消</button>
			</p>	
		</div>

	</div>
</div>
	
@endsection

@section('javascript')
<script src="{{statics('js/wangEditor-3.1.1/release/wangEditor.min.js')}}"></script>
<script src="{{statics('js/admin/add_product.js')}}"></script>
<script type="text/javascript">
	var E = window.wangEditor;
   var editor = new E('#contentEditor');
   //重置图片上传功能
   editor.customConfig.customUploadImg = function (files, insert) {
		 // files 是 input 中选中的文件列表
		 // insert 是获取图片 url 后，插入到编辑器的方法

		 // 上传代码返回结果之后，将图片插入到编辑器中

         if (window.FileReader) {
            var f_name = ['png','jpg','jpeg']; 	           
         	if(f_name.indexOf(files[0].name.split('.')[1])=='-1'){
         		base.layer.msg('上传图片类型为jpg或者png');
              	return false;
            }
           	if(files[0].size>2048*1000){
           		base.layer.msg('文件大小不能超过2m');
               return false;
           	} 
            var fd = new FormData();
            fd.append("file",files[0]);
            //加密
            var $data = {
	            random: base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
	            timestamp: Date.parse(new Date()) / 1000
	        	};
            var encrypt = new JSEncrypt();
     			encrypt.setPublicKey();
     			var encryptData = encrypt.encrypt(JSON.stringify($data));

            $.ajax({
               url: WEB_CONFIG.API_URL + 'admin/upload',
               type: "POST",
               processData: false,
               contentType: false,
               async: false,
               headers: {
               	'X-Requested-With': 'XMLHttpRequest',
             		sign: encryptData,
             		random: $data.random,
             		timestamp: $data.timestamp,
                   token: $.cookie('chwlToken')
               },
               data:fd,
               xhr: function () {
                  var xhr = $.ajaxSettings.xhr();
                  return xhr;
               },
               success: function (data) {
                 	if(data.code=='0000'){
                 		insert(WEB_CONFIG.PIC_URL+data.data.name);             		
                 	}
                 	else if(data.code=='1004'){
                   	window.location.href =WEB_CONFIG.WEB_URL + 'login';
                 	}else{
                 		layer.msg('上传失败');
                 	}                          
               }
           	})
         }else {
            
         }
         // return false;
	}
   
	//构建editor	
	editor.create();
	//设置富文本内容
	editor.txt.html("");
</script>
@endsection