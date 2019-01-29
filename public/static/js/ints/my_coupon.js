new Vue({
    el: '#my_couponApp',
    data: {
        wxInfo: base.wxInfo,
        type: 1,
        data: [],
        data_length: 0,

        is_poster_s: false
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '我的优惠券');

        var zhuanzeng = {
            coupon_id: base.getQuery().coupon_id,
            from_uid: base.getQuery().from_uid
        };

        if (zhuanzeng.coupon_id && zhuanzeng.from_uid) {

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'lottery/my',
                data: {
                    type: 2,
                    coupon_id: zhuanzeng.coupon_id,
                    from_uid: zhuanzeng.from_uid
                },
                no_code: true
            }, function (data) {
                if (data.code === '0000') {
                    base.layer.msg('接收转赠成功', 6);
                }
                _self.getData(1);
            });

        } else {
            _self.getData(1);
        }

        console.log(zhuanzeng)


    },
    methods: {
        getData: function (isOne) {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'lottery/my?type=' + _self.type
            }, function (data) {
                _self.data = data;
                if (isOne === 1) {
                    _self.data_length = data.length;
                }
            });
        },
        tab: function (type) {
            var _self = this;
            _self.data = [];
            _self.type = type;
            _self.getData();
        },
        details: function (item) {
            var _self = this;
            base.ajax({
                url: app_config.API_URL + 'lottery/my/' + item.id
            }, function (data) {

                layer.open({
                    type: 1,
                    area: [$(window).width() + 'px', $(window).height() + 'px'],
                    title: 0,
                    closeBtn: 0,
                    content: '<div class="details_ddf">' +
                    '<div class="d1">【' + data.lottery_draw.business.name + '】 ' + data.prize.name + '</div>' +

                    '<div class="d2">' +
                    '<img src="' + data.qrCode + '" alt=""><br>券码：' + data.code + '' +
                    '</div>' +

                    '<div class="d3">备注：' + data.prize.use_condition + '</div>' +

                    '<div class="d4"><button id="dslfkslds">转赠给他人</button></div>' +

                    '</div>',
                    success: function () {
                        $('#dslfkslds').on('click', function () {

                            _self.poster_s_style = {
                                'height': $(window).height() + 'px',
                                'z-index': 198910151
                            };

                            _self.is_poster_s = true;

                            wx.ready(function () {

                                var shareData = {
                                    title: '【转赠】' + data.lottery_draw.business.name + ' - ' + data.prize.name,
                                    desc: '点击获取优惠券',
                                    imgUrl: base.wxInfo.headimgurl,
                                    link: window.location.search === '' ?
                                        (window.location.href + '?coupon_id=' + data.id + '&from_uid=' + base.wxInfo.id) :
                                        (window.location.href + '&coupon_id=' + data.id + '&from_uid=' + base.wxInfo.id)
                                };

                                wx.onMenuShareTimeline({
                                    title: shareData.title, // 分享标题
                                    link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                                    imgUrl: shareData.imgUrl, // 分享图标
                                    success: function () {

                                        base.ajax({
                                            type: 'post',
                                            url: app_config.API_URL + 'lottery/my',
                                            data: {
                                                type: 1,
                                                coupon_id: data.id
                                            }
                                        }, function () {
                                            base.layer.msg('转赠成功', 6);
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 500);
                                        });

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

                                        base.ajax({
                                            type: 'post',
                                            url: app_config.API_URL + 'lottery/my',
                                            data: {
                                                type: 1,
                                                coupon_id: data.id
                                            }
                                        }, function () {
                                            base.layer.msg('转赠成功', 6);
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 500);
                                        });

                                    }
                                });
                            });

                        })
                    }
                })
            });
        }
    }
});