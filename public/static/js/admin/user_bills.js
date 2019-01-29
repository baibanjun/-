new Vue({
	el: '#user_bills',
	data: {
		list:[],
		role:'all',
		status:'all',
		nickname:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/user',
	      data:{
	      	role:'all',
	      	status:'all'
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
	         url:WEB_CONFIG.API_URL+ 'admin/user',
	         data:{
					nickname:_self.nickname,
					role:_self.role,
					status:_self.status,
					page:num
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
							limit: 1,
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
			      url:WEB_CONFIG.API_URL + 'admin/user/'+_id,
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
	  	unfreeze:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确定要解冻该用户？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/user/'+_id,
			      data:{
			      	status:0
			      }
			 	},function(res){
			      _self.list[_index].status = 0;
			      layer.close(index);
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}

	}//methods

});