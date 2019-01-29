seajs.use(['base','page'], function(base) {
	
	base.headMobile();//解决手机端input获取焦点时候 头部固定偏移问题
	
	var app = new Vue({
		el: "#app",
		data: {
			name: null, //姓名
			tel: null, //电话
			code:null, //电子码
			sn: null, //订单号
			gridData: [], //返回的数据
			
			curPage:0,//当前页
			showPages:0,//显示多少页
			totalPages:0,//总页数
			isPage:true,//是否显示分页

            order_id: null,

            orderDetails_sd: null

		},
		created: function() {
			var _self = this;
			_self.getList(1);

			_self.orderDetails_sd = JSON.parse(localStorage.getItem('orderDetails_sd'));
		},
		components: {
			// 引用组件
			'pagination': pagination
		},
		filters:{
			statusText:function(value){
				return value==0?'未支付':value==1?'已支付':value==2?'已预约':value==3?'已发货':value==4?'已完成':'';
			}
		},
		methods: {
			getList: function(p) {
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
					url: app_config.BUS_API_URL + 'business/verify_the_coupon/' + _self.order_id,
					type: 'get',
					data: {
						type: 3, //核销记录
						limit: 15,
						page: p,
						name: _self.name
					}
				}, function(data) {
					if (data.code == '0000') {
						_self.gridData = data.data.data;
						let d = data.data;
						
						_self.curPage = d.current_page;//当前页
						_self.totalPages = d.last_page;//总页数
						_self.showPages = d.last_page == 0 ? 0 : d.last_page < 5 ? d.last_page : 5;//可以点击的页数

						if(d.last_page == 0){
							_self.isPage = false;
						}
					}
				})
			},
			orderDetails:function(id){
				window.location.href="/web_business/orderDetails?id=" + id;
			}
		}
	})


});
