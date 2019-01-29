new Vue({
    el: '#detailsApp',
    data: {
        data: [],
        curStandards: {
            onhand: 1
        },
        wxInfo: base.wxInfo,
        floatR: true,
        u: '',
        is_poster_s: false,
        poster_s_style: {},
		topContent:'',
    },
    created: function () {
        var _self = this;
        $(document).attr('title', '吃喝玩乐成都联盟·爆品详情');
        var id = base.getQuery().id;
        if (!id) {
            window.location.href = app_config.WEB_URL;
        }
        _self.getData(id);
		
		//获取顶部文字
		_self.getContent();

    },
    mounted: function () {
        var _self = this;
        setInterval(function () {

            var obj = _self.data;

            var aaa = parseInt(obj["time"]);
            var bbb = parseInt(new Date().getTime() / 1000);

            var rightTime = aaa - bbb;

            if (rightTime > 0) {
                var dd = Math.floor(rightTime / 60 / 60 / 24);
                var hh = Math.floor((rightTime / 60 / 60) % 24);
                var mm = Math.floor((rightTime / 60) % 60);
                var ss = Math.floor((rightTime) % 60);
                if (dd === 0) {
                    obj["djs"] = hh + "小时" + mm + "分" + ss + "秒";
                } else {
                    obj["djs"] = dd + "天" + hh + "小时" + mm + "分" + ss + "秒";
                }
            } else {
                obj["djs"] = '已结束';
            }
        }, 1000);
    },
    methods: {
        getData: function (id) {
            var _self = this;
            base.ajax({
                url: app_config.API_URL + 'product/' + id
            }, function (data) {
                if (!data) {
                    window.location.href = app_config.WEB_URL + '/';
                }

                var standards = data.standards;
                data.is_on_hand = false;
                if (standards.length > 0) {
                    for (var n = 0; n < standards.length; n++) {
                        var onhand = standards[n].onhand;
                        data.is_on_hand = onhand > 0;
                    }
                }

                _self.data = data;
                _self.curStandards = data.standards[0];

                _self.$nextTick(function () {
                    var mySwiper = new Swiper('.swiper-container', {
                        autoplay: 3000,
                        pagination: '.swiper-pagination',
                        paginationClickable: true,
                        loop: true
                    });
                });

                // 过期时间戳
                var obj = data;
                var date = new Date(obj.updated_at.replace(/-/g, '/'));
                var time = Date.parse(date) / 1000;
                obj.time = time + obj.time_limit;
                _self.$set(
                    obj, "djs", base.InitTime(obj.time)
                );


                var origin = window.location.origin;
                var pathname = window.location.pathname;
                var id = parseInt(base.getQuery().id);
                var f = parseInt(base.getQuery().f);
                var s = parseInt(base.getQuery().s);
                var uid = parseInt(base.wxInfo.id);
                _self.u = '?id=' + id + '&';
                if (f === uid || s === uid) {
                    _self.u += 'f=' + f + '&s=' + s;
                } else {
                    _self.u += 'f=' + uid + '&s=' + f;
                }

                //加载海报
                base.ajax({
                    url: app_config.API_URL + 'poster' + _self.u
                }, function (data) {});

                var shareData = {
                    title: data.name,
                    desc: data.subtitle,
                    link: origin + pathname + _self.u,
                    imgUrl: base.cosPic(data.pics[0].name, 100, 100)
                };

                wx.ready(function () {

                    wx.onMenuShareTimeline({
                        title: shareData.title, // 分享标题
                        link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: shareData.imgUrl, // 分享图标
                        success: function () {
                            // 用户点击了分享后执行的回调函数
                        }
                    });

                    wx.onMenuShareAppMessage({
                        title: shareData.title, // 分享标题
                        desc: shareData.desc, // 分享描述
                        link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: shareData.imgUrl, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
// 用户点击了分享后执行的回调函数
                        }
                    });

                    // //自定义“分享给朋友”及“分享到QQ”按钮的分享内容
                    // wx.updateAppMessageShareData({
                    //     title: shareData.title, // 分享标题
                    //     desc: shareData.desc, // 分享描述
                    //     link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    //     imgUrl: shareData.imgUrl, // 分享图标
                    //     success: function () {
                    //         // 设置成功
                    //     }
                    // });
                    //
                    //
                    //
                    // //自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容
                    // wx.updateTimelineShareData({
                    //     title: shareData.title, // 分享标题
                    //     link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    //     imgUrl: shareData.imgUrl, // 分享图标
                    //     success: function () {
                    //         // 设置成功
                    //     }
                    // });

                });

            });
        },
		/**
		 * 获取分享顶部文字
		 */
		getContent:function(){
			var _self = this;
			base.ajax({
				url: app_config.API_URL + 'tip',
				data:{
					type:'scan_content'
				}
			}, function (data) {
				console.log(data);
				_self.topContent = data.value.content;
			});
		},
        curStandardsFn: function (item) {
            var _self = this;
            _self.curStandards = item;
        },
        close: function () {
            var _self = this;
            _self.floatR = false;
        },
        poster: function () {
            var _self = this;

            var dom = $('.details-box');
            var wh = $(window).height();
            var dh = dom.height() + parseFloat(dom.css('padding-top')) + parseFloat(dom.css('margin-bottom'));

            _self.poster_s_style = {
                'height': wh > dh ? wh : dh + 'px'
            };

            _self.is_poster_s = true;
        },
        poster_show: function () {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'poster' + _self.u,
                layer: true
            }, function (data) {
                window.location.href = app_config.WEB_URL + 'poster?name=' + data.name + '&id=' + _self.data.id;
            })
        },
        poster_close: function () {
            var _self = this;
            _self.is_poster_s = false;
        },
        buy: function (item) {
            var _self = this;
            _self.curStandards.u = {
                id: item.id,
                f: parseInt(base.getQuery().f),
                s: parseInt(base.getQuery().s)
            };
            localStorage.setItem('curStandards', JSON.stringify(_self.curStandards));

            window.location.href = app_config.WEB_URL + 'payment_order';


        },
        openLocation: function () {
            var _self = this;
            wx.ready(function () {

                //使用微信内置地图查看位置接口
                wx.openLocation({
                    latitude: Number(_self.data.business.lat),
                    longitude: Number(_self.data.business.lng),
                    name: _self.data.business.name,
                    address: _self.data.business.address,
                    scale: 20,
                    infoUrl: ''
                });

                // wx.getLocation({
                //     type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                //     success: function (res) {
                //         wx.openLocation({
                //             latitude: res.latitude, // 纬度，浮点数，范围为90 ~ -90
                //             longitude: res.longitude, // 经度，浮点数，范围为180 ~ -180。
                //             name: '我的位置', // 位置名
                //             address: '329创业者社区', // 地址详情说明
                //             scale: 28, // 地图缩放级别,整形值,范围从1~28。默认为最大
                //             infoUrl: 'http://www.gongjuji.net' // 在查看位置界面底部显示的超链接,可点击跳转（测试好像不可用）
                //         });
                //     },
                //     cancel: function (res) {
                //
                //     }
                // });
            });
        },
        styles: function (path, width, height) {
            var paths = base.cosPic(path, width, height);
            return {
                'background': 'url(' + paths + ') center bottom / cover no-repeat'
            }
        },
        app_ewm: function () {
            layer.open({
                title: '长按识别二维码关注',
                offset: '1rem',
                btn: 0,
                content: '<div style="text-align: center;"><img style="width: 200px;" src="' + app_ewm + '"></div>',
                yes: function (index) {
                    layer.close(index);
                }
            });
        }
    }
});