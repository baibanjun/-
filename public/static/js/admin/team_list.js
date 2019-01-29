new Vue({
	el: '#team_list',
	data: {
		list:[],
		nickname:'',
		name:'',
		mobile:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/user_team',
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
	         url:WEB_CONFIG.API_URL+ 'admin/user_team',
	         data:{
					nickname:_self.nickname,
					name:_self.name,
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
	  	to_member:function(_id,_nickname,_name,_mobile){
	  		var _self = this;
	  		var _info = {
	  			nickname:_nickname,
	  			name:_name,
	  			mobile:_mobile
	  		}
	  		_info = JSON.stringify(_info);
	  		$.cookie('team_id', _id, base.cookieConfig(60000));
	  		$.cookie('captain_info', _info, base.cookieConfig(60000));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'teamMember';
	  	}

	}//methods

});