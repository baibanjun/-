new Vue({
	el: '#edit_product',
	data: {
		req:{
			id:'',
			name:'',
			type:1,
			is_countdown:1,//是否倒计时
			hour:0,
			min:0,
			sec:0,
			time_limit:'',
			city_code:'110100000',
			business_id:null,
			booking_information:'',
			send_sms_or_not:0,//是否短信
			poster:[],//海报
			pics:[],//产品轮播
			subtitle:'',//副标题
			content:'',//内容
			distribution:[{ //分销信息
				id:null,
				class_type:1,
				type:1,
				value:0
			},{
				id:null,
				class_type:2,
				type:1,
				value:0
			},{
				id:null,
				class_type:3,
				type:1,
				value:0
			}],
			standard:[{  //go商品规格
				id:null,
				name:'',
				sale_price:'',
				price:'',
				quantity_sold:'',
				onhand:''
			}],
			standards:[
				{
					name:'',
					sale_price:'',
					price:'',
					quantity_sold:'',
					onhand:''
				}
			]
		},
		address:'',
		tel:'',
		business_select:[],
		city_select:[]
	},                                                            
	created: function () {
	  	var _self = this;
	  	var _info = JSON.parse(window.localStorage.product_info);
	  	_self.req.id = _info.id;
	  	_self.req.type = _info.type;
	  	_self.req.is_countdown = _info.is_countdown;
	  	_self.req.name = _info.name;
	  	_self.req.business_id = _info.business_id;
	  	_self.req.booking_information = _info.booking_information;
	  	_self.req.city_code = _info.city_code;
	  	_self.req.send_sms_or_not = _info.send_sms_or_not;
	  	_self.req.subtitle = _info.subtitle;
	  	_self.req.pics = _info.pics;
	  	_self.req.poster = _info.poster;
	  	//一级分销
	  	_self.req.distribution[0].id = _info.primaryd_distribution.id;
	  	_self.req.distribution[0].class_type = _info.primaryd_distribution.class_type;
	  	_self.req.distribution[0].type = _info.primaryd_distribution.type;
	  	if(_self.req.distribution[0].type==1){
	  		_self.req.distribution[0].value = _info.primaryd_distribution.value*100;
	  	}else{
	  		_self.req.distribution[0].value = _info.primaryd_distribution.value;
	  	}	
	  	//二级分销
	  	_self.req.distribution[1].id = _info.secondary_distribution.id;
	  	_self.req.distribution[1].class_type = _info.secondary_distribution.class_type;
	  	_self.req.distribution[1].type = _info.secondary_distribution.type;
	  	if(_self.req.distribution[1].type==1){
	  		_self.req.distribution[1].value = _info.secondary_distribution.value*100;
	  	}else{
	  		_self.req.distribution[1].value = _info.secondary_distribution.value;
	  	}
	  	//团队分销
	  	_self.req.distribution[2].id = _info.team_distribution.id;
	  	_self.req.distribution[2].class_type = _info.team_distribution.class_type;
	  	_self.req.distribution[2].type = _info.team_distribution.type;
	  	if(_self.req.distribution[2].type==1){
	  		_self.req.distribution[2].value = _info.team_distribution.value*100;
	  	}else{
	  		_self.req.distribution[2].value = _info.team_distribution.value;
	  	}

	  	_self.tel = _info.business.tel;
	  	_self.address = _info.business.address;
	  	if(_info.type==1){
	  		_self.req.standard = _info.standards;
	  	}else{
	  		_self.req.standards = _info.standards;
	  	}
	  	if(_info.is_countdown==1){
	  		var _limit = Date.parse(new Date())/1000 - Date.parse(_info.updated_at)/1000;
	  		var _s = _info.time_limit - _limit;
	  		if(_s<=0){
	  			_self.req.hour=0;
	  			_self.req.min=0;
	  			_self.req.sec=0;
	  			_self.req.is_countdown = 0;
	  		}else{
	  			_self.req.hour=Math.floor(_s/60/60);
	  			_self.req.min=Math.floor(_s/60%60);
	  			_self.req.sec=Math.floor(_s%60);
	  		}
	  	}

	  	//商家下拉
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/business_select',
	      data:{

	      }
	 	},function(data){
	      _self.business_select = data;
	  	},function(data){

	  	});
	  	//城市下拉
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/product_city',
	      data:{

	      }
	 	},function(data){
	      _self.city_select = data;
	  	},function(data){

	  	});
	  	//
	  	var time = setTimeout(function(){
	  		//设置富文本内容
	  		editor.txt.html(_info.content);
		},1000)
	},
	mounted:function(){
		_self = this;
		
		       
	},
	methods:{
	  	post:function(){
	  		var _self = this;
	  		_self.req.content = editor.txt.html();
	  		if(_self.req.name==''){
	  			layer.msg('请填写产品名称');
	  			return false;
	  		}
	  		if(_self.req.subtitle==''){
	  			layer.msg('请填写产品副标题');
	  			return false;
	  		}
	  		// if(_self.req.send_sms_or_not==1){
	  		// 	if(_self.req.booking_information==""){
	  		// 		layer.msg('请填写预约链接');
	  		// 		return false;
	  		// 	}
	  		// }
	  		if(_self.req.poster.length==0){
	  			layer.msg('请上传海报');
	  			return false;
	  		}
	  		if(_self.req.pics.length==0){
	  			layer.msg('请上传轮播图');
	  			return false;
	  		}
	  		if(!Number(_self.req.distribution[0].value)&&_self.req.distribution[0].value!=0){
	  			layer.msg('请正确填写一级分销值');
	  			return false;
	  		}
	  		if(!Number(_self.req.distribution[1].value)&&_self.req.distribution[0].value!=0){
	  			layer.msg('请正确填写二级分销值');
	  			return false;
	  		}
	  		if(!Number(_self.req.distribution[2].value)&&_self.req.distribution[0].value!=0){
	  			layer.msg('请正确填写团队分销值');
	  			return false;
	  		}
	  		if(_self.req.is_countdown == 1){
	  			if(_self.req.hour==0&&_self.req.min==0&&_self.req.sec==0){
	  				layer.msg('请正确倒计时时间');
	  				return false;
	  			}
	  			if(_self.req.min>59||_self.req.sec>59){
	  				layer.msg('请正确倒计时时间');
	  				return false;
	  			}
	  		}
	  		if(_self.req.type == 1){
	  			if(!Number(_self.req.standard[0].sale_price)){
	  				layer.msg('请正确填写销售价');
	  				return false;
	  			}
	  			if(!Number(_self.req.standard[0].price)){
	  				layer.msg('请正确填写门市价');
	  				return false;
	  			}
	  			if(!Number(_self.req.standard[0].quantity_sold)&&_self.req.standard[0].quantity_sold!=0){
	  				layer.msg('请正确填写已售数量');
	  				return false;
	  			}
	  			if(_self.req.standard[0].quantity_sold>1000000){
	  				layer.msg('请正确填写已售数量');
	  				return false;
	  			}
	  			if(!Number(_self.req.standard[0].onhand)&&_self.req.standard[0].onhand!=0){
	  				layer.msg('请正确填写库存');
	  				return false;
	  			}
	  			if(_self.req.standard[0].onhand>1000000){
	  				layer.msg('请正确填写库存');
	  				return false;
	  			}
	  		}else{
	  			$.each(_self.req.standards,function(i,n){
	  				if(n.name==""){
	  					layer.msg('请填写商品规格名称');
		  				return false;
	  				}
	  				if(!Number(n.sale_price)){
		  				layer.msg('请正确填写销售价');
		  				return false;
		  			}
		  			if(!Number(n.price)){
		  				layer.msg('请正确填写门市价');
		  				return false;
		  			}
		  			if(!Number(n.quantity_sold)&&n.quantity_sold!=0){
		  				layer.msg('请正确填写已售数量');
		  				return false;
		  			}
		  			if(n.quantity_sold>1000000){
		  				layer.msg('请正确填写已售数量');
		  				return false;
		  			}
		  			if(!Number(n.onhand)&&n.onhand!=0){
		  				layer.msg('请正确填写库存');
		  				return false;
		  			}
		  			if(n.onhand>1000000){
		  				layer.msg('请正确填写库存');
		  				return false;
		  			}
	  			})
	  		}
	  		var _can = true;
	  		layer.confirm('确定修改并上架？', function(index){
	  			if(_can){
	  				_can = false;
		  			layer.close(index);
		  			var _req = JSON.parse(JSON.stringify(_self.req));
		  			if(_self.req.type==1){
		  				_req.standard[0].name = _req.name;
		  			}else{
		  				_req.standard = _req.standards;
		  			}
		  			if(_req.is_countdown==1){
		  				_req.time_limit = _req.hour+':'+_req.min+':'+_req.sec;
		  			}	  			
		  			base.ajax({
				      type:'put',
				      url:WEB_CONFIG.API_URL + 'admin/product/'+_req.id,
				      data:_req
				 	},function(data){
				      window.location.href =WEB_CONFIG.WEB_URL + 'productList';
				  	},function(data){

				  	});
	  			}
	  		})
	  	},
	  	file_post:function(_type){
	      var _self = this;
	      if(_type==2&&_self.req.pics.length==6){
	      	base.layer.msg('轮播图数量不超过6个');
	      	return false;
	      }
	      $('body').append('<input id="ftx-file" type="file" style="display:none;"/>');
	      $('#ftx-file').off('change').on('change', function (e) {
	         //支持 FileReader
	         if (window.FileReader) {
	            var file=document.querySelector('input[type=file]').files[0];
	            var f_name = ['png','jpg','jpeg']; 	           
            	if(f_name.indexOf(file.name.split('.')[1])=='-1'){
            		base.layer.msg('上传图片类型为jpg或者png');
                 	return false;
               }
	           	if(file.size>2048*1000){
	           		base.layer.msg('文件大小不能超过2m');
	               return false;
	           	} 
	            var fd = new FormData();
	            fd.append("file",file);
	            //加密
	            var $data = {
		            random: base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
		            timestamp: Date.parse(new Date()) / 1000
		        	};
	            var encrypt = new JSEncrypt();
        			encrypt.setPublicKey();
        			var encryptData = encrypt.encrypt(JSON.stringify($data));
        			//加载图标
        			var _index = layer.load(0,{ shade: [0.3, '#ccc'] });

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
	               // 'Content-Type': 'application/json'
	               },
	               data:fd,
	               xhr: function () {
	                  var xhr = $.ajaxSettings.xhr();
	                  return xhr;
	               },
	               success: function (data) {
	               	layer.close(_index);
	                 	if(data.code=='0000'){                 		
	                 		if(_type==1){
	                 			//限制尺寸
	                 			if(data.data.width!=750||data.data.height!=1334){
                 					layer.msg('分享海报的尺寸不符合要求');
                 					return false;
                 				}
	                 			if(_self.req.poster.length==0){	                 				
	                 				layer.msg('上传成功');
	                 				_self.req.poster.push(data.data);
	                 			}else{	                 				
	                 				layer.msg('上传成功');
	                 				_self.req.poster = [];
	                 				_self.req.poster.push(data.data);
	                 			}
	                 		}
	                 		else{
	                 			if((data.data.width/data.data.height)!=2){
                 					layer.msg('产品轮播图比例应为2:1');
                 					return false;
                 				}
	                 			_self.req.pics.push(data.data);
	                 		}
	                 	}
	                 	else if(data.code=='1004'){
	                   	window.location.href =WEB_CONFIG.WEB_URL + 'login';
	                 	}else{
	                 		layer.close(_index);
	                 		layer.msg('上传失败');
	                 	}                          
	               }
	           	})
	         }else {
	            $('#ftx-file').empty().remove();
	            // layer.msg('上传失败，');
	         }
	         return false;
	      });
	      setTimeout(function () {
	          $('#ftx-file').click();
	      }, 0);          
	  	},
	  	deleteImg:function(_type,_index){
	  		var _self = this;
	  		if(_type==1){
	  			_self.req.poster = [];
	  		}else{
	  			_self.req.pics.splice(_index,1);
	  		}
	  	},
	  	close:function(){
	  		layer.confirm('确定要放弃修改并返回？', function(index){
	  			window.location.href =WEB_CONFIG.WEB_URL + 'productList';
	  		})
	  	},
	  	choose_business:function(){
	  		var _self = this;
	  		$.each(_self.business_select,function(i,n){
	  			if(_self.req.business_id == n.id){
	  				_self.address = n.address;
	  				_self.tel = n.tel;
	  			}
	  		})
	  	},
	  	add_standard:function(){
	  		var _self = this;
	  		_self.req.standards.push({
	  			name:'',
				sale_price:'',
				price:'',
				quantity_sold:'',
				onhand:''
	  		})
	  	},
	  	del_standard:function(_index){
	  		var _self = this;
	  		_self.req.standards.splice(_index,1);
	  	}

	}//methods

});