new Vue({
	el: '#document_alert',
	data: {
		content:'',
		set_content:'',
		id:''
	},                                                            
	created: function () {
	  	var _self = this;
	  	base.ajax({
	      type:'get',
	      url:WEB_CONFIG.API_URL + 'admin/admin_set',
	      data:{
	      	type_name:'scan_content'
	      }
	 	},function(data){
	      _self.content = data.value.content;
	      _self.id = data.id;
	  	},function(data){

	  	});
	},
	methods:{
	  	set_alert:function(){
	  		var _self = this;
	  		layer.prompt({
			  	formType: 2,
			  	title: '请输入设置内容',
			  	area: ['400px', '150px'] //自定义文本域宽高
			}, function(value, index, elem){
			  	value = value.trim();
			  	if(value==''){
			  		layer.msg('内容不能为空');
			  		return false;
			  	}
			  	if(value.length>50){
			  		layer.msg('内容应当在50字以内');
			  		return false;
			  	}
			  	base.ajax({
			      type:'PUT',
			      url:WEB_CONFIG.API_URL + 'admin/admin_set/'+_self.id,
			      data:{
			      	type_name:'scan_content',
			      	value:{'content':value}
			      }
			 	},function(data){
			      _self.content = value;
			      layer.close(index);
			      layer.msg('设置成功');
			  	},function(data){

			  	});
			});
	  	}

	}//methods

});