seajs.use(['base'], function(base) {
	
	base.headMobile();//解决手机端input获取焦点时候 头部固定偏移问题
	
	var app = new Vue({
		el: "#app",
		data: {
			gridData: [], //返回的数据
			order_id:'',//订单id
			courierNumber:'',//快递单号
			courierType:'',//配送方式
			
			//是否填写发货信息
			isDelivery:false,
			//发货详情
			isDeliveryDetals:false
			
		},
		created: function() {
			var _self = this;
			_self.getList();
		},
		filters:{
			address:function(arr){
				let address_name = '';
				for (var i = 0; i < arr.length; i++) {
					address_name += arr[i].name;
				}
				return address_name;
			},
			statusText:function(value){
				return value==0?'未支付':value==1?'已支付':value==2?'已预约':value==3?'已发货':value==4?'已完成':'';
			}
		},
		methods: {
			getList: function() {
				var _self = this;
				//获取url参数
				let urlData = location.search;
				if(urlData.indexOf('?') != -1){
					var str = urlData.substr(1);
					var strs = str.split("&");
					var data = {};
					for (var i = 0; i < strs.length; i++) {
						data[strs[i].split("=")[0]] = decodeURI(strs[i].split("=")[1]);
		
						_self.order_id = data.id;
					}
				}
				
				base.Ajax({
					url: app_config.BUS_API_URL + 'business/place_order/' + _self.order_id,
					type: 'GET',
					data: {
						id:_self.order_id
					}
				}, function(data) {
					if (data.code == '0000') {
						_self.gridData = data.data;
						_self.id = data.data.id;
						
						//状态==1已付款 输入发货信息
						if(data.data.status == 1){
							_self.isDelivery = true;
						}
						
						//状态== 3已发货  == 已完成 显示发货详情
						if(data.data.status == 3 || data.data.status == 4){
							_self.isDeliveryDetals = true;
						}
					}
				})
			},
			clickConfirm:function(){
				let _self = this;
				if(!_self.courierNumber || _self.courierNumber == '' || _self.courierNumber == null){
					layer.msg('请输入快递单号');
					return false;
				}
				if(!_self.courierType || _self.courierType == '' || _self.courierType == null){
					layer.msg('请输入配送方式');
					return false;
				}
				
				base.Ajax({
					url: app_config.BUS_API_URL + 'business/place_order/' + _self.order_id,
					type: 'PUT',
					data: {
						id:_self.order_id,
						express_number:_self.courierNumber,
						express_company:_self.courierType
					}
				}, function(data) {
					if (data.code == '0000') {
						layer.msg('已确认发货',{
							icon:1,
						});
						_self.getList();
					}
				})
			}
		}
	})


});
