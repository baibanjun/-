new Vue({
    el: '#burseApp',
    data: {
        details: function (id) {
            return app_config.WEB_URL + 'details?id=' + id + '&f=0&s=0'
        },
        data: {},
        cofferData: {}
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '我的金库');

        base.ajax({
            url: app_config.API_URL + 'my/coffer',
            no_code: true
        }, function (data) {
            if (data.code === '0000') {
				_self.data = data.data;
                data = data.data;
                _self.cofferData = {
                    t1: base.moneyOperation.add(base.moneyOperation.add(base.moneyOperation.add(data.primary_distribution_money, data.secondary_distribution_money), data.team_distribution_money), data.recommended_user_money),
                    t2: data.balance,
                    t3: data.withdraw_money,
                    t4: base.moneyOperation.add(data.primary_distribution_money, data.secondary_distribution_money),
                    t5: data.team_distribution_money
                };
            } else {
                window.location.href = app_config.WEB_URL + 'user';
            }
        });


        _self.getData(0);
    },
    mounted: function () {
        var _self = this;
        $(document).scroll(function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 30) {
                if (_self.isPost) {
                    _self.isPost = false;
                    _self.more = true;
                    _self.getData(1);
                }
            }
        });
    },
    methods: {
        getData: function (type) {
            var _self = this;

            if (type === 0) {
                _self.page = 1;
                _self.isPost = true;
                _self.noProduct = false;
            }

            var ajaxData = {
                page: _self.page,
                limit: 10
            };

            base.ajax({
                url: app_config.API_URL + 'my/records',
                data: ajaxData
            }, function (data) {
                if (data.current_page === data.last_page) {
                    _self.more = false;
                    _self.isPost = false;
                }
                _self.page++;
                if (type === 0) {
                    _self.data = data;
                    if (data.data.length < 1) {
                        _self.more = false;
                        _self.noProduct = true;
                    }
                }
                if (type === 1) {
                    if (data.data.length > 0) {
                        _self.isPost = true;
                        for (var i = 0; i < data.data.length; i++) {
                            _self.data.data.push(data.data[i]);
                        }
                    }
                }
            });
        }
    }
});