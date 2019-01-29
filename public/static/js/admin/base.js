(function (global) {
    function Base() {
        return Base.fn()
    }

    Base.fn = Base.prototype = {
        constructor: Base
    };

    //layer 自定义
    Base.layer = {
        msg: function (data, icon) { //1:√  2:X  3:?  4:锁 5:哭  6:笑 7:!
            layer.msg(data, {
                icon: icon || 2
            });
        }
    };
    //手机号验证
    Base.isPhone = function(num){
        var reg = /^[1][3,4,5,7,8,9][0-9]{9}$/;
        if(!reg.test(num)){
            Base.layer.msg('手机号格式有误');
            return false;
        }
    }

    //AJAX请求规范
    Base.ajax = function (opt, success, error, complete) {
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
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                sign: encryptData,
                random: $data.random,
                timestamp: $data.timestamp,
                token: $.cookie('chwlToken')
            },
            success: function (data) {
                if (success && typeof success === 'function') {
                    if (data.code === '0000') {
                        success(data.data);
                    }
                    else if (data.code === '1004') {
                        window.location.href = '/web_admin/login';
                    } else {
                        Base.layer.msg(Base.lg[data.code]);
                    }
                }
            },
            error: function () {
                if (error && typeof error === 'function') {
                    error('服务器繁忙，请稍后再试');
                }
            },
            complete: function () {
                if (complete && typeof complete === 'function') {
                    complete();
                }
            }
        })
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

    function docH() {
        var wh = $(window).height();
        var $h = $('.header-time').height() + $('.header').height();
        $('.content-left,.content-right').css('height', wh - $h - 80);
    }

    docH();

    //语言包
    Base.lg = {
        '404': '页面出错啦',
        'unKnown': '未知错误',
        //请求返回码
        "0000": "成功",
        "0001": "失败",
        "0002": "数据不存在",
        "0003": "数据已存在",
        "0004": "状态不正常",
        "0005": "金额不够",
        "0006": "金额太小",
        "0007": "有正在处理的提现",
        "0008": "角色已存在",
        "0009": "密码不正确",
        "0010": "已核销过了",
        "0011": "微信认证code错误",
        "0012": "微信认证access_token错误", 
        "0013": "微信认证使用refresh_token请求时错误",
        "0014": "微信须要重新授权",
        "0015": "微信支付失败",
        "0016": "用户状态不正常",
        "0017": "API认证失败,检查token",
        "0018": "API认证失败,检查时间差",
        "0019": "API认证失败,检查签名在配制的最大时间内使用过",
        "0020": "API认证失败,签名解决失败",
        "0021": "验证码数据不存在",
        "0022": "验证码错误",
        "0023": "未关注公众号",
        "0024": "商家状态不正常",
        "0025": "产品状态不正常",
        "0026": "库存不足",
        "0027": "达人状态不正常",
        "0028": "已在其它团队",
        "0029": "产品倒计时结束",
        "0030": "队长和队员不能相同",
        "0031": "数据错误",
        "0032": "用户账户不存在",

        
        "1001": "密码错误达到上限",
        "1002": "帐号或密码错误",
        "1003": "登录失败",
        "1004": "登录认证失效",
        "1005": "数据错误",
        "1006": "没有新增用户的权限",
        "1007": "产品倒计时时间不正确",
        "1008": "商家有已支付未完成的订单时不能冻结商家",
        "1009": " 核销系统用户名或登录手机号已存在",
        "1010": "商家不存在或已冻结",
        "1011": "城市不存在",
        "1012": "奖品数量错误",
        "1013": "所有的奖品中奖率之和必须为100%",
        "1014": "奖品不存在",
        "1015": "活动奖品总库存为0",
        "1016": "平台已有三个正在进行的抽奖活动",
        "1017": "抽奖活动类型不能修改"
    }

    Base.lgFn = function(n, callback) { //n是返回码，string；callback
        if (Base.lg[n]) {
            if (typeof callback === "function") {
                callback(n);
            }
        } else {
            layer.msg(Base.lg['unKnown'])
        }
    }

    Base.cookieConfig = function(time) { //time: 分钟
        var c = {
            expires: 3650,
            path: '/'
        }

        var expiresDate = new Date();
        expiresDate.setTime(expiresDate.getTime() + (time * 60 * 1000));

        if (time) {
            c.expires = expiresDate;
        }

        return c;
    };
    //图片放大
    Base.showBigPhontos = function() {
       $(document).on("click","table tr td img", function() {
            var img_content = $(this).attr("src");
            $("body").append(
                "<div class='bg-img'>" +
                "<div class='tra-img'>" +
                "<img style='max-width: 1200px;' src='" + img_content + "' class='zoom-out'>" +
                "</div></div>"
            );
            //bottom:'0',left:'0';会让图片从页面左下放出现，如果想从左上方出现，将bottom:'0'改成top:'0';
            $(".bg-img").animate({
                width: "100%",
                height: "100%",
                top: "0",
                left: "0",
            }, "normal")
        })
    }
    //文件上传
    // Base.filePost = function(post_type){
    //     $('#ftx-file').empty().remove();
    //     $('body').append('<input id="ftx-file" type="file" style="display:none;"/>');
    //     $('#ftx-file').off('change').on('change', function (e) {
    //         //支持 FileReader
    //         if (window.FileReader) {
    //             var file=document.querySelector('input[type=file]').files[0];
    //             if(post_type=='file'){
    //                 if(file.name.split('.')[1]!='pdf'&&file.name.split('.')[1]!='docx'){
    //                   base.layer.msg('简历文件类型错误');
    //                   return false;
    //                 }
    //             }
    //             if(post_type=='images'){
    //                 if(file.name.split('.')[1]!='png'&&file.name.split('.')[1]!='jpg'){
    //                   base.layer.msg('图片类型必须是png或jpg');
    //                   return false;
    //                 }
    //             }              
    //             var fd = new FormData();
    //             fd.append("file",file);
    //             $.ajax({
    //                 'url': CONFIG.API_URL + 'api/fgj/upload/file',
    //                 'type': "POST",
    //                 'processData': false,
    //                 'contentType': false,
    //                 'async': false,
    //                 'headers': {
    //                     token: $.cookie('housingToken')
    //                 // 'Content-Type': 'application/json'
    //                 },
    //                 'data': fd,
    //                 'xhr': function () {
    //                     var xhr = $.ajaxSettings.xhr();
    //                     return xhr;
    //                 },
    //                 'success': function (data) {
    //                   if(data.retcode===0){
    //                       return data.data;
    //                     }else{
    //                         base.layer.msg(data.retmsg);
    //                     }                          
    //                 }
    //             })
    //         } else {
    //             $('#ftx-file').empty().remove();
    //             // layer.msg('上传失败，');
    //         }
    //     });
    //     setTimeout(function () {
    //         $('#ftx-file').click();
    //     }, 0);  
    // }

    //点击外层区域页面图片隐藏
    $(document).on("click", ".bg-img", function() {
        $(this).remove();
    })

    // Base.lgFn('E0007', function(n) {
    //  console.log(base.lg[n])
    // });

    //只能输入两位小数
    Base.floatNum = function(obj){
        
        obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符  
        obj.value = obj.value.replace(/^\./g,""); //验证第一个字符是数字而不是.
        obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的  
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$","."); 
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数  
        if(obj.value.indexOf(".")< 0 && obj.value !=""){//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额 
            obj.value= parseFloat(obj.value); 
        }
    }
    
    Base.floatOver = function(obj){
        var k = obj.value.split('');
        var j = k[k.length-1];
        if(j == '.'){
            obj.value = obj.value + '00';
        }
    }
    //只能是整数
    Base.IntNum = function(obj){
        obj.value=obj.value.replace(/[^\d]/g,'');
    }

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
    
    global.base = Base;
}(this));