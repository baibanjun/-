new Vue({
	el: '#experts_list',
	data: {
		list:[],
		status:'all',
		nickname:'',
		name:'',
		mobile:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/user_talent',
	      data:{
	      	status:'all',
	      	limit:10
	      }
	 	},function(res){
	      _self.list = res.data;
	      layui.use('laypage', function(){
            var page = layui.laypage;
            page.render({
					elem: 'pages',
					count: res.last_page,
					curr:res.current_page,
					limit:1,
					jump: function(obj,first){
					  	if(!first){
					      _self.get_list(obj.curr,false);
					  	}
					}
            });
        	});
	  	},function(res){

	  	});

	},
	methods:{
	  	get_list:function(num,search){
	      var _self = this;
	      base.ajax({
	         type:'get',
	         url:WEB_CONFIG.API_URL+ 'admin/user_talent',
	         data:{
					nickname:_self.nickname,
					name:_self.name,
					status:_self.status,
					mobile:_self.mobile,
					page:num,
					limit:10
	         }
	      },function(res){
	         _self.list = res.data;
	         if(search){
	            layui.use('laypage', function(){
                  var page = layui.laypage;
                  page.render({
							elem: 'pages',
							count: res.last_page,
							curr:res.current_page,
							limit:1,
							jump: function(obj,first){
							  	if(!first){
							      _self.get_list(obj.curr,false);
							  	}
							}
                  });
              	}); 
	         }
	      },function(res){

	      });          
	  	},
	  	freeze:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确定要冻结该用户？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/user_talent/'+_id,
			      data:{
			      	status:2
			      }
			 	},function(res){
			      _self.list[_index].status = 2;
			      layer.close(index);
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	unfreeze:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确定要解冻该用户？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/user_talent/'+_id,
			      data:{
			      	status:1
			      }
			 	},function(res){
			      _self.list[_index].status = 1;
			      layer.close(index);
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	show_code:function(){
	  		var _self = this;
	  		var $data = {
            random: base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
            timestamp: Date.parse(new Date()) / 1000
        	};

        	var encrypt = new JSEncrypt();
        	encrypt.setPublicKey();
        	var encryptData = encrypt.encrypt(JSON.stringify($data));
        	var index = layer.load(0,{ shade: [0.3, '#ccc'] });
		  	$.ajax({
            type:'get',
            url: WEB_CONFIG.API_URL + 'admin/user_talent_qrcode',
            data:{},
            dataType: 'json',
            headers: {
            	'X-Requested-With': 'XMLHttpRequest',
               sign: encryptData,
               random: $data.random,
               timestamp: $data.timestamp,
               token: $.cookie('chwlToken')
            },
            complete:function (data) {
            	layer.close(index);
               layer.open({
               	title:'达人注册二维码',
			      	content:'<div style="text-align:center">'+
			      	'<img src="'+data.responseText+'"/><br><br>',
			      	btn:['下载二维码','取消'],
			      	yes:function(index){
			      		layer.close(index);
			      		window.open(WEB_CONFIG.API_URL + 'admin/user_talent_qrcode?type=download');
			      	}
			      })
            }
        	})

	  	},
	  	//导出
	  	out_list:function(){
	  		var _self = this;
	  		layer.confirm('确定按当前搜索项进行导出？', function(index){
	  			window.open(
	  				WEB_CONFIG.API_URL
	  				+'admin/export_user_talent?token='+$.cookie('chwlToken')
	  				+'&&nickname='+_self.nickname
					+'&&name='+_self.name
					+'&&mobile='+_self.mobile
					+'&&status='+_self.status
	  			)
	  			layer.close(index);
	  		})
	  	}

	}//methods

});