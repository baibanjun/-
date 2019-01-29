new Vue({
    el: '#payment_orderApp',
    data: {
        data: {pics: [{name: ''}]},
        curStandards: {},
        name: '',
        tel: '',
        remark: '',
        area_code: '',
        address: '',
        quantity: 1,
        total: '0.00',

        area_province: [],
        area_province_cur: '',
        area_city: [],
        area_city_cur: '',
        area_county: [],
        area_county_cur: ''
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '吃喝玩乐成都联盟·爆品详情');

        _self.curStandards = JSON.parse(localStorage.getItem('curStandards'));

        localStorage.removeItem('curStandards');

        if (!_self.curStandards) {
            window.history.go(-1);
        } else {
            _self.getData();
        }

        _self.total = _self.curStandards.sale_price;

        $.ajax({
            url: three_area,
            success: function (data) {

                _self.area_province = data;
                _self.area_province_cur = data[0].name;

                _self.area_city = data[0].items;
                _self.area_city_cur = data[0].items[0].name;

                _self.area_county = data[0].items[0].items;
                _self.area_county_cur = data[0].items[0].items[0].name;

            }
        });


    },
    methods: {
        findItemsForName: function (data, name) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].name === name) {
                    return data[i];
                }
            }
        },
        area_change: function (lv) {
            var _self = this;
            var data = [];

            if (lv === 1) {
                data = _self.findItemsForName(_self.area_province, _self.area_province_cur);

                _self.area_city = data.items;
                _self.area_city_cur = data.items[0].name;

                _self.area_county = data.items[0].items;
                _self.area_county_cur = data.items[0].items[0].name;
            }

            if (lv === 2) {
                data = _self.findItemsForName(_self.area_city, _self.area_city_cur);

                _self.area_county = data.items;
                _self.area_county_cur = data.items[0].name;
            }


        },
        getData: function () {
            var _self = this;
            base.ajax({
                url: app_config.API_URL + 'product/' + _self.curStandards.u.id
            }, function (data) {
                _self.data = data;
            });
        },
        ct: function (type) {
            var _self = this;
            if (type === 1) {
                if (_self.quantity <= 1) {
                } else {
                    _self.quantity--;
                }
            }
            if (type === 2) {
                if (_self.quantity >= _self.curStandards.onhand) {
                } else {
                    _self.quantity++;
                }
            }

            _self.total = base.getNum(base.moneyOperation.mul(_self.curStandards.sale_price, _self.quantity));

        },
        buy: function () {
            var _self = this;

            var ajaxData = {
                product_id: _self.curStandards.u.id,
                uid: base.wxInfo.id,
                standard_id: _self.curStandards.id,
                quantity: _self.quantity,
                name: _self.name,
                tel: _self.tel,
                remark: _self.remark,
                area_code: _self.findItemsForName(_self.area_county, _self.area_county_cur).code,
                address: _self.address,
                f: _self.curStandards.u.f,
                s: _self.curStandards.u.s
            };

            if (!ajaxData.name) {
                base.layer.msg('收货人姓名不能为空');
                return false;
            }

            if (!ajaxData.tel) {
                base.layer.msg('收货人手机号码不能为空');
                return false;
            }

            if (!base.el["101"].q.test(ajaxData.tel)) {
                base.layer.msg(base.el["101"].a);
                return false;
            }

            if (_self.data.type === 3 && !ajaxData.address) {
                base.layer.msg('收货人详细地址不能为空');
                return false;
            }

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'order',
                data: ajaxData,
                layer: true
            }, function (data) {

                var order_id = data.id;

                base.ajax({
                    type: 'post',
                    url: app_config.API_URL + 'wx_pay',
                    data: {
                        order_id: data.id
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
                                    window.location.href = app_config.WEB_URL + 'u_order?id=' + order_id;
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

            });


        }
    }
});