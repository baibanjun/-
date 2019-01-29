new Vue({
	el: '#product_detail',
	data: {
		info:{},
		time:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	_self.info = JSON.parse(window.localStorage.product_info);
	  	if(_self.info.status==5){
			var timer = setInterval(function(){
		  		var _limit = Date.parse(new Date())/1000 - Date.parse(_self.info.updated_at)/1000;
		  		if(_self.info.time_limit<_limit){
		  			_self.info.status = 2;//下架
		  		}else{
		  			var _s = _self.info.time_limit - _limit;
		  			var d=Math.floor(_s/60/60/24);
	            var hour=Math.floor(_s/60/60%24);
	            var min=Math.floor(_s/60%60);
	            var sec=Math.floor(_s%60);
	            _self.time = (d==0?'':d + '天') + hour + '小时' + min + '分钟' + sec + '秒';
		  		}
		  	},1000)
	  	}
	  	
	},
	methods:{
	  	to_product_list:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'productList';
	  	},
	  	edit_product:function(){
	  		window.location.href = WEB_CONFIG.WEB_URL + 'editProduct';
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
	  	},
	  	show_img:function(){
	  		var _self = this;
	  		base.ajax({
		      type:'post',
		      url:WEB_CONFIG.API_URL + 'admin/product_poser',
		      data:{product_id:_self.info.id}
		 	},function(data){
		      var img_content = WEB_CONFIG.PIC_URL+data.name;
	         $("body").append(
	            "<div class='bg-img'>" +
	            "<div class='tra-img'>" +
	            "<img style='max-width: 375px;' src='" + img_content + "' class='zoom-out'>" +
	            "</div></div>"
	         );
	         //bottom:'0',left:'0';会让图片从页面左下放出现，如果想从左上方出现，将bottom:'0'改成top:'0';
	         $(".bg-img").animate({
	            width: "100%",
	            height: "100%",
	            top: "0",
	            left: "0",
	         }, "normal")
		  	},function(data){

		  	});
	  		
	  	}

	}//methods

});