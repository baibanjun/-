new Vue({
    el: '#lotteryApp',
    data: {
        query_id: base.getQuery().id,
        all_data: {
            weichat_group: {
                value: {
                    group_name: '',
                    group_qr_code: {},
                    group_title: ''
                }
            },
            poster: [{
                name: ''
            }],
            business: {},
            number: {}
        },
        data_user: [],
        data_user_style: {
            'marginTop': 0
        },
        index: 0,
        speed: 100,
        is_lottery: true,
        lottery_data: {
            user_today_prize_number: {}
        },
        share_type: 0, //0分享 1领奖 2获取次数
        is_poster_s: false
    },
    created: function () {
        var _self = this;

        _self.get_all_data(true);

        _self.get_data_user();

    },
    methods: {
        get_all_data: function (type) {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'lottery/index?id=' + _self.query_id + '&type=0'
            }, function (data) {
                _self.all_data = data;
                $(document).attr('title', _self.all_data.title);

                if (type) {
                    _self.$nextTick(function () {
                        var h = 1.65;
                        var t = 0;
                        var s = setInterval(function () {
                            t -= 0.01;
                            if (Math.abs(t) >= (h * (_self.data_user.length - 3))) {
                                clearInterval(s);
                            }
                            _self.data_user_style['marginTop'] = t + 'rem';
                        }, 10)

                    })
                }

                var shareData = {
                    title: _self.all_data.title,
                    desc: _self.all_data.description,
                    link: window.location.href,
                    imgUrl: base.cosPic(_self.all_data.poster[0].name, 100, 100)
                };

                wx.ready(function () {

                    wx.onMenuShareTimeline({
                        title: shareData.title, // 分享标题
                        link: shareData.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: shareData.imgUrl, // 分享图标
                        success: function () {
                            // 用户点击了分享后执行的回调函数
                            // alert(_self.share_type);
                            _self.is_poster_s = false;

                            if (_self.share_type === 0) {
                                base.layer.msg('分享成功！', 6);

                                _self.share_type = 0;

                                _self.get_all_data();

                                _self.get_data_user();
                            }

                            if (_self.share_type === 1) {

                                base.ajax({
                                    type: 'post',
                                    url: app_config.API_URL + 'lottery/share_callback',
                                    data: {
                                        id: _self.lottery_data.coupons_id,
                                        type: 1
                                    }
                                }, function () {
                                    // alert('领奖成功');
                                });

                                if (_self.lottery_data.draw_type === 2) {
                                    _self.share_type = 0;
                                    return false;
                                }


                                if (_self.lottery_data.user_today_prize_number.number === 0) {

                                    var btn = _self.lottery_data.user_today_prize_number.is_share === 0 ? '分享获得抽奖机会' : '我的优惠券';

                                    layer.open({
                                        offset: ['2rem'],
                                        title: '',
                                        skin: 'lottery_suc',
                                        btn: [btn],
                                        content: '<div class="lottery_suc_tit">' +
                                        '<p class="p1">恭喜您</p>' +
                                        '<p class="p2">成功领取' + _self.lottery_data.name + '</p>' +
                                        '</div>' +
                                        '<div class="lottery_suc_con1">' +
                                        '<p class="p1"><span>奖励说明：</span>' + _self.lottery_data.description + '</p>' +
                                        '<p class="p2"><span>使用说明：</span>进入我们的公众号，选择菜单【我的优惠券】，即可查看中奖纪录，兑换使用时，向商家展示对应优惠券券码即可，谢谢您的支持！</p>' +
                                        '</div>',
                                        yes: function (index) {
                                            layer.close(index);
                                            if (_self.lottery_data.user_today_prize_number.is_share === 0) {
                                                _self.share_type = 2;
                                                _self.is_poster_s = true;
                                            } else {
                                                _self.share_type = 0;
                                                window.location.href = app_config.WEB_URL + 'my_coupon';
                                            }

                                        }
                                    });

                                } else {
                                    layer.open({
                                        offset: ['2rem'],
                                        title: '',
                                        skin: 'lottery_suc',
                                        btn: ['分享给好友参与', '再来一次'],
                                        content: '<div class="lottery_suc_tit">' +
                                        '<p class="p1">恭喜您</p>' +
                                        '<p class="p2">成功领取' + _self.lottery_data.name + '</p>' +
                                        '</div>' +
                                        '<div class="lottery_suc_con1">' +
                                        '<p class="p1"><span>奖励说明：</span>' + _self.lottery_data.description + '</p>' +
                                        '<p class="p2"><span>使用说明：</span>进入我们的公众号，选择菜单【我的优惠券】，即可查看中奖纪录，兑换使用时，向商家展示对应优惠券券码即可，谢谢您的支持！</p>' +
                                        '</div>',
                                        yes: function (index) {
                                            layer.close(index);
                                            _self.share_type = 0;
                                            _self.is_poster_s = true;

                                        },
                                        end: function (index) {
                                            layer.close(index);
                                            _self.share_type = 0;
                                        }
                                    });
                                }
                            }

                            if (_self.share_type === 2) {

                                base.ajax({
                                    type: 'post',
                                    url: app_config.API_URL + 'lottery/share_callback',
                                    data: {
                                        id: _self.query_id,
                                        type: 2
                                    }
                                }, function () {
                                    base.layer.msg('获取次数成功', 6);

                                    _self.get_all_data();

                                    _self.get_data_user();
                                });

                                _self.share_type = 0;
                            }
                            //
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
                            // alert(_self.share_type);
                            _self.is_poster_s = false;

                            if (_self.share_type === 0) {
                                base.layer.msg('分享成功！', 6);

                                _self.share_type = 0;

                                _self.get_all_data();

                                _self.get_data_user();
                            }

                            if (_self.share_type === 1) {

                                base.ajax({
                                    type: 'post',
                                    url: app_config.API_URL + 'lottery/share_callback',
                                    data: {
                                        id: _self.lottery_data.coupons_id,
                                        type: 1
                                    }
                                }, function () {
                                    // alert('领奖成功');
                                });

                                if (_self.lottery_data.draw_type === 2) {
                                    _self.share_type = 0;
                                    return false;
                                }


                                if (_self.lottery_data.user_today_prize_number.number === 0) {

                                    var btn = _self.lottery_data.user_today_prize_number.is_share === 0 ? '分享获得抽奖机会' : '我的优惠券';

                                    layer.open({
                                        offset: ['2rem'],
                                        title: '',
                                        skin: 'lottery_suc',
                                        btn: [btn],
                                        content: '<div class="lottery_suc_tit">' +
                                        '<p class="p1">恭喜您</p>' +
                                        '<p class="p2">成功领取' + _self.lottery_data.name + '</p>' +
                                        '</div>' +
                                        '<div class="lottery_suc_con1">' +
                                        '<p class="p1"><span>奖励说明：</span>' + _self.lottery_data.description + '</p>' +
                                        '<p class="p2"><span>使用说明：</span>进入我们的公众号，选择菜单【我的优惠券】，即可查看中奖纪录，兑换使用时，向商家展示对应优惠券券码即可，谢谢您的支持！</p>' +
                                        '</div>',
                                        yes: function (index) {
                                            layer.close(index);
                                            if (_self.lottery_data.user_today_prize_number.is_share === 0) {
                                                _self.share_type = 2;
                                                _self.is_poster_s = true;
                                            } else {
                                                _self.share_type = 0;
                                                window.location.href = app_config.WEB_URL + 'my_coupon';
                                            }

                                        }
                                    });

                                } else {
                                    layer.open({
                                        offset: ['2rem'],
                                        title: '',
                                        skin: 'lottery_suc',
                                        btn: ['分享给好友参与', '再来一次'],
                                        content: '<div class="lottery_suc_tit">' +
                                        '<p class="p1">恭喜您</p>' +
                                        '<p class="p2">成功领取' + _self.lottery_data.name + '</p>' +
                                        '</div>' +
                                        '<div class="lottery_suc_con1">' +
                                        '<p class="p1"><span>奖励说明：</span>' + _self.lottery_data.description + '</p>' +
                                        '<p class="p2"><span>使用说明：</span>进入我们的公众号，选择菜单【我的优惠券】，即可查看中奖纪录，兑换使用时，向商家展示对应优惠券券码即可，谢谢您的支持！</p>' +
                                        '</div>',
                                        yes: function (index) {
                                            layer.close(index);
                                            _self.share_type = 0;
                                            _self.is_poster_s = true;

                                        },
                                        end: function (index) {
                                            layer.close(index);
                                            _self.share_type = 0;
                                        }
                                    });
                                }
                            }

                            if (_self.share_type === 2) {

                                base.ajax({
                                    type: 'post',
                                    url: app_config.API_URL + 'lottery/share_callback',
                                    data: {
                                        id: _self.query_id,
                                        type: 2
                                    }
                                }, function () {
                                    base.layer.msg('获取次数成功', 6);

                                    _self.get_all_data();

                                    _self.get_data_user();
                                });

                                _self.share_type = 0;
                            }
                            //
                        }
                    });

                });
            });
        },
        get_data_user: function () {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'lottery/index?id=' + _self.query_id + '&type=1'
            }, function (data) {
                _self.data_user = data;
            });
        },
        weichat_group: function () {
            var _self = this;

            var pic = base.cosPic(_self.all_data.weichat_group.value.group_qr_code.name, 280);
            if ($('#weichat_group_pic').length === 0) {
                $('body').append('<img style="display: none;" id="weichat_group_pic" src="' + pic + '">');
            }

            var t = setInterval(function () {
                var weichat_group_pic = $('#weichat_group_pic');
                if (weichat_group_pic.height() !== 0) {


                    layer.open({
                        type: 1,
                        title: 0,
                        // closeBtn: false,
                        skin: 'weichat_group_pic',
                        area: [weichat_group_pic.width() + 'px', weichat_group_pic.height() + 'px'],
                        content: '<img style="width: ' + weichat_group_pic.width() + 'px;" src="' + pic + '">'
                    });

                    clearInterval(t);
                }

            }, 10);

        },
        styles: function () {
            var _self = this;
            var url = base.cosPic(_self.all_data.poster[0].name, 500);
            return {
                'background': 'url(' + url + ') no-repeat center top',
                'background-size': 'cover'
            }

        },
        lottery: function () {
            var _self = this;

            if (_self.is_lottery) {

                _self.is_lottery = false;

                _self.index = 1;

                var set1 = setInterval(fn, _self.speed);

                function fn() {
                    var len = _self.all_data.lottery_type === 2 ? 6 : 8;

                    if (_self.speed < 30) {
                        _self.speed = 30;
                    } else {
                        _self.speed--;
                    }

                    if (_self.index === len) {
                        _self.index = 1;
                    } else {
                        _self.index++;
                    }

                    var dom = $('.lottery_top_aa_b');

                    switch (_self.index) {
                        case 1:
                            dom.css('transform', 'rotate(30deg)');
                            break;
                        case 2:
                            dom.css('transform', 'rotate(90deg)');
                            break;
                        case 3:
                            dom.css('transform', 'rotate(150deg)');
                            break;
                        case 4:
                            dom.css('transform', 'rotate(-150deg)');
                            break;
                        case 5:
                            dom.css('transform', 'rotate(-90deg)');
                            break;
                        case 6:
                            dom.css('transform', 'rotate(-30deg)');
                            break;
                        default:

                    }

                    clearInterval(set1);

                    if (_self.speed > 0) {
                        set1 = setInterval(fn, _self.speed);
                    }
                }


                setTimeout(function () {
                    base.ajax({
                        type: 'post',
                        url: app_config.API_URL + 'lottery/index',
                        data: {
                            id: _self.all_data.id
                        },
                        no_code: true
                    }, function (data) {

                        clearInterval(set1);
                        _self.is_lottery = true;
                        _self.speed = 100;

                        if (data.code === '0000') {

                            _self.lottery_data = data.data;

                            for (var i = 0; i < _self.all_data.lottery_draw_list.length; i++) {
                                var d = _self.all_data.lottery_draw_list[i];
                                if (d.id === data.data.id) {
                                    _self.index = i + 1;


                                    var dom = $('.lottery_top_aa_b');
                                    switch (_self.index) {
                                        case 1:
                                            dom.css('transform', 'rotate(30deg)');
                                            break;
                                        case 2:
                                            dom.css('transform', 'rotate(90deg)');
                                            break;
                                        case 3:
                                            dom.css('transform', 'rotate(150deg)');
                                            break;
                                        case 4:
                                            dom.css('transform', 'rotate(-150deg)');
                                            break;
                                        case 5:
                                            dom.css('transform', 'rotate(-90deg)');
                                            break;
                                        case 6:
                                            dom.css('transform', 'rotate(-30deg)');
                                            break;
                                        default:

                                    }

                                }
                            }

                            _self.get_all_data();

                            _self.get_data_user();

                            if (_self.lottery_data.draw_type === 2) {//未中奖

                                if (_self.lottery_data.user_today_prize_number.number === 0) {

                                    if (_self.lottery_data.user_today_prize_number.is_share === 0) {
                                        //获取次数
                                        layer.open({
                                            offset: ['2rem'],
                                            title: '',
                                            skin: 'lottery_suc',
                                            btn: ['分享获得抽奖机会'],
                                            content: '<div class="lottery_suc_con">' +
                                            '<p class="p1">未中奖</p>' +
                                            '<p class="p2">机会已用完，分享获得额外机会</p>' +
                                            '</div>',
                                            yes: function (index) {
                                                layer.close(index);
                                                _self.share_type = 1;

                                                //
                                                var dom = $('#lotteryApp');
                                                var wh = $(window).height();
                                                var dh = dom.height() + parseFloat(dom.css('padding-top')) + parseFloat(dom.css('margin-bottom'));
                                                _self.poster_s_style = {
                                                    'height': wh > dh ? wh : dh + 'px'
                                                };
                                                _self.is_poster_s = true;
                                            }
                                        });

                                    } else {

                                        layer.open({
                                            offset: ['2rem'],
                                            title: '',
                                            skin: 'lottery_suc',
                                            btn: ['我的优惠券'],
                                            content: '<div class="lottery_suc_con">' +
                                            '<p class="p1">很遗憾，未中奖</p>' +
                                            '<p class="p2">今天的机会已经用完，明天再来哦！</p>' +
                                            '</div>',
                                            yes: function (index) {
                                                layer.close(index);
                                                window.location.href = app_config.WEB_URL + 'my_coupon';
                                            }
                                        });

                                    }

                                } else {

                                    layer.open({
                                        offset: ['2rem'],
                                        title: '',
                                        skin: 'lottery_suc',
                                        btn: ['再来一次'],
                                        content: '<div class="lottery_suc_con">' +
                                        '<p class="p1">未中奖</p>' +
                                        '<p class="p2">还有' + _self.lottery_data.user_today_prize_number.number + '次机会</p>' +
                                        '</div>',
                                        yes: function (index) {
                                            layer.close(index);
                                        }
                                    });
                                }

                            }

                            if (_self.lottery_data.draw_type === 1) {//中奖

                                //展示抽奖结果
                                layer.open({
                                    offset: ['2rem'],
                                    title: '',
                                    skin: 'lottery_suc',
                                    btn: ['点我领奖哦'],
                                    content: '<div class="lottery_suc_tit active">' +
                                    '<p class="p1">【' + _self.lottery_data.business.name + '】' + _self.lottery_data.name + '</p>' +
                                    '<p class="p2">' + _self.lottery_data.use_condition + '</p>' +
                                    '</div>' +
                                    '<div class="lottery_suc_con">' +
                                    '<p class="p1">恭喜您，中奖啦！</p>' +
                                    '<p class="p2">' + _self.lottery_data.name + '</p>' +
                                    '</div>',
                                    yes: function (index) {
                                        layer.close(index);
                                        _self.share_type = 1;

                                        //
                                        var dom = $('#lotteryApp');
                                        var wh = $(window).height();
                                        var dh = dom.height() + parseFloat(dom.css('padding-top')) + parseFloat(dom.css('margin-bottom'));
                                        _self.poster_s_style = {
                                            'height': wh > dh ? wh : dh + 'px'
                                        };
                                        _self.is_poster_s = true;

                                    }
                                });
                            }

                        } else {
                            base.layer.msg(base.code[data.code]);
                        }

                    });

                }, 5000);


            }
        },
        openLocation: function () {
            var _self = this;
            wx.ready(function () {

                //使用微信内置地图查看位置接口
                wx.openLocation({
                    latitude: Number(_self.all_data.business.lat),
                    longitude: Number(_self.all_data.business.lng),
                    name: _self.all_data.business.name,
                    address: _self.all_data.business.address,
                    scale: 20,
                    infoUrl: ''
                });
            });
        },
        get_share_3: function () {
            var _self = this;
            //
            var dom = $('#lotteryApp');
            var wh = $(window).height();
            var dh = dom.height() + parseFloat(dom.css('padding-top')) + parseFloat(dom.css('margin-bottom'));
            _self.poster_s_style = {
                'height': wh > dh ? wh : dh + 'px'
            };
            _self.share_type = 2;
            _self.is_poster_s = true;
        }
    }
});