new Vue({
    el: '#teamApp',
    data: {
        data: {},
        captain: parseInt(base.getQuery().captain) || 0
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '申请加入团队');

        if (!_self.captain) {
            window.location.href = app_config.WEB_URL;
        } else {
            _self.getData();
        }

    },
    methods: {
        getData: function () {
            var _self = this;

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'my/team',
                data: {
                    captain: _self.captain
                },
                no_code: true
            }, function (data) {
                if (data.code === '0000') {
                    base.layer.msg(base.code[data.code], 6);
                } else {
                    base.layer.msg(base.code[data.code]);
                }
                setTimeout(function () {
                    window.location.href = app_config.WEB_URL;
                }, 3000);
            });
        }
    }
});