new Vue({
    el: '#edit_award',
    data: {
        req: {
            title: '123',
            lottery_type: 1, //1九宫格2圆盘
            business_id: "",
            description: '',//活动说明
            poster: [],//海报
            business_introduce: '',//商家介绍
            draw_data: []
        },
        address: '',
        tel: '',
        business_select: []
    },
    mounted: function () {
        _self = this;
        //时间选择器
        layui.use('laydate', function () {
            var laydate = layui.laydate;
            laydate.render({
                elem: '#start_time_0'
            });
            laydate.render({
                elem: '#end_time_0'
            });
            laydate.render({
                elem: '#start_time_1'
            });
            laydate.render({
                elem: '#end_time_1'
            });
            laydate.render({
                elem: '#start_time_2'
            });
            laydate.render({
                elem: '#end_time_2'
            });
            laydate.render({
                elem: '#start_time_3'
            });
            laydate.render({
                elem: '#end_time_3'
            });
            laydate.render({
                elem: '#start_time_4'
            });
            laydate.render({
                elem: '#end_time_4'
            });
            laydate.render({
                elem: '#start_time_5'
            });
            laydate.render({
                elem: '#end_time_5'
            });
            laydate.render({
                elem: '#start_time_6'
            });
            laydate.render({
                elem: '#end_time_6'
            });
            laydate.render({
                elem: '#start_time_7'
            });
            laydate.render({
                elem: '#end_time_7'
            });
        })
    },
    created: function () {
        console.log(1)
        var _self = this;
        _self.req = JSON.parse(window.localStorage.award_info);
        _self.req.draw_data = _self.req.lottery_draw_list;
        //商家信息
        _self.tel = _self.req.business.tel;
        _self.address = _self.req.business.address;
        $.each(_self.req.draw_data, function (i, n) {
            n.probability *= 100;
        })
        var time = setTimeout(function () {
            //设置富文本内容
            editor.txt.html(_self.req.business_introduce);
        }, 600)

        //商家下拉
        base.ajax({
            type: 'get',
            url: WEB_CONFIG.API_URL + 'admin/business_select',
            data: {}
        }, function (data) {
            // _self.business_select = data;
            _self.business_select.push(_self.req.business);
        }, function (data) {

        });
    },
    methods: {
        post: function () {
            var _self = this;
            var reg_1 = /^\d+$/;  //非负整数
            var reg_2 = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;//正浮点数
            // var reg_2 = /^(([0-9]+\.[0-9]*[0-9][0-9]*)|([0-9]*[0-9][0-9]*\.[0-9]+)|([0-9]*[0-9][0-9]*))$/;
            _self.req.business_introduce = editor.txt.html();
            if (_self.req.title == '') {
                layer.msg('请填写抽奖标题');
                return false;
            }
            if (_self.req.poster.length == 0) {
                layer.msg('请上传活动海报');
                return false;
            }
            if (_self.req.business_id == "") {
                layer.msg('请选择商家');
                return false;
            }
            if (_self.req.description == "") {
                layer.msg('请填写活动说明');
                return false;
            }
            // if(_self.req.business_introduce==""){
            // 	layer.msg('请填写商家介绍');
            // 	return false;
            // }
            var _pass = true;
            var _p = Number(0);
            for (var i = 0; i < _self.req.draw_data.length; i++) {
                var n = _self.req.draw_data[i];

                if (n.name == '') {
                    layer.msg('请填写奖品名称');
                    _pass = false;
                    return false;
                }
                if (!reg_1.test(n.inventory)) {
                    layer.msg('请正确填写奖品库存');
                    _pass = false;
                    return false;
                }
                if (!reg_2.test(n.probability)) {
                    layer.msg('请正确填写奖品概率');
                    _pass = false;
                    return false;
                }
                //加概率
                // _p+=Number(n.probability);
                _p = base.moneyOperation.add(_p, Number(n.probability));

                if (_self.req.lottery_type != 2 && n.pic.length == 0) {
                    layer.msg('请上传奖品图片');
                    _pass = false;
                    return false;
                }
                if (n.draw_type == 1) {
                    if (n.use_condition == '') {
                        layer.msg('请正确填写奖品使用条件');
                        _pass = false;
                        return false;
                    }
                    if (n.description == '') {
                        layer.msg('请填写奖品说明');
                        _pass = false;
                        return false;
                    }
                    if ($('#start_time_' + i).val() == "" || $('#end_time_' + i).val() == "") {
                        layer.msg('请填写优惠券有效期');
                        _pass = false;
                        return false;
                    }
                }
            }
            //概率和为100%
            console.log(_p)
            if (_p != 100) {
                layer.msg('奖品概率的和为100%');
                _pass = false;
                return false;
            }
            if (!_pass) {
                return false;
            }
            var _can = true;
            layer.confirm('确定要提交保存吗？', function (index) {
                if (_can) {
                    _can = false;
                    layer.close(index);
                    var _req = JSON.parse(JSON.stringify(_self.req));
                    $.each(_req.draw_data, function (i, n) {
                        n.probability = n.probability / 100;
                        if (n.draw_type == 2) {
                            delete n.start_date;
                            delete n.end_date;
                            delete n.use_condition;
                            delete n.description;
                        }
                        else {
                            n.start_date = $('#start_time_' + i).val();
                            n.end_date = $('#end_time_' + i).val();
                        }
                    })

                    base.ajax({
                        type: 'put',
                        url: WEB_CONFIG.API_URL + 'admin/lottery_draw/' + _self.req.id,
                        data: _req
                    }, function (data) {
                        window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
                    }, function (data) {

                    });
                }

            })
        },
        file_post: function (_type, _index) {
            var _self = this;

            $('body').append('<input id="ftx-file" type="file" style="display:none;"/>');
            $('#ftx-file').off('change').on('change', function (e) {
                //支持 FileReader
                if (window.FileReader) {
                    var file = document.querySelector('input[type=file]').files[0];
                    var f_name = ['png', 'jpg', 'jpeg'];
                    if (f_name.indexOf(file.name.split('.')[1]) == '-1') {
                        base.layer.msg('上传图片类型为jpg或者png');
                        return false;
                    }
                    if (file.size > 2048 * 1000) {
                        base.layer.msg('文件大小不能超过2m');
                        return false;
                    }

                    var fd = new FormData();
                    fd.append("file", file);
                    //加密
                    var $data = {
                        random: base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
                        timestamp: Date.parse(new Date()) / 1000
                    };
                    var encrypt = new JSEncrypt();
                    encrypt.setPublicKey();
                    var encryptData = encrypt.encrypt(JSON.stringify($data));
                    //上传中提示
                    var index = layer.load(0, {shade: [0.3, '#ccc']});
                    $.ajax({
                        url: WEB_CONFIG.API_URL + 'admin/upload',
                        type: "POST",
                        processData: false,
                        contentType: false,
                        async: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            sign: encryptData,
                            random: $data.random,
                            timestamp: $data.timestamp,
                            token: $.cookie('chwlToken')
                            // 'Content-Type': 'application/json'
                        },
                        data: fd,
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            return xhr;
                        },
                        success: function (data) {
                            layer.close(index);
                            if (data.code == '0000') {
                                //海报
                                if (_type == 1) {
                                    //限制尺寸
                                    if (data.data.width != 750 || data.data.height != 1334) {
                                        layer.msg('分享海报的尺寸不符合要求');
                                        return false;
                                    }
                                    if (_self.req.poster.length == 0) {
                                        layer.msg('上传成功');
                                        _self.req.poster.push(data.data);
                                    } else {
                                        layer.msg('上传成功');
                                        _self.req.poster = [];
                                        _self.req.poster.push(data.data);
                                    }
                                }
                                //奖品图
                                else {
                                    if (_self.req.draw_data[_index].pic.length == 0) {
                                        layer.msg('上传成功');
                                        _self.req.draw_data[_index].pic.push(data.data);
                                    } else {
                                        layer.msg('上传成功');
                                        _self.req.draw_data[_index].pic = [];
                                        _self.req.draw_data[_index].pic.push(data.data);
                                    }
                                }
                            }
                            else if (data.code == '1004') {
                                window.location.href = WEB_CONFIG.WEB_URL + 'login';
                            } else {
                                layer.msg('上传失败');
                            }
                        }
                    })
                } else {
                    $('#ftx-file').empty().remove();
                    // layer.msg('上传失败，');
                }
                return false;
            });
            setTimeout(function () {
                $('#ftx-file').click();
            }, 0);
        },
        deleteImg: function (_type, _index) {
            var _self = this;
            if (_type == 1) {
                _self.req.poster = [];
            } else {
                _self.req.pics.splice(_index, 1);
            }
        },
        close: function () {
            layer.confirm('确定要放弃修改并返回？', function (index) {
                window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
            })
        },
        choose_business: function () {
            var _self = this;
            $.each(_self.business_select, function (i, n) {
                if (_self.req.business_id == n.id) {
                    _self.address = n.address;
                    _self.tel = n.tel;
                }
            })
        },
        choose_type: function (event) {
            var _self = this;
            if (event.target.value == 2) {
                if (_self.req.draw_data.length == 8) {
                    _self.req.draw_data.pop();
                }
            }
            if (event.target.value == 1) {
                if (_self.req.draw_data.length == 7) {
                    _self.req.draw_data.push({
                        name: '',
                        draw_type: "1", //1优惠券 2谢谢参与
                        inventory: '',
                        probability: '',
                        start_date: '',
                        end_date: '',
                        use_condition: '',
                        pic: [],
                        description: '',
                        is_auto_hidden: 0
                    });
                }
            }
        }

    }//methods

});