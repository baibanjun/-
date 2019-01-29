new Vue({
    el: '#withdrawApp',
    data: {
        cofferData: {},
        money: ''
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '我的金库');

        base.ajax({
            url: app_config.API_URL + 'my/coffer',
            no_code: true
        }, function (data) {
            if (data.code === '0000') {
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

    },
    methods: {
        withdraw: function () {
            var _self = this;

            if (!_self.money) {
                base.layer.msg('请输入提现金额');
                return false;
            }

            if (!base.el['102'].q.test(_self.money)) {
                base.layer.msg(base.el['102'].a);
                return false;
            }

            base.ajax({
                type: 'post',
                url: app_config.API_URL + 'my/withdraw',
                data: {
                    money: _self.money
                }
            }, function (data) {
                base.layer.msg('提现成功！', 6);
                setTimeout(function () {
                    window.location.reload()
                }, 500);
            });
        }
    }
});