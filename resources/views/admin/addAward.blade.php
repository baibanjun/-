@extends('../admin.layout.main')

@section('content')
<style>
.layui-table tbody tr:hover{ background-color: transparent; }
</style>
<div class="layui-body layui-tab-content site-demo-body">
	<div class="info_box" id="add_award" v-cloak>
	
		<div class="pl30 mg10" style="width: 90%">
			<p><span class="col-b t18">抽奖信息</span></p>
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
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.title"></td>
						<td class="b tc">抽奖类型</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" name="type1" value="1" v-model="req.lottery_type" @change="choose_type($event)"> 九宫格抽奖
							</label>
							<label class="radio-inline">
							  	<input type="radio" name="type1" value="2" v-model="req.lottery_type" @change="choose_type($event)"> 圆盘抽奖
							</label>
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
					<tr>
						<td class="b tc">商家地址</td>
						<td class="tc">@{{address}}</td>
						<td class="b tc"></td>
						<td class="tc"></td>
					</tr>

					<tr>
						<td class="b tc">活动海报<br><span class="col-r t12">(尺寸750*1334)</span></td>
						<td colspan="3">
							<p><a class="layui-btn layui-btn-sm layui-btn-normal" @click="file_post(1)">上传海报</a></p>
							<div class="del-img-box" v-for="n in req.poster">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="90" height="160">
								<div @click="deleteImg(1)" class="del-img-x"><i class="layui-icon layui-icon-close"></i></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b tc">活动说明</td>
						<td class="tc" colspan="3">
							<input type="text" class="layui-input" v-model="req.description">
						</td>
					</tr>
					<tr>
						<td class="b tc">商家介绍</td>
						<td colspan="3">
							<div id="contentEditor"></div>
						</td>
					</tr>
				</tbody>					
			</table>
			<div class="mg20">
				<p><span class="col-b t18">奖品信息</span></p>
				<table class="layui-table" v-for="(n,index) in req.draw_data" style="margin-top: 30px;">
					<colgroup>
						<col width="15%">
						<col width="35%">
						<col width="15%">
						<col width="35%">
					</colgroup>
					<tr>
						<td class="b tc col-g">奖品名称</td>
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.draw_data[index].name"></td>
						<td class="b tc">奖品类型</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" :name="'draw_type_'+index" value="1" v-model="req.draw_data[index].draw_type"> 优惠券
							</label>
							<label class="radio-inline">
							  	<input type="radio" :name="'draw_type_'+index" value="2" v-model="req.draw_data[index].draw_type"> 谢谢参与
							</label>
						</td>
					</tr>
					<tr>
						<td class="b tc">奖品库存</td>
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.draw_data[index].inventory"></td>
						<td class="b tc">中奖概率</td>
						<td class="tc"><span class="layui-col-md10"><input type="text" class="layui-input" v-model.trim="req.draw_data[index].probability"></span><span class="layui-col-md2 lh2 t16">%</span></td>
					</tr>
					<tr v-show="req.draw_data[index].draw_type==1">
						<td class="b tc">奖品有效期</td>
						<td class="tc">
							<input type="text" class="layui-input" style="width: 45%;display: inline-block;" :id="'start_time_'+index" placeholder="开始时间" > --
							<input type="text" class="layui-input" style="width: 45%;display: inline-block;" :id="'end_time_'+index" placeholder="结束时间">
						</td>
						<td class="b tc">使用条件</td>
						<td class="tc"><input type="text" class="layui-input" v-model.trim="req.draw_data[index].use_condition"></td>
					</tr>
					<tr>
						<td class="b tc">此奖品被抽完是否自动将此抽奖活动下线</td>
						<td class="tc">
							<label class="radio-inline">
							  	<input type="radio" :name="'hide_type_'+index" value="1" v-model="req.draw_data[index].is_auto_hidden"> 是
							</label>
							<label class="radio-inline">
							  	<input type="radio" :name="'hide_type_'+index" value="0" v-model="req.draw_data[index].is_auto_hidden"> 否
							</label>
						</td>
						<td class="b tc"></td>
						<td class="tc"></td>
					</tr>
					<tr v-show="q_is_pic_show">
						<td class="b tc">奖品图片<br></td>
						<td colspan="3">
							<p><a class="layui-btn layui-btn-sm layui-btn-normal" @click="file_post(2,index)">上传图片</a></p>
							<div class="del-img-box" v-for="n in req.draw_data[index].pic">
								<img :src="WEB_CONFIG.PIC_URL+n.name" width="90" height="90">
							</div>
						</td>
					</tr>
					<tr v-if="req.draw_data[index].draw_type==1">
						<td class="b tc">奖品说明</td>
						<td class="tc" colspan="3">
							<input type="text" class="layui-input" v-model="req.draw_data[index].description">
						</td>
					</tr>
				</table>
			</div>
				
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
<script src="{{statics('js/admin/add_award.js')}}"></script>
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