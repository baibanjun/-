define(function(require, exports, module) {
	var base = require('base');
	var app = new Vue({
		el: '#resetPwd',
		data: {
			pwdInfo:[],//存储的数据
			pwd:'',
			salt:'',//盐值
			agen_pwd:'',
		},
		created:function(){
			let _self = this;
			_self.pwdInfo = JSON.parse(localStorage.getItem('findPwdUserInfo'));	
			
			//获取颜值
			if(_self.pwdInfo){
				_self.getSalt();
			}
		},
		methods: {
			resetPwdClick: function() {
				let _self = this;
				let pwd = _self.pwd;
				let pwd2 = _self.agen_pwd;
				let info = _self.pwdInfo;
				/**
				 * 最短6位，最长16位 {6,16}
				 *	可以包含小写大母 [a-z] 和大写字母 [A-Z]
				 *	可以包含数字 [0-9]
				 *	可以包含下划线 [ _ ] 和减号 [ - ]
				 */
				var pattern = /^[\w_-]{6,16}$/;//密码
				
				if(!pattern.test(pwd)){
					layer.msg('请按照规则填写新密码');
					return false;
				}
				
				if (!pwd || pwd == '') {
					layer.msg('请输入重置密码');
					return false;
				}
				if(pwd != pwd2){
					layer.msg('两次输入的密码不一致');
					return false;
				}
				
				var sha256Pwd = _self.encryption(pwd + _self.salt);
				
				base.Ajax({
					onSite:1,//标识站内还是站外 1：站外
					type:'put',
					url: app_config.BUS_API_URL + 'business/forget_pwd/' + info.mobile,
					data:{
						code:info.code,
						pwd:sha256Pwd,
						salt:_self.salt
					}
				},function(data){
					if (data.code == '0000') {
						layer.msg('密码重置成功',{
							icon:1
						});
						setTimeout(function(){
							window.location.href="/web_business/login";
						},1000)
					}else{
						layer.msg(base.errorCode(data.code));
					}
				})
			},
			/**
			 * 获取盐值
			 */
			getSalt:function(){
				let _self = this;
				let info = _self.pwdInfo;
				base.Ajax({
					onSite:1,//标识站内还是站外 1：站外
					type:'get',
					url: app_config.BUS_API_URL + 'business/login?username=' + info.username,
					data:{}
				},function(data){
					if (data.code == '0000') {
						_self.salt = data.data.salt;
					}else{
						layer.msg(base.errorCode(data.code));
					}
				})
			},
			/**
			 * crypto-js/sha256加密  64个字符
			 */
			encryption:function(val){
				return hex_md5(CryptoJS.SHA256(val).toString().toUpperCase());
			}
		}
	})
})
