new Vue({
    el: '#userApp',
    data: {
        wxInfo: base.wxInfo,
        type: 1,
        data: {},
        page: 1,
        isPost: true,
        more: false,
        noProduct: false,
        details: function (id) {
            return app_config.WEB_URL + 'details?id=' + id + '&f=0&s=0'
        },
        index: '',
        is_burse: false
    },
    created: function () {
        var _self = this;

        var type = base.getQuery().type;
        _self.type = type || '1';

        if (_self.type === '1' || _self.type === '2' || _self.type === '3' || _self.type === '4') {
            _self.getData(0);
        }

        if (_self.type === 'ewm') {

            base.ajax({
                url: app_config.API_URL + 'my/index'
            }, function (data) {
                _self.index = qr_url + data.qrcode;
            });
        }

        base.ajax({
            url: app_config.API_URL + 'my/coffer',
            no_code: true
        }, function (data) {
            if (data.code === '0000') {
                _self.is_burse = true;
            }
        });

    },
    mounted: function () {
        var _self = this;
        $(document).scroll(function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 30) {
                if (_self.isPost) {
                    _self.isPost = false;
                    _self.more = true;
                    _self.getData(1);
                }
            }
        });
    },
    methods: {
        getData: function (type) {
            var _self = this;

            if (type === 0) {
                _self.page = 1;
                _self.isPost = true;
                _self.noProduct = false;
            }

            var ajaxData = {
                type: _self.type,
                page: _self.page,
                limit: 10
            };

            base.ajax({
                url: app_config.API_URL + 'my/orders',
                data: ajaxData
            }, function (data) {
                if (data.current_page === data.last_page) {
                    _self.more = false;
                    _self.isPost = false;
                }
                _self.page++;
                if (type === 0) {
                    _self.data = data;
                    if (data.data.length < 1) {
                        _self.more = false;
                        _self.noProduct = true;
                    }
                }
                if (type === 1) {
                    if (data.data.length > 0) {
                        _self.isPost = true;
                        for (var i = 0; i < data.data.length; i++) {
                            _self.data.data.push(data.data[i]);
                        }
                    }
                }
            });
        },
        u_order: function (item) {
            var _self = this;
            window.location.href = app_config.WEB_URL + 'u_order?id=' + item.id;
        },
        pay: function (item) {
            var _self = this;

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'wx_pay',
                data: {
                    order_id: item.id
                }
            }, function (data) {
                wx.ready(function () {
                    wx.chooseWXPay({
                        appId: data.appId,
                        timestamp: data.timeStamp,
                        nonceStr: data.nonceStr,
                        package: data.package,
                        signType: data.signType,
                        paySign: data.sign,
                        success: function (res) {
                            // 支付成功后的回调函数
                            if (res.errMsg === "chooseWXPay:ok") {
                                //支付成功
                                window.location.href = app_config.WEB_URL + 'u_order?id=' + item.id;
                            } else {
                                window.location.reload();
                            }
                        },
                        cancel: function (res) {
                            //支付取消
                        }
                    });
                });

            });
        }
    }
});