new Vue({
    el: '#header',
    data: {
        user_name: '',
        now_id: 3,
        open: 0
    },
    created: function () {
        var _self = this;
        _self.user_name = $.cookie('userName');
        _self.now_id = $.cookie('now_id') || 0;
        if (_self.now_id == 0) {
            _self.open = 0;
        } else if (_self.now_id < 11) {
            _self.open = 1;
        } else if (_self.now_id < 17) {
            _self.open = 2;
        } else if (_self.now_id < 18) {
            _self.open = 3;
        } else {
            _self.open = 4;
        }
    },
    methods: {
        out: function () {
            var _self = this;
            layer.confirm('确定要退出登录？', function (index) {
                $.cookie('chwlToken', null, base.cookieConfig(-1));
                $.cookie('userName', null, base.cookieConfig(-1));
                $.cookie('now_id', null, base.cookieConfig(-1));
                window.location.href = WEB_CONFIG.WEB_URL + 'login';
            })
        },
        memu_this: function (num) {
            $.cookie('now_id', num, base.cookieConfig(6000));
        }

    }//methods

});