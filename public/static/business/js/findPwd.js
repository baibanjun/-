define(function(require, exports, module) {
	var base = require('base');
	var app = new Vue({
		el: '#findPwd',
		data: {
			time: 60, //短信倒计时
			mobile: '',
			code: '',
		},
		methods: {
			getCode: function() {
				let _self = this;
				let mobile = _self.mobile;
				if (!base.isPhone(mobile)) {
					layer.msg('手机号码不符合规范');
					return false;
				}

				base.sendMsg({
					btn: '#sendBtn',
					time: _self.time,
					mobile: mobile
				});
			},
			findNext: function() {
				let _self = this;
				let mobile = _self.mobile,
					code = _self.code;

				if (mobile == '' || !mobile) {
					layer.msg('请输入绑定的的手机号码');
					return false;
				}
				if (!base.isPhone(mobile)) {
					layer.msg('手机号码不符合规范');
					return false;
				}
				if (code == '' || !code) {
					layer.msg('请输入短信验证码');
					return false;
				}

				base.Ajax({
					onSite:1,//标识站内还是站外 1：站外
					type:'get',
					url: app_config.BUS_API_URL + 'business/forget_pwd/' + mobile,
					data:{
						code:code
					}
				},function(data){
					if (data.code == '0000') {
						base.findPwdUserInfo.code = code;
						localStorage.setItem('findPwdUserInfo',JSON.stringify(base.findPwdUserInfo));
						window.location.href="/web_business/reset_pwd";
					}else{
						layer.msg(base.errorCode(data.code));
					}
				})
			},
		}
	})
})
