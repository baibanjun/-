new Vue({
	el: '#index',
	data: {
		user:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	_self.user = $.cookie('userName');
	  	if(!$.cookie('chwlToken')){
	  		window.location.href =WEB_CONFIG.WEB_URL+ 'login';
	  	}
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/product_city',
	      data:{

	      }
	 	},function(data){
	  	},function(data){

	  	});
	},
	methods:{
	  	
	}//methods

});
