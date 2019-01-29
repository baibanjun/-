new Vue({
    el: '#app_header',
    data: {
        styleObject: {},
        is_tab_city: true,
        wxInfo: base.wxInfo,
        // cityArr: base.cityArr,
        cityArr: [],
        curCity: {}
    },
    created: function () {
        var _self = this;

        _self.styleObject.height = $(document).height() + 'px';


        base.ajax({
            url: app_config.API_URL + 'city'
        }, function (data) {
            for (var i = 0; i < data.length; i++) {
                _self.cityArr.push({
                    name: data[i].city_name,
                    city: data[i].city_code
                });
            }

            if (!localStorage.getItem('city')) {
                localStorage.setItem('city', _self.cityArr[0].city);
                _self.curCity = _self.cityArr[0];
                window.location.reload();
            } else {
                for (var j = 0; j < _self.cityArr.length; j++) {
                    if (_self.cityArr[j].city === localStorage.getItem('city')) {
                        _self.curCity = _self.cityArr[j];
                    }
                }
            }
        });


    },
    methods: {
        tab_city: function () {
            const _self = this;
            _self.is_tab_city = !_self.is_tab_city;
        },
        tab_city_active: function (item) {
            const _self = this;
            _self.is_tab_city = true;
            localStorage.setItem('city', item.city);
            window.location.reload();
        }
    }
});

// 计算返价
Vue.filter('rePrice', function (item) {
    if (item.primary_distribution && item.primary_distribution.type === 1) {
        var n = base.moneyOperation.mul(item.primary_distribution.value, item.standards[0].sale_price);
        return base.getNum(n);
    }
    if (item.primary_distribution && item.primary_distribution.type === 2) {
        return item.primary_distribution.value;
    }
    return 0;
});

// 倒计时
Vue.filter('time_limit', function (item) {
    // item.is_countdown=0;

    // 过期时间戳
    var date = new Date(item.updated_at.replace(/-/g, '/'));
    var time = Date.parse(date) / 1000;

    // 当前时间戳
    var curTime = Date.parse(new Date()) / 1000;

    setInterval(function () {
        return curTime - time;
    }, 1000);
});

// 腾讯云读地址
Vue.filter('cosPic', function (name, width, height) {
    return base.cosPic(name, width, height);
});

// 腾讯云读地址
Vue.filter('cosPic1', function (name, width, height) {
    return base.cosPic1(name, width, height);
});