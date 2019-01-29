new Vue({
	el: '#order_detail_go',
	data: {
		go_info:{}
	},                                                            
	created: function () {
	  	var _self = this;
	  	_self.go_info = JSON.parse(window.localStorage.go_info);
	},
	methods:{
	  	to_list:function(){
	  		var _self = this;
	  		$.cookie('go_info', null, base.cookieConfig(-1));
	  		$.cookie('alliance_info', null, base.cookieConfig(-1));
	  		window.location.href = WEB_CONFIG.WEB_URL + 'orderList';
	  	}

	}//methods

});