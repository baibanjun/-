new Vue({
	el: '#dealer_list',
	data: {
		list:[],
		sn:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/distribution',
	      data:{
	      	limit:10
	      }
	 	},function(res){
	 		//格式化数据
	      $.each(res.data,function(i,n){
	      	var _list = {
	      		id:'',
		      	sn:'',
		      	nickname:'',
		      	money:0,
		      	pay_time:'——',
		      	first_mobile:'——',
		      	first_money:'——',
		      	second_mobile:'——',
		      	second_money:'——',
		      	team_mobile:'——',
		      	team_money:'——'
		      };
		      _list.id = n.id;
	      	_list.sn = n.sn;
	      	_list.nickname = n.user.nickname;
	      	_list.money = n.money;
	      	_list.pay_time = n.pay_time;
	      	$.each(n.account_record,function(j,m){
	      		if(m.object_type==1){
	      			if(m.user_talent){
	      				_list.first_mobile = m.user_talent.mobile;
	      			}	      			
	      			_list.first_money = m.money;
	      		}
	      		else if(m.object_type==2){//团队分销
	      			if(m.user_talent){
	      				_list.team_mobile = m.user_talent.mobile;
	      			}	      			
	      			_list.team_money = m.money;
	      		}
	      		else if(m.object_type==6){
	      			if(m.user_talent){
	      				_list.second_mobile = m.user_talent.mobile;
	      			}	      			
	      			_list.second_money = m.money;
	      		}
	      	})
	      	_self.list.push(_list);
	      });

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
	         url:WEB_CONFIG.API_URL+ 'admin/distribution',
	         data:{
					sn:_self.sn,
					start_pay_time:$('#begin_time').val(),
					end_pay_time:$('#end_time').val(),
					page:num,
					limit:10
	         }
	      },function(res){
	         _self.list = [];
	         //格式化数据
	         $.each(res.data,function(i,n){
		      	var _list = {
			      	sn:'',
			      	nickname:'',
			      	money:0,
			      	pay_time:'——',
			      	first_mobile:'——',
			      	first_money:'——',
			      	second_mobile:'——',
			      	second_money:'——',
			      	team_mobile:'——',
			      	team_money:'——'
			      };
		      	_list.sn = n.sn;
		      	_list.nickname = n.user.nickname;
		      	_list.money = n.money;
		      	_list.pay_time = n.pay_time;
		      	$.each(n.account_record,function(j,m){
		      		if(m.object_type==1){
		      			if(m.user_talent){
		      				_list.first_mobile = m.user_talent.mobile;
		      			}	      			
		      			_list.first_money = m.money;
		      		}
		      		else if(m.object_type==2){//团队分销
		      			if(m.user_talent){
		      				_list.team_mobile = m.user_talent.mobile;
		      			}	      			
		      			_list.team_money = m.money;
		      		}
		      		else if(m.object_type==6){
		      			if(m.user_talent){
		      				_list.second_mobile = m.user_talent.mobile;
		      			}	      			
		      			_list.second_money = m.money;
		      		}
		      	})
		      	_self.list.push(_list);
		      });

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
	  	to_member:function(_id){
	  		var _self = this;
	  		$.cookie('team_id', _id, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'teamMember';
	  	},
	  	show_detail:function(_id){
	  		var _self = this;
	  		base.ajax({
		      type:'get',
		      url:WEB_CONFIG.API_URL + 'admin/order/' + _id,
		      data:{}
		 	},function(data){
		 		var primaryd_distribution = data.product.primaryd_distribution.type==1?
		      	(data.product.primaryd_distribution.value*100+"%"+' ' + '￥'+data.product.primaryd_distribution*data.money):("￥" + data.product.primaryd_distribution.value);
		      var secondary_distribution = data.product.secondary_distribution.type==1?
		      	(data.product.secondary_distribution.value*100+"%"+' ' + '￥'+data.product.secondary_distribution*data.money):("￥" + data.product.secondary_distribution.value);
		      var team_distribution = data.product.team_distribution.type==1?
		      	(data.product.team_distribution.value*100+"%"+' ' + '￥'+data.product.team_distribution*data.money):("￥" + data.product.team_distribution.value)
		      layer.open({
            	title:'分销订单详情',
            	offset:[200,400],
		      	content:'<div style="width:300px;">'+
		      	'<p class="lh2"><span>订单号码：</span>'+data.sn+'</p>'+
		      	'<p class="lh2">'+data.product.subtitle+'</p>'+
		      	'<p class="b t18">￥'+data.money+'</p>'+
		      	'<hr>'+
		      	'<p><span>用户姓名：</span>'+data.name+'</p>'+
		      	'<p><span>联系电话：</span>'+data.tel+'</p>'+
		      	'<p><span>产品名称：</span>'+data.product.name+'</p>'+
		      	'<p><span>商家名称：</span>'+data.business.name+'</p>'+
		      	'<p><span>一级分销：</span>'+primaryd_distribution+'</p>'+
		      	'<p><span>二级分销：</span>'+secondary_distribution+'</p>'+
		      	'<p><span>团队分销：</span>'+team_distribution+'</p>'+
		      	'</div>'
		      })
		  	},function(data){

		  	});
	  	},
	  	//导出
	  	out_list:function(){
	  		var _self = this;
	  		layer.confirm('确定按当前搜索项进行导出？', function(index){
	  			window.open(
	  				WEB_CONFIG.API_URL
	  				+'admin/export_distribution?token='+$.cookie('chwlToken')
					+'&&sn='+_self.sn
					+'&&start_pay_time='+$('#begin_time').val()
					+'&&end_pay_time='+$('#end_time').val()
	  			)
	  			layer.close(index);
	  		})
	  	}

	}//methods

});