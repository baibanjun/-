new Vue({
	el: '#award_times',
	data: {
		id:'',
		day_has_num:"",
		day_share_num:"",
		share_get_num:"",
		set_day_has:'',
		set_day_share:'',
		set_day_get:'',
		setting:false
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/admin_set',
	      data:{
	      	type_name:'lottery_draw'
	      }
	 	},function(data){
	 		if(data.value){
	 			_self.day_has_num = data.value.day_has_num;
	 			_self.day_share_num = data.value.day_share_num;
	 			_self.share_get_num = data.value.share_get_num;
	 		}
	      _self.id = data.id;
	  	},function(data){

	  	});
	},
	methods:{
	  	set_alert:function(){
	  		var _self = this;
	  		_self.setting = true;
	  		_self.set_day_has = JSON.parse(JSON.stringify(_self.day_has_num));
	  		_self.set_day_share = JSON.parse(JSON.stringify(_self.day_share_num));
	  		_self.set_day_get = JSON.parse(JSON.stringify(_self.share_get_num));
	  	},
	  	confirm:function(){
	  		var _self = this;
	  		if(_self.set_day_has==""){
	  			layer.msg('提醒字段不能为空');
	  			return false;
	  		}
	  		if(_self.set_day_share==""){
	  			layer.msg('提醒字段不能为空');
	  			return false;
	  		}
	  		if(_self.set_day_get==""){
	  			layer.msg('提醒字段不能为空');
	  			return false;
	  		}
	  		base.ajax({
		      type:'PUT',
		      url:WEB_CONFIG.API_URL + 'admin/admin_set/'+_self.id,
		      data:{
		      	type_name:'lottery_draw',
		      	value:{
		      		'day_has_num':_self.set_day_has,
		      		'day_share_num':_self.set_day_share,
		      		'share_get_num':_self.set_day_get
		      	}
		      }
		 	},function(data){		 		
		      _self.day_has_num = _self.set_day_has;
		      _self.day_share_num = _self.set_day_share;
		      _self.share_get_num = _self.set_day_get;
		      _self.setting = false;
		      layer.msg('设置成功');
		  	},function(data){
		  		_self.setting = false;
		  	});
	  	},
	  	off:function(){
	  		var _self = this;
	  		_self.setting = false;
	  	},
	  	back:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
	  	}

	}//methods

});