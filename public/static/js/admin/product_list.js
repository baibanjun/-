new Vue({
	el: '#product_list',
	data: {
		list:[],
		bus_name:'',
		city_name:'',
		name:'',
		type:'all',
		status:'all'
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/product',
	      data:{
	      	limit:10
	      }
	 	},function(res){
	      _self.list = res.data;
	      $.each(_self.list,function(i,n){
	      	_self.list[i].onhand = Number(0);
	      	$.each(n.standards,function(j,m){
	      		_self.list[i].onhand += Number(m.onhand);
	      	})
	      	//状态格式化
	      	if(n.status == 3){

	      	}else if(n.status == 2){

	      	}else{
	      		if(_self.list[i].onhand==0){
	      			_self.list[i].status = 4;//售罄
	      		}else{
	      			if(n.is_countdown==1){
	      				var _limit = Date.parse(new Date())/1000 - Date.parse(n.updated_at)/1000;
	      				if(n.time_limit>=_limit){
				      		_self.list[i].status = 5;//倒计时
				      	}else{
				      		_self.list[i].status = 2;//下架
				      	}
	      			}
	      		}
	      	}
	      })
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
	         url:WEB_CONFIG.API_URL+ 'admin/product',
	         data:{
					bus_name:_self.bus_name,
					name:_self.name,
					city_name:_self.city_name,
					type:_self.type,
					search_status:_self.status,
					page:num,
					limit:10
	         }
	      },function(res){
	         _self.list = res.data;
	         $.each(_self.list,function(i,n){
		      	_self.list[i].onhand = Number(0);
		      	$.each(n.standards,function(j,m){
		      		_self.list[i].onhand += Number(m.onhand);
		      	})
		      	//状态格式化
		      	if(n.status == 3){

		      	}else if(n.status == 2){

		      	}else{
		      		if(_self.list[i].onhand==0){
		      			_self.list[i].status = 4;//售罄
		      		}else{
		      			if(n.is_countdown==1){
		      				var _limit = Date.parse(new Date())/1000 - Date.parse(n.updated_at)/1000;
		      				if(n.time_limit>=_limit){
					      		_self.list[i].status = 5;//倒计时
					      	}else{
					      		_self.list[i].status = 2;//下架
					      	}
		      			}
		      		}
		      	}
		      })
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
	  	to_add_product:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'addProduct';
	  	},
	  	to_detail:function(_index){
	  		var _self = this;
	  		var info = JSON.parse(JSON.stringify(_self.list[_index]));
	  		info = JSON.stringify(info);
	  		window.localStorage.setItem('product_info',info);
	  		// $.cookie('product_info', info, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'productDetail';
	  	},
	  	sold_out:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认下架这件产品？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:2
			      }
			 	},function(res){			      
			      layer.msg("修改成功");
			      _self.list[_index].status = 2;
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	putaway:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认上架这件产品？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:1
			      }
			 	},function(res){
			 		layer.msg("修改成功");
			      _self.list[_index].status = 1;
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	conceal:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认隐藏这件产品？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:3
			      }
			 	},function(res){
			 		layer.msg("修改成功");			      
			      _self.list[_index].status = 3;
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	del:function(_id,_index){
	  		var _self = this;
	  		layer.confirm('确认删除这件产品？', function(index){
	  			layer.close(index);
	  			base.ajax({
			      type:'DELETE',
			      url:WEB_CONFIG.API_URL + 'admin/product/'+_id,
			      data:{}
			 	},function(res){
			 		layer.msg("删除成功");			      
			      _self.list.splice(_index,1);
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}


	}//methods

});