seajs.use(['base'], function(base) {

	base.headMobile(); //解决手机端input获取焦点时候 头部固定偏移问题

	var app = new Vue({
		el: "#app",
		data: {
			code:null
		},
		created: function() {
			//判断用户是否登录
			base.userInfo();

			var index = layer.load(2);
			
			var _self = this;
			_self.code = base.GetQueryString('code');
			
			if (!_self.code || _self.code == '' || $.trim(_self.code) == '') {
				layer.msg('电子码不存在');
				window.location.href="/web_business"
				return false;
			}else{
				window.location.href="/web_business?code="+_self.code;
			}
		},
		methods: {
			
		}
	})


});
