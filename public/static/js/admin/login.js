new Vue({
	el: '#login',
	data: {
		user:null,
		password:null,
		pass:false
	},                                                            
	created: function () {
	  var _self = this;
	},
	methods:{
		check:function(){
			var _self = this;
			if(_self.user==''||!_self.user){
				_self.pass = false;
			}
			else if(_self.password == ''||!_self.password){
				_self.pass = false;
			}
			else if(_self.password.length<6){
				_self.pass = false;
			}
			else{
				_self.pass = true;
			}
		},
	  	login:function(){
	  		var _self = this;

  			base.ajax({
		      type:'post',
		      url:WEB_CONFIG.API_URL + 'admin/login',
		      data:{mobile:_self.user,password:_self.password}
		 	},function(res){
					$.cookie('chwlToken', res.token, base.cookieConfig(60000));
					$.cookie('userName', _self.user, base.cookieConfig(60000));
					window.location.href = WEB_CONFIG.WEB_URL+'';
		  	},function(data){

		  	});
	  // 		$.post(WEB_CONFIG.API_URL+'admin/login',{mobile:_self.user,password:_self.password},function(res){
				// if (res.code=='0000') {
				// 	$.cookie('chwlToken', res.data.token, base.cookieConfig(60000));
				// 	$.cookie('userName', _self.user, base.cookieConfig(60000));
				// 	window.location.href = WEB_CONFIG.WEB_URL+'';
				// }else{
				// 	_self.pass = false;
				// 	base.layer.msg(base.lg[res.code]);
				// 	$.cookie('userName', null, base.cookieConfig(-1));
				// 	_self.password = '';
				// }					
			// })
	  	}

	}//methods

});