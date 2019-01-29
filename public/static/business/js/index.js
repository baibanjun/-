seajs.use(['base'], function(base) {

	base.headMobile(); //解决手机端input获取焦点时候 头部固定偏移问题

	var app = new Vue({
		el: "#app",
		data: {
			gridData: [], //返回的数据
			code: '', //输入的电子码
			codeSca: '', //扫描的电子码

			orderId: '',
			message: 'bbb',
			arg2: 'qweqwe',

			tab1Steps: false, //流程box
			tab1Order: false, //订单box

			verifyBtn: false, //核销订单按钮
			
			isWei:false,
		},
		created: function() {
			var _self = this;
			//判断用户是否登录
			base.userInfo();
			//这里判断显不显示查询流程
			_self.tab1Steps = true;
			
			//微信扫一扫是否显示
			_self.isWei = base.isWx();
			
			//扫一扫进来的code
			_self.codeSca = base.GetQueryString('code');
			if(_self.codeSca){
				_self.scanningCode();
			}
		},
		filters: {
			capitalize: function(value, b, c) {
				if (!value) return ''
				value = value.toString()
				var a = value.charAt(0).toUpperCase() + value.slice(1);
				if (!b || !c) return a;
				return a + b + c;
			}
		},
		methods: {
			scanningCode:function(){
				
				var _self = this;
				_self.getOrder(_self.codeSca);
				
			},
			inputCode:function(){
				var _self = this;
				_self.verifyBtn = false;
				if (!_self.code || _self.code == '' || $.trim(_self.code) == '') {
					layer.msg('请输入订单电子码');
					return false;
				}
				_self.getOrder(_self.code);
			},
			/**
			 * 获取订单详情
			 */
			getOrder: function(code) {
				var _self = this;
				base.Ajax({
					url: app_config.BUS_API_URL + 'business/verify_the_order',
					type: 'POST',
					data: {
						code:code
					}
				}, function(data) {
					if (data.code == '0000') {
						_self.gridData = data.data;

						//这里判断显不显示查询流程
						_self.tab1Steps = false;
						_self.tab1Order = true;

						if (data.data.status == 1 || data.data.status == 2) {
							_self.verifyBtn = true;
							_self.orderId = data.data.id;
						}else{
							_self.verifyBtn = false;
						}

					} else {
						//这里判断显不显示查询流程
						_self.tab1Steps = true;
						_self.tab1Order = false;

						layer.msg(base.errorCode(data.code));
					}
				})
			},
			/**
			 * 订单核销
			 */
			orderVerification: function() {
				var _self = this;
				var orderCode;
				
				if(_self.code && _self.code != '' && _self.code != null ){
					orderCode = _self.code;
				}else if(_self.codeSca && _self.codeSca != '' && _self.codeSca != null ){
					orderCode = _self.codeSca;
				}
				base.Ajax({
					url: app_config.BUS_API_URL + 'business/verify_the_order/' + _self.orderId,
					type: 'PUT',
					data: {
						code: orderCode
					}
				}, function(data) {
					if (data.code == '0000') {
						layer.msg('已成功验证订单', {
							icon: 1
						});
						setTimeout(function() {
							_self.getOrder(orderCode);
						}, 600)

					} else {
						//这里判断显不显示查询流程
						_self.tab1Steps = true;
						_self.tab1Order = false;

						layer.msg(base.errorCode(data.code));
					}
				})
			},
			wxScanning: function() {
				base.scanning();
			}
		}
	})


});
