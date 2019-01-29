new Vue({
	el: '#business_list',
	data: {
		list: [],
		name: '',
		add_show: false,
		edit_show: false,
		cover: false,
		req: {
			id: null,
			name: 0,
			tel: 0,
			address: 'haha',
			lng: 0,
			lat: 0,
			username: 0,
			mobile: 0,
			password: null,
			salt: 'sabwdkww',
			re_password: null,
		},
		goOnAdd: 1, //地图用的标识
		goOnEdit: 1
	},
	created: function() {
		var _self = this;
		base.ajax({
			type: 'get',
			url: WEB_CONFIG.API_URL + 'admin/business',
			data: {
				limit: 10
			}
		}, function(res) {
			_self.list = res.data;
			layui.use('laypage', function() {
				var page = layui.laypage;
				page.render({
					elem: 'pages',
					count: res.last_page,
					curr: res.current_page,
					limit: 1,
					jump: function(obj, first) {
						if (!first) {
							_self.get_list(obj.curr, false);
						}
					}
				});
			});
		}, function(res) {

		});

	},
	mounted: function() {
		_self = this;
		//时间选择器
		layui.use('laydate', function() {
			var laydate = layui.laydate;
			laydate.render({
				elem: '#begin_time',
				type: 'datetime'
			});
			laydate.render({
				elem: '#end_time',
				type: 'datetime'
			});
		})
	},
	methods: {
		get_list: function(num, search) {
			var _self = this;
			base.ajax({
				type: 'get',
				url: WEB_CONFIG.API_URL + 'admin/business',
				data: {
					name: _self.name,
					start_time: $('#begin_time').val(),
					end_time: $('#end_time').val(),
					page: num,
					limit: 10
				}
			}, function(res) {
				_self.list = res.data;
				if (search) {
					layui.use('laypage', function() {
						var page = layui.laypage;
						page.render({
							elem: 'pages',
							count: res.last_page,
							curr: res.current_page,
							limit: 1,
							jump: function(obj, first) {
								if (!first) {
									_self.get_list(obj.curr, false);
								}
							}
						});
					});
				}
			}, function(res) {

			});
		},
		freeze: function(_id, _index) {
			var _self = this;
			layer.confirm('确定要冻结该商家？', function(index) {
				base.ajax({
					type: 'put',
					url: WEB_CONFIG.API_URL + 'admin/business/' + _id,
					data: {
						status: 2,
						update_type: 'status'
					}
				}, function(res) {
					_self.list[_index].status = 2;
					layer.close(index);
				}, function(res) {
					layer.close(index);
				});
			})
		},
		unfreeze: function(_id, _index) {
			var _self = this;
			layer.confirm('确定要解冻该商家？', function(index) {
				base.ajax({
					type: 'put',
					url: WEB_CONFIG.API_URL + 'admin/business/' + _id,
					data: {
						status: 1,
						update_type: 'status'
					}
				}, function(res) {
					_self.list[_index].status = 1;
					layer.close(index);
				}, function(res) {
					layer.close(index);
				});
			})
		},
		close: function() {
			var _self = this;
			_self.add_show = false;
			_self.edit_show = false;
			_self.cover = false;
		},
		add: function() {
			var _self = this;
			_self.req = {
				id: null,
				name: '',
				tel: '',
				address: '',
				lng: 0,
				lat: 0,
				username: '',
				mobile: '',
				password: null,
				re_password: null
			}
			_self.add_show = true;
			_self.cover = true;
		},
		edit: function(_index) {
			var _self = this;
			//深拷贝
			_self.req = JSON.parse(JSON.stringify(_self.list[_index]));
			_self.edit_show = true;
			_self.cover = true;
			
			//创建地图实例，用经纬度设置地图中心点
			map_2.clearOverlays(); 
			var new_point = new BMap.Point(_self.req.lng, _self.req.lat);
			var marker = new BMap.Marker(new_point);  // 创建标注
			map_2.addOverlay(marker);              // 将标注添加到地图中
			setTimeout(function(){
				map_2.panTo(new_point);
			},100)
		},
		add_post: function() {
			var _self = this;
			var re = /^1[3456789]\d{9}$/;
			if (_self.req.name == "") {
				base.layer.msg('商家名称不能为空');
				return false;
			}
			if (_self.req.address == "") {
				base.layer.msg('请选择商家地址');
				return false;
			}
			if (_self.req.tel == "") {
				base.layer.msg('商家联系电话不能为空');
				return false;
			}
			if (!re.test(_self.req.mobile)) {
				base.layer.msg('登录手机号码有误');
				return false;
			}
			if (_self.req.password.length < 6 || _self.req.password.length > 18) {
				base.layer.msg('密码的长度在6-18位');
				return false;
			}
			if (_self.req.password != _self.req.re_password) {
				base.layer.msg('两次输入的密码不相同');
				return false;
			}
			_self.req.salt = base.uuid(8);
			var sha256Pwd = _self.encryption(_self.req.password + _self.req.salt);

			base.ajax({
				type: 'post',
				url: WEB_CONFIG.API_URL + 'admin/business',
				data: {
					name: _self.req.name,
					tel: _self.req.tel,
					address: _self.req.address,
					lng: _self.req.lng,
					lat: _self.req.lat,
					username: _self.req.username,
					mobile: _self.req.mobile,
					password: sha256Pwd,
					salt: _self.req.salt
				}
			}, function(data) {
				location.reload();
			}, function(data) {

			});
		},
		edit_post: function() {
			var _self = this;
			var re = /^1[3456789]\d{9}$/;
			if (_self.req.tel == "") {
				base.layer.msg('商家联系电话不能为空');
				return false;
			}
			if (_self.req.address == "") {
				base.layer.msg('请选择商家地址');
				return false;
			}
			if (_self.req.username == "") {
				base.layer.msg('核销系统用户名不能为空');
				return false;
			}
			if (!re.test(_self.req.mobile)) {
				base.layer.msg('登录手机号码有误');
				return false;
			}
			base.ajax({
				type: 'put',
				url: WEB_CONFIG.API_URL + 'admin/business/' + _self.req.id,
				data: {
					id: _self.req.id,
					name: _self.req.name,
					tel: _self.req.tel,
					address: _self.req.address,
					lng: _self.req.lng,
					lat: _self.req.lat,
					username: _self.req.username,
					mobile: _self.req.mobile
				}
			}, function(data) {
				location.reload();
			}, function(data) {

			});
		},
		/**
		 * 地图模糊匹配标记
		 */
		businessSearchMap: function(e, map, addtext) {
			var nowMap;
			if(map == 'map_1'){
				nowMap = map_1;
			}else if(map == 'map_2'){
				nowMap = map_2;
			}
			
			
			
			var _self = this;
			var search;
			$('.gwResultMap').css('display', 'none');
			$('.gwResultMap').html('');
			var html = '';
			if (addtext != '' && addtext != null) {
				search = addtext;
			} else {
				search = _self.req.address;
			}
			console.log(search);
			var options = {
				onSearchComplete: function(results) {
					// 判断状态是否正确
					if (local.getStatus() == BMAP_STATUS_SUCCESS) {
						var s = [];
						console.log(results);
						console.log(results.getCurrentNumPois());

						if (results.Ar !== '[]') {
							$('.gwResultMap').css('display', 'block');
						} else {
							$('.gwResultMap').css('display', 'none');
						}

						for (var i = 0; i < results.getCurrentNumPois(); i++) {
							var array = {
								title: results.getPoi(i).title,
								address: results.getPoi(i).address,
								lng: results.getPoi(i).point.lng,
								lat: results.getPoi(i).point.lat
							}
							s.push(array);
						}
						for (var i = 0; i < s.length; i++) {
							html += '<div class="add-min" data-lng="' + s[i].lng + '" data-lat="' + s[i].lat + '">' + s[i].address +
								',' + s[i].title + '</div>';
						}
						$('.gwResultMap').append(html);
						// document.getElementById("result").innerHTML = s.join("<hr/>");

						$('.add-min').on('click', function(e) {
							let that = this;
							let text = $(that).text();
							_self.req.address = text;
							// _self.businessAdd('',text);

							$('.gwResultMap').css('display', 'none');

							console.log($(that).attr('data-lng'), $(that).attr('data-lat'));
							//移除覆盖物
							nowMap.clearOverlays();

							// 创建地图实例（这里地图移动到指定位置并标点
							var lng = $(that).attr('data-lng');
							var lat = $(that).attr('data-lat');
							var point = {
								lng,
								lat
							};
							nowMap.centerAndZoom(new BMap.Point(lng, lat), 18);
							// 初始化地图， 设置中心点坐标和地图级别
							var marker = new BMap.Marker(point);
							nowMap.addOverlay(marker);
						})
					}
				}
			};
			var local = new BMap.LocalSearch(nowMap, options);
			local.search(search);
		},
		add_point: function() {
			var _self = this;
			if (_self.goOnAdd == 1) {
				_self.goOnAdd += 1;
				var geoc = new BMap.Geocoder();
				map_1.addEventListener("click", function(e) {
					//移除覆盖物
					map_1.clearOverlays();

					var pot = e.point;
					geoc.getLocation(pot, function(rs) {
						_self.req.address = rs.address;
						_self.req.lng = rs.point.lng;
						_self.req.lat = rs.point.lat;
						_self.businessSearchMap('','map_1','');
						console.log(rs.point.lng, rs.point.lat);

						// 创建地图实例
						var point = new BMap.Point(rs.point.lng, rs.point.lat);

						// 初始化地图， 设置中心点坐标和地图级别
						var marker = new BMap.Marker(point);
						map_1.addOverlay(marker);
					});
				});
			}
		},
		edit_point: function() {
			var _self = this;
			if (_self.goOnEdit == 1) {
				_self.goOnEdit += 1;
				var geoc = new BMap.Geocoder();
				map_2.addEventListener("click", function(e) {
					//移除覆盖物
					map_2.clearOverlays();
					var pot = e.point;
					geoc.getLocation(pot, function(rs) {
						_self.req.address = rs.address;
						_self.req.lng = rs.point.lng;
						_self.req.lat = rs.point.lat;
						_self.businessSearchMap('','map_2','');

						console.log(rs.point.lng, rs.point.lat);
						// 创建地图实例
						var point = new BMap.Point(rs.point.lng, rs.point.lat);

						// 初始化地图， 设置中心点坐标和地图级别
						var marker = new BMap.Marker(point);
						map_2.addOverlay(marker);
					});
				}, false);
			}
		},
		//加密
		encryption: function(val) {
			return hex_md5(CryptoJS.SHA256(val).toString().toUpperCase());
		}

	} //methods

});
