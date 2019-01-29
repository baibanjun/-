new Vue({
	el: '#invite_set',
	data: {
		id:'',
		money:0,
		set_money_num:0,
		setting:false
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/admin_set',
	      data:{
	      	type_name:'attention'
	      }
	 	},function(data){
	 		if(data.value){
	 			_self.money = data.value.money;
	 		}
	      _self.id = data.id;
	  	},function(data){

	  	});
	},
	methods:{
	  	set_money:function(){
	  		var _self = this;
	  		_self.setting = true;
	  		_self.set_money_num = JSON.parse(JSON.stringify(_self.money));
	  	},
	  	confirm:function(){
	  		var _self = this;
	  		if(!Number(_self.set_money_num)){
	  			layer.msg('输入的数据有误');
	  			return false;
	  		}
	  		if(Number(_self.set_money_num)>100000){
	  			layer.msg('输入的数据有误');
	  			return false;
	  		}
	  		base.ajax({
		      type:'PUT',
		      url:WEB_CONFIG.API_URL + 'admin/admin_set/'+_self.id,
		      data:{
		      	type_name:'attention',
		      	value:{
		      		'money':_self.set_money_num
		      	}
		      }
		 	},function(data){		 		
		      _self.money = _self.set_money_num;
		      _self.setting = false;
		      layer.msg('设置成功');
		  	},function(data){
		  		_self.setting = false;
		  	});
	  	},
	  	off:function(){
	  		var _self = this;
	  		_self.setting = false;
	  	}

	}//methods

});