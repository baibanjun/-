new Vue({
	el: '#alliance_product_detail',
	data: {
		info:{},
		time:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	_self.info = JSON.parse($.cookie('product_info'));
	  	
	  	var timer = setInterval(function(){
	  		var _limit = Date.parse(new Date())/1000 - Date.parse(_self.info.updated_at)/1000;
	  		if(_self.info.time_limit<_limit){
	  			_self.info.status = 2;//下架
	  		}else{
	  			var _s = _self.info.time_limit - _limit;
	  			var d=Math.floor(_s/60/60/24);
            var hour=Math.floor(_s/60/60);
            var min=Math.floor(_s/60%60);
            var sec=Math.floor(_s%60);
            _self.time = (d==0?'':d + '天') + hour + '小时' + min + '分钟' + sec + '秒';
	  		}
	  	},1000)
	},
	methods:{
	  	to_product_list:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'productList';
	  	},
	  	sold_out:function(_id){
	  		var _self = this;
	  		layer.confirm('确认下架这件产品？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:2
			      }
			 	},function(res){
			      layer.close(index);
			      window.location.href = WEB_CONFIG.WEB_URL + 'productList';
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	putaway:function(_id){
	  		var _self = this;
	  		layer.confirm('确认上架这件产品？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:1
			      }
			 	},function(res){
			      layer.close(index);
			      window.location.href = WEB_CONFIG.WEB_URL + 'productList';
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	conceal:function(_id){
	  		var _self = this;
	  		layer.confirm('确认隐藏这件产品？', function(index){
	  			base.ajax({
			      type:'put',
			      url:WEB_CONFIG.API_URL + 'admin/product_status/'+_id,
			      data:{
			      	status:3
			      }
			 	},function(res){
			      layer.close(index);
			      window.location.href = WEB_CONFIG.WEB_URL + 'productList';
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	},
	  	del:function(_id){
	  		var _self = this;
	  		layer.confirm('确认删除这件产品？', function(index){
	  			base.ajax({
			      type:'DELETE',
			      url:WEB_CONFIG.API_URL + 'admin/product/'+_id,
			      data:{}
			 	},function(res){
			      layer.close(index);
			      window.location.href = WEB_CONFIG.WEB_URL + 'productList';
			  	},function(res){
			  		layer.close(index);
			  	});
	  		})
	  	}

	}//methods

});