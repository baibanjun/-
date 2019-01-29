define(function(require, exports, module) {
	var base = require('base');
	var app = new Vue({
		el: '#login_box',
		data: {
			user: '',
			pwd: '',
			salt: '',
		},
		created: function() {
			localStorage.setItem('busInfo', '');
		},
		methods: {
			/**
			 * 获取盐值
			 */
			getSalt: function() {
				let _self = this;
				base.Ajax({
					onSite: 1, //标识站内还是站外 1：站外
					type: 'get',
					url: app_config.BUS_API_URL + 'business/login?username=' + _self.user,
					data: {}
				}, function(data) {
					if (data.code == '0000') {
						_self.salt = data.data.salt;
						console.log(_self.salt)
					} else {
						layer.msg(base.errorCode(data.code));
						return false;
					}
				})
			},
			loginClick: function() {
				let _self = this;
				let user = _self.user,
					pwd = _self.pwd;

				if (!user) {
					layer.msg('请输入用户名');
					return false;
				}
				if (pwd == '' || !pwd) {
					layer.msg('请输入登录密码');
					return false;
				}

				
				_self.getSalt();

				var sha256Pwd = _self.encryption(pwd + _self.salt);
				
				base.Ajax({
					onSite: 1, //标识站内还是站外 1：站外
					type: 'post',
					url: app_config.BUS_API_URL + 'business/login',
					data: {
						username: user,
						pwd: sha256Pwd
					},
				}, function(data) {
					if (data.code == '0000') {
						layer.msg('登录成功', {
							icon: 1,
							time: 800
						});
						console.log(data.data)
						localStorage.setItem('busInfo', JSON.stringify(data.data));
						setTimeout(function() {
							window.location.href = "/web_business";
						}, 800);
					} else {
						layer.msg(base.errorCode(data.code));
					}
				})

			},
			/**
			 * crypto-js/sha256加密  64个字符
			 */
			encryption: function(val) {
				return hex_md5(CryptoJS.SHA256(val).toString().toUpperCase());
			}
		}
	})
})
