new Vue({
	el: '#witndraw_record',
	data: {
		list:[],
		nickname:'',
		mobile:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/user_cash',
	      data:{
	      	type:"success",
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
	         url:WEB_CONFIG.API_URL+ 'admin/user_cash',
	         data:{
	         	type:"success",
					nickname:_self.nickname,
					mobile:_self.mobile,
					start_created_at:$('#begin_time').val(),
					end_created_at:$('#end_time').val(),
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
	  	to_account:function(_id){
	  		var _self = this;
	  		$.cookie('account_id', _id, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'teamMember';
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
			      // _self.list[_index].status = 2;
			      layer.close(index);
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
			      // _self.list[_index].status = 2;
			      layer.close(index);
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}

	}//methods

});