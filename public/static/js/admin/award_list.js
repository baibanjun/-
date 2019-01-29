new Vue({
	el: '#award_list',
	data: {
		list:[],
		business_name:'',
		lottery_type:'all',
		status:'all',
		title:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/lottery_draw',
	      data:{
	      	lottery_type:'all',
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
	         url:WEB_CONFIG.API_URL+ 'admin/lottery_draw',
	         data:{
					business_name:_self.business_name,
					title:_self.title,
					lottery_type:_self.lottery_type,
					status:_self.status,
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
	  	to_add:function(){
	  		var _self = this;
	  		window.location.href = WEB_CONFIG.WEB_URL + 'addAward';
	  	},
	  	to_set:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'awardTimes';
	  	},
	  	to_detail:function(_id,_index){
	  		var _self = this;
	  		var award_info = JSON.parse(JSON.stringify(_self.list[_index]));
	  		award_info = JSON.stringify(award_info);
	  		window.localStorage.setItem('award_info',award_info);
	  		window.location.href = WEB_CONFIG.WEB_URL + 'awardDetail';
	  	},
	  	putaway:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认上架该抽奖活动？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/lottery_draw_status/'+_id,
			      data:{
			      	status:2
			      }
			 	},function(res){
			 		layer.msg("操作成功");
			      _self.list[_index].status = 2;
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	conceal:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认隐藏该抽奖活动？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/lottery_draw_status/'+_id,
			      data:{
			      	status:1
			      }
			 	},function(res){
			 		layer.msg("操作成功");
			      _self.list[_index].status = 1;
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}

	}//methods

});