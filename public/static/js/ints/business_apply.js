new Vue({
    el: '#business_applyApp',
    data: {
        data: {},
        "name": "",
        "tel": "",
        "industry": "",
        "remark": ""
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '入户商户信息');

        _self.getData();


    },
    methods: {
        getData: function () {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'business_apply'
            }, function (data) {
                _self.data = data;
            });
        },
        post: function () {
            var _self = this;

            var ajaxData = {
                "name": _self.name,
                "tel": _self.tel,
                "industry": _self.industry,
                "remark": _self.remark
            };

            if(ajaxData.name === ''){
                base.layer.msg('请输入姓名');
                return false;
            }

            if(ajaxData.tel === ''){
                base.layer.msg('请输入手机号码');
                return false;
            }

            if(!base.el['101'].q.test(ajaxData.tel)){
                base.layer.msg(base.el['101'].a);
                return false;
            }

            if(ajaxData.industry === ''){
                base.layer.msg('请输入行业类型');
                return false;
            }

            if(ajaxData.remark === ''){
                base.layer.msg('请输入备注');
                return false;
            }

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'business_apply',
                data: ajaxData
            }, function (data) {
                // base.layer.msg('');
                _self.getData();
            });

        }
    }
});