new Vue({
    el: '#business_alert',
    data: {
        id: '',
        pass_attention: "",
        wait_attention: "",
        return_attention: "",
        set_pass_attention: "",
        set_wait_attention: "",
        set_return_attention: "",
        setting: false
    },
    created: function () {
        var _self = this;
        base.ajax({
            type: 'get',
            url: WEB_CONFIG.API_URL + 'admin/admin_set',
            data: {
                type_name: 'business_enter_attention'
            }
        }, function (data) {
            if (data.value) {
                _self.pass_attention = data.value.pass_attention;
                _self.wait_attention = data.value.wait_attention;
                _self.return_attention = data.value.return_attention;
            }
            _self.id = data.id;
        }, function (data) {

        });
    },
    methods: {
        set_alert: function () {
            var _self = this;
            _self.setting = true;
            _self.set_pass_attention = JSON.parse(JSON.stringify(_self.pass_attention));
            _self.set_wait_attention = JSON.parse(JSON.stringify(_self.wait_attention));
            _self.set_return_attention = JSON.parse(JSON.stringify(_self.return_attention));
        },
        confirm: function () {
            var _self = this;
            if (_self.set_pass_attention == "") {
                layer.msg('提醒字段不能为空');
                return false;
            }
            if (_self.set_wait_attention == "") {
                layer.msg('提醒字段不能为空');
                return false;
            }
            if (_self.set_return_attention == "") {
                layer.msg('提醒字段不能为空');
                return false;
            }
            base.ajax({
                type: 'PUT',
                url: WEB_CONFIG.API_URL + 'admin/admin_set/' + _self.id,
                data: {
                    type_name: 'business_enter_attention',
                    value: {
                        'pass_attention': _self.set_pass_attention,
                        'wait_attention': _self.set_wait_attention,
                        'return_attention': _self.set_return_attention
                    }
                }
            }, function (data) {
                _self.pass_attention = _self.set_pass_attention;
                _self.wait_attention = _self.set_wait_attention;
                _self.return_attention = _self.set_return_attention;
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
        back: function () {
            window.location.href = WEB_CONFIG.WEB_URL + 'businessApply';
        }

    }//methods

});