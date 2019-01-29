new Vue({
    el: '#wxgroup_set',
    data: {
        id: '',

        group_name: null,
        set_group_name: null,
        group_qr_code: null,
        set_group_qr_code: null,
        group_title: null,
        set_group_title: null,

        setting: false
    },
    created: function () {
        var _self = this;
        base.ajax({
            type: 'get',
            url: WEB_CONFIG.API_URL + 'admin/admin_set',
            data: {
                type_name: 'weichat_group'
            }
        }, function (data) {
            if (data.value) {
                _self.group_name = data.value.group_name;
                _self.group_qr_code = data.value.group_qr_code;
                _self.group_title = data.value.group_title;
            }
            _self.id = data.id;
        }, function (data) {

        });
    },
    methods: {
        set_money: function () {
            var _self = this;
            _self.setting = true;

            _self.set_group_name = _self.group_name;
            _self.set_group_qr_code = _self.group_qr_code;
            _self.set_group_title = _self.group_title;
        },
        confirm: function () {
            var _self = this;
            if (!_self.set_group_name||!_self.set_group_title) {
                layer.msg('输入的数据有误');
                return false;
            }
            base.ajax({
                type: 'PUT',
                url: WEB_CONFIG.API_URL + 'admin/admin_set/' + _self.id,
                data: {
                    type_name: 'weichat_group',
                    value: {
                        'group_name': _self.set_group_name,
                        'group_qr_code': _self.set_group_qr_code,
                        'group_title': _self.set_group_title
                    }
                }
            }, function (data) {

                _self.group_name = _self.set_group_name;
                _self.group_qr_code = _self.set_group_qr_code;
                _self.group_title = _self.set_group_title;

                _self.setting = false;
                layer.msg('设置成功');
            }, function (data) {
                _self.setting = false;
            });
        },
        off: function () {
            var _self = this;
            _self.setting = false;
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
                                _self.set_group_qr_code = data.data;
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
        }

    }//methods

});