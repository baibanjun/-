new Vue({
	el: '#team_set',
	data: {
		id:'',
		team_number:1,
		sale_team_number:1,
		set_team_number:0,
		set_sale_number:0,
		setting:false
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/admin_set',
	      data:{
	      	type_name:'team_setting'
	      }
	 	},function(data){
	 		if(data.value){
	 			_self.team_number = data.value.team_number;
	 			_self.sale_team_number = data.value.sale_team_number;
	 		}
	      _self.id = data.id;
	  	},function(data){

	  	});
	},
	methods:{
	  	set_condition:function(){
	  		var _self = this;
	  		_self.setting = true;
	  		_self.set_team_number = JSON.parse(JSON.stringify(_self.team_number));
	  		_self.set_sale_number = JSON.parse(JSON.stringify(_self.sale_team_number));
	  	},
	  	confirm:function(){
	  		var _self = this;
	  		if(_self.set_team_number===''||_self.set_sale_number===''){
	  			layer.msg('输入的数据有误');
	  			return false;
	  		}
	  		if(Number(_self.set_team_number)>100000||Number(_self.set_sale_number)>100000){
	  			layer.msg('输入的数据有误');
	  			return false;
	  		}
	  		if(Number(_self.set_team_number)<Number(_self.set_sale_number)){
	  			layer.msg('团队人数不得少于卖出产品人数');
	  			return false;
	  		}
	  		base.ajax({
		      type:'PUT',
		      url:WEB_CONFIG.API_URL + 'admin/admin_set/'+_self.id,
		      data:{
		      	type_name:'team_setting',
		      	value:{
		      		'team_number':_self.set_team_number,
		      		'sale_team_number':_self.set_sale_number
		      	}
		      }
		 	},function(data){		 		
		      _self.team_number = _self.set_team_number;
		      _self.sale_team_number = _self.set_sale_number;
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