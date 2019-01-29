new Vue({
	el: '#discounts_list',
	data: {
		list:[],
		discount_list:[],
		nickname:'',
		discount_type:'not_use',
		discount_user:'',
		discount_show:false,
		cover:false
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/lottery_user',
	      data:{
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
	         url:WEB_CONFIG.API_URL+ 'admin/lottery_user',
	         data:{
					nickname:_self.nickname,
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
	  	get_discount_list:function(num,_id){
	      var _self = this;
	      console.log(_self.discount_type);
	      base.ajax({
	         type:'get',
	         url:WEB_CONFIG.API_URL+ 'admin/lottery_user/'+_id,
	         data:{
					type:_self.discount_type,
					page:num,
					limit:10
	         }
	      },function(res){
	         _self.discount_list = res.data;
	      },function(res){

	      });          
	  	},
	  	to_discount:function(_id,_type,_name){
	  		var _self = this;
	  		base.ajax({
		      type:'get',
		      url:WEB_CONFIG.API_URL + 'admin/lottery_user/'+_id,
		      data:{
		      	type:_type,
		      	limit:10
		      }
		 	},function(res){
		      _self.discount_list = res.data;
		      _self.discount_type = _type;
		      _self.discount_user = _name;
		      _self.discount_show = true;
	  			_self.cover = true;
		      layui.use('laypage', function(){
	            var page = layui.laypage;
	            page.render({
						elem: 'page_1',
						count: res.last_page,
						curr:res.current_page,
						limit:1,
						jump: function(obj,first){
						  	if(!first){
						      _self.get_discount_list(obj.curr,_id);
						  	}
						}
	            });
	        	});
		  	},function(res){

		  	});	  		
	  	},
	  	close:function(){
	  		var _self = this;
	  		_self.discount_show = false;
	  		_self.cover = false;
	  	}

	}//methods

});