new Vue({
	el: '#business_apply_record',
	data: {
		list:[],
		name:'',
		tel:'',
		status:'all'
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/business_apply',
	      data:{
	      	search_status:'record',
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
	mounted:function(){
		_self = this;
		//时间选择器
		layui.use('laydate', function(){
			var laydate = layui.laydate;
			laydate.render({
				elem: '#begin_time'
				,type: 'datetime'
			});
			laydate.render({
				elem: '#end_time'
				,type: 'datetime'
			});
		})       
	},
	methods:{
	  	get_list:function(num,search){
	      var _self = this;
	      base.ajax({
	         type:'get',
	         url:WEB_CONFIG.API_URL+ 'admin/business_apply',
	         data:{
					name:_self.name,
					tel:_self.tel,
					status:_self.status,
					update_start_time:$('#begin_time').val(),
					update_end_time:$('#end_time').val(),
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
	  	to_account:function(_index){
	  		var _self = this;
	  		var cash_info = JSON.stringify(_self.list[_index]);
	  		$.cookie('cash_info', cash_info, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'accountRecord';
	  	},
	  	to_set_alert:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'withdrawAlert';
	  	},
	  	pass_cash:function(_id){
	  		var _self = this;
	  		layer.confirm('确认已提现成功？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/user_cash/'+_id,
			      data:{
			      	status:2
			      }
			 	},function(res){
			      layer.close(index);
			      location.reload();
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	refuse:function(_id){
	  		var _self = this;
	  		layer.confirm('确认驳回提现申请？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/user_cash/'+_id,
			      data:{
			      	status:3
			      }
			 	},function(res){
			      layer.close(index);
			      location.reload();
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}

	}//methods

});