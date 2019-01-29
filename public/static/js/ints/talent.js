new Vue({
    el: '#talentApp',
    data: {
        name: '',
        mobile: ''
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '分享');

    },
    methods: {
        talent: function () {

            var _self = this;

            var ajaxData = {
                name: _self.name,
                mobile: _self.mobile
            };

            if (!ajaxData.name) {
                base.layer.msg('姓名不能为空');
                return false;
            }

            if (!ajaxData.mobile) {
                base.layer.msg('手机号码不能为空');
                return false;
            }

            if (!base.el["101"].q.test(ajaxData.mobile)) {
                base.layer.msg(base.el["101"].a);
                return false;
            }

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'my/talent',
                data: ajaxData,
                no_code: true,
                data_type: 'json'
            }, function (data) {
                if (data.code === '0000') {
                    base.layer.msg('申请成功', 6);
                    setTimeout(function () {
                        window.location.href = app_config.WEB_URL;
                    }, 500);
                }
                if (data.code === '0008') {
                    base.layer.msg('您已经是达人了', 6);
                }
                if (data.code === '0023') {
                    layer.open({
                        btn: ['已扫码关注'],
                        title: '微信扫码关注公众号',
                        offset: '1rem',
                        content: '<div style="text-align: center;"><img style="width: 130px;" src="' + app_ewm + '"></div>',
                        yes: function (index) {
                            _self.talent();
                            layer.close(index);
                        }
                    });
                }
            });
        }
    }
});