(function (global) {
    function Base() {
        return Base.fn()
    }

    Base.fn = Base.prototype = {
        constructor: Base
    };

    $('body').css('opacity', 1);

    Base.ajax = function (opt, success, error, complete) {

        var isAjaxLayer = $('.isRotateLayer').hasClass('layui-layer');

        if (!isAjaxLayer && opt.layer) {

            var ajaxLayer = layer.open({
                    type: 1,
                    title: 0,
                    closeBtn: 0,
                    btn: 0,
                    skin: 'isRotateLayer',
                    area: ['2.2rem', '2.2rem'],
                    shade: 0.01,
                    content: '<div style="width: 30px;height: 30px;margin: 0 auto;margin-top: 0.8rem;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></div>'
                }
            );

        }

        var $data = {
            random: Base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
            timestamp: Date.parse(new Date()) / 1000
        };

        var encrypt = new JSEncrypt();
        encrypt.setPublicKey();
        var encryptData = encrypt.encrypt(JSON.stringify($data));

        $.ajax({
            type: opt.type || 'get',
            url: opt.url,
            data: opt.data || {},
            dataType: opt.no_code ? (opt.no_code ? opt.data_type : '') : 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                sign: encryptData,
                random: $data.random,
                timestamp: $data.timestamp,
                token: Base.wxInfo.token
            },
            success: function (data) {
                if (success && typeof success === 'function') {
                    if (opt.no_code) {
                        success(data);
                    } else {
                        if (data.code === '0000') {
                            success(data.data);
                        } else if (data.code === '0014' || data.code === '0017') {
                            localStorage.removeItem('wxInfo');
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            Base.layer.msg(Base.code[data.code]);
                        }
                    }
                }
            },
            error: function () {
                if (error && typeof error === 'function') {
                    error('服务器繁忙，请稍后再试');
                }
            },
            complete: function () {
                layer.close(ajaxLayer);
                if (complete && typeof complete === 'function') {
                    complete();
                }
            }
        })
    };

    //layer 自定义
    Base.layer = {
        msg: function (data, icon) { //1:√  2:X  3:?  4:锁 5:哭  6:笑 7:!
            layer.msg(data, {
                icon: icon || 2
            });
        }
    };

    //url取query
    Base.getQuery = function (url) {
        var res = {};
        var urls = url ? url : window.location.search;
        $.each(urls.substr(1).split('&'), function (n, i) {
            var j = i.split('=');
            res[j[0]] = j[1];
        });
        return res;
    };
    //uuid
    Base.uuid = function (len, radix) {
        var timestamp = new Date().getTime();
        var char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' + timestamp;
        var chars = char.split('');

        var uuid = [],
            i;
        radix = radix || chars.length;

        if (len) {
            // Compact form
            for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
        } else {
            // rfc4122, version 4 form
            var r;

            // rfc4122 requires these characters
            uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
            uuid[14] = '4';

            // Fill in random data.  At i==19 set the high bits of clock sequence as
            // per rfc4122, sec. 4.1.5
            for (i = 0; i < 36; i++) {
                if (!uuid[i]) {
                    r = 0 | Math.random() * 16;
                    uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                }
            }
        }

        return uuid.join('');
    };

    //
    Base.moneyOperation = {
        add: function (a, b) {
            var c, d, e;
            try {
                c = a.toString().split(".")[1].length;
            } catch (f) {
                c = 0;
            }
            try {
                d = b.toString().split(".")[1].length;
            } catch (f) {
                d = 0;
            }
            return e = Math.pow(10, Math.max(c, d)), (this.mul(a, e) + this.mul(b, e)) / e;
        },
        sub: function (a, b) {
            var c, d, e;
            try {
                c = a.toString().split(".")[1].length;
            } catch (f) {
                c = 0;
            }
            try {
                d = b.toString().split(".")[1].length;
            } catch (f) {
                d = 0;
            }
            return e = Math.pow(10, Math.max(c, d)), (this.mul(a, e) - this.mul(b, e)) / e;
        },
        mul: function (a, b) {
            if (a === undefined || b === undefined) return false;
            var c = 0,
                d = a.toString(),
                e = b.toString();
            try {
                c += d.split(".")[1].length;
            } catch (f) {
            }
            try {
                c += e.split(".")[1].length;
            } catch (f) {
            }
            return Number(d.replace(".", "")) * Number(e.replace(".", "")) / Math.pow(10, c);
        },
        div: function (a, b) {
            var c, d, e = 0,
                f = 0;
            try {
                e = a.toString().split(".")[1].length;
            } catch (g) {
            }
            try {
                f = b.toString().split(".")[1].length;
            } catch (g) {
            }
            return c = Number(a.toString().replace(".", "")), d = Number(b.toString().replace(".", "")), this.mul(c / d, Math.pow(10, f - e));
        }
    };

    // 小数点后六位
    Base.getNum = function (num) {
        num = num || 0;
        num = num.toString();
        var arr = num.split('.');
        if (arr.length === 1) {
            arr.push('00')
        } else {
            var a1 = arr[1].length;
            if (a1 < 6) {
                arr[1] = arr[1] + '00';
            }
        }
        var re = /([0-9]+\.[0-9]{2})[0-9]*/;
        return arr.join('.').replace(re, "$1");
    };

    /**
     * @return {string}
     */
    Base.InitTime = function (endtime) {
        var dd, hh, mm, ss = null;
        var time = parseInt(endtime) - parseInt(new Date().getTime() / 1000);
        if (time <= 0) {
            return '已结束'
        }
        dd = Math.floor(time / 60 / 60 / 24);
        hh = Math.floor((time / 60 / 60) % 24);
        mm = Math.floor((time / 60) % 60);
        ss = Math.floor(time % 60);
        if (dd === 0) {
            return hh + "小时" + mm + "分" + ss + "秒";
        }
        return dd + "天" + hh + "小时" + mm + "分" + ss + "秒";
    };

    Base.cosPic = function (name, width, height) {
        return app_config.PIC_URL + name + '?imageView2/2/w/' + (width ? width : 0) + '/h/' + (height ? height : 0);
    };

    Base.cosPic1 = function (name, width, height) {
        return app_config.PIC_URL + name + '?imageView2/1/w/' + (width ? width : 0) + '/h/' + (height ? height : 0);
    };

    //
    Base.el = {
        '101': {
            q: /^0*(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[2|3|4|5|6|7|8|9]|18[0-9]|19[0-9])\d{8}$/,
            a: '手机号码格式错误'
        },
        '102': {
            q: /^\+?[1-9][0-9]*$/,
            a: '请输入大于0的数字'
        }
    };

    Base.code = {
        '0000': '成功',
        '0001': '失败',
        '0002': '数据不存在',
        '0003': '数据已存在',
        '0004': '状态不正常',
        '0005': '金额不够',
        '0006': '金额太小',
        '0007': '有正在处理的提现',
        '0008': '角色已存在',
        '0009': '密码不正确',
        '0010': '已核销过了',
        '0011': '微信认证code错误',
        '0012': '微信认证access_token错误',
        '0013': '微信认证使用refresh_token请求时错误',
        '0014': '微信须要重新授权',
        '0015': '微信支付失败',
        '0016': '用户状态不正常',
        '0017': 'API认证失败,检查token',
        '0018': 'API认证失败,检查时间差',
        '0019': 'API认证失败,检查签名在配制的最大时间内使用过',
        '0020': 'API认证失败,签名解决失败',
        '0021': '验证码数据不存在',
        '0022': '验证码错误',
        '0023': '未关注公众号',
        '0024': '商家状态不正常',
        '0025': '产品状态不正常',
        '0026': '库存不足',
        '0027': '达人状态不正常',
        '0028': '已在其它团队',
        '0029': '产品倒计时结束',
        '0030': '队长和队员不能相同',
        '0031': '订单已过期',
        '0032': '活动不存在或者已结束',
        '0033': '奖品没有了',
        '0034': '抽奖次数不够',
        '0035': '优惠卷不存在',
        '0036': '商家申请已存在,不能再申请',
        '0037': '优惠卷已过期',
        '0038': '您分享得太晚，奖品已被他人领走了'
    };


    //微信授权
    if (app_config.WEB_URL === 'http://www.chwl.loc/') {
        localStorage.setItem('wxInfo', '{"id":7,"openid":"oJStm6KLrGFTf9ybQzgpbkcvtjbk","nickname":"9526🔥","sex":1,"language":"zh_CN","city":"成都","province":"四川","country":"中国","headimgurl":"http://thirdwx.qlogo.cn/mmopen/vi_32/55VfXtu7EOT8bheElohGRicicA8IIz1jEHu4AmLgsib9vO8U1AdDibR7tgE3tRlrwiaJawPNEZmWRI31nHdrIrp2oxQ/132","role":0,"qr_code":null,"status":0,"salt":"5c1c8fbec87f3","inviter":0,"created_at":"2018-12-14 11:13:28","updated_at":"2018-12-21 15:01:18","access_token":"16_c5M6JgBpM7SGCgKGHdJmORwsr-qklqbwgPSpkm1097reLl-kxaGeLrxrOiXhaV5JyB39WJJCJ3eH7fVIZzGOsg","token":"7a9177f9a25b40a530095b0e4a6afb990e94a7334540a50cb7a7c5793f903a48"}');
    }

    var code = Base.getQuery().code;
    var wxInfo = localStorage.getItem('wxInfo');
    var jump_url = window.location.href;
    if (!wxInfo) {

        if (!code) {
            window.location.href = app_config.API_URL + 'index';
            localStorage.setItem('jump_url', jump_url);
        } else {
            $.ajax({
                type: 'post',
                url: app_config.API_URL + 'index',
                data: {
                    code: code
                },
                success: function (data) {
                    if (data.code === '0000') {
                        localStorage.setItem('wxInfo', JSON.stringify(data.data));

                        window.location.href = localStorage.getItem('jump_url') || app_config.WEB_URL;
                    } else {
                        var j = localStorage.getItem('jump_url');
                        localStorage.removeItem('wxInfo');
                        localStorage.removeItem('jump_url');
                        setTimeout(function () {
                            window.location.href = j || app_config.WEB_URL;
                        }, 500);
                    }
                }
            });
        }

    } else {

        localStorage.removeItem('jump_url');
        var wxInfoObj;
        Base.wxInfo = wxInfoObj = JSON.parse(wxInfo);
        Base.wxConfig = wxConfig = {
            appId: app_config.APP_ID,
            noncestr: 'nihao123',
            timestamp: (Date.parse(new Date()) / 1000 + 7200).toString(),
            url: window.location.href
        };


        Base.ajax({
            url: app_config.API_URL + 'jsapi_ticket'
        }, function (data) {

            wxConfig.ticket = data.ticket;

            var shareData = {
                title: '吃喝玩乐',
                desc: '好货不断',
                imgUrl: wxInfoObj.headimgurl
            };

            var signatureString = 'jsapi_ticket=' + wxConfig.ticket + '&noncestr=' + wxConfig.noncestr + '&timestamp=' + wxConfig.timestamp + '&url=' + wxConfig.url + '';

            wx.config({
                debug: false,
                // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: wxConfig.appId,
                // 必填，公众号的唯一标识
                timestamp: wxConfig.timestamp,
                // 必填，生成签名的时间戳
                nonceStr: wxConfig.noncestr,
                // 必填，生成签名的随机串
                signature: hex_sha1(signatureString),
                // 必填，签名
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'updateAppMessageShareData', 'updateTimelineShareData', 'getLocation', 'openLocation', 'chooseWXPay'] // 必填，需要使用的JS接口列表
            });

            wx.ready(function () {

                if (app_config.CUR_URL !== 'details' && app_config.CUR_URL !== 'lottery' && app_config.CUR_URL !== 'poster2' && app_config.CUR_URL !== 'my_coupon') {

                    wx.onMenuShareTimeline({
                        title: shareData.title, // 分享标题
                        link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: shareData.imgUrl, // 分享图标
                        success: function () {
                            // 用户点击了分享后执行的回调函数
                        }
                    });

                    wx.onMenuShareAppMessage({
                        title: shareData.title, // 分享标题
                        desc: shareData.desc, // 分享描述
                        link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: shareData.imgUrl, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
// 用户点击了分享后执行的回调函数
                        }
                    });

                }

            });

        });

    }


    global.base = Base;
}(this));