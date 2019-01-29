new Vue({
    el: '#u_orderApp',
    data: {
        id: 0,
        d: {},
        ewm: '',
        details: function (id) {
            return app_config.WEB_URL + 'details?id=' + id + '&f=0&s=0'
        }
    },
    created: function () {
        var _self = this;

        _self.id = parseInt(base.getQuery().id);

        if (!_self.id) {
            window.location.href = app_config.WEB_URL + 'user';
        }

        _self.getData(_self.id, function (code) {
            _self.getEwm(code);
        });

    },
    methods: {
        getData: function (id, success) {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'my/orders/' + id
            }, function (data) {

                _self.d = data;
                success(data.code);

            });
        },
        getEwm: function (code) {
            var _self = this;

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'my/orders',
                data: {
                    code: code
                },
                no_code: true
            }, function (data) {

                _self.ewm = data;

            });
        },
        mmp: function () {
            var _self = this;

            base.ajax({
                type: 'put',
                url: app_config.API_URL + 'my/orders/' + _self.d.id
            }, function (data) {

                base.layer.msg('确认成功', 6);
                _self.getData(_self.id, function (code) {
                    _self.getEwm(code);
                });
            });
        }
    }
});