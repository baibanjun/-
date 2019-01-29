new Vue({
	el: '#team_member',
	data: {
		list:[],
		captain_info:{
			nickname:'',
			name:'',
			moblie:''
		}
	},                                                            
	created: function () {
	  	var _self = this;
	  	_self.captain_info = JSON.parse($.cookie('captain_info'));
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/user_team/'+$.cookie('team_id'),
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
	         url:WEB_CONFIG.API_URL+ 'admin/user_team/'+$.cookie('team_id'),
	         data:{
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
	  	to_list:function(){
	  		var _self = this;
	  		$.cookie('team_id', null, base.cookieConfig(-1));
	  		$.cookie('captain_info', null, base.cookieConfig(-1));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'teamList';
	  	}

	}//methods

});