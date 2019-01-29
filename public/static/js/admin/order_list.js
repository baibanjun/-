new Vue({
	el: '#order_list',
	data: {
		list:[],
		nickname:'',
		type:'all',
		status:'all',
		sn:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/order',
	      data:{
	      	type:'all',
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
	         url:WEB_CONFIG.API_URL+ 'admin/order',
	         data:{
					nickname:_self.nickname,
					sn:_self.sn,
					type:_self.type,
					status:_self.status,
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
	  	to_go_detail:function(_id,_index){
	  		var _self = this;
	  		var go_info = JSON.parse(JSON.stringify(_self.list[_index]));
	  		go_info = JSON.stringify(go_info);
	  		window.localStorage.setItem('go_info',go_info);
	  		// $.cookie('go_info', go_info, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'orderDetailGo';
	  	},
	  	to_alliance_detail:function(_id,_index){
	  		var _self = this;
	  		var alliance_info = JSON.parse(JSON.stringify(_self.list[_index]));
	  		alliance_info = JSON.stringify(alliance_info);
	  		window.localStorage.setItem('alliance_info',alliance_info);
	  		window.location.href = WEB_CONFIG.WEB_URL + 'orderDetailAlliance';
	  	},
	  	out_list:function(){
	  		var _self = this;
	  		layer.confirm('确定按当前搜索项进行导出？', function(index){
	  			window.open(
	  				WEB_CONFIG.API_URL
	  				+'admin/export_order?token='+$.cookie('chwlToken')
	  				+'&&nickname='+_self.nickname
					+'&&sn='+_self.sn
					+'&&type='+_self.type
					+'&&status='+_self.status
					+'&&start_created_at='+$('#begin_time').val()
					+'&&end_created_at='+$('#end_time').val()
	  			)
	  			layer.close(index);
	  		})
	  	}

	}//methods

});