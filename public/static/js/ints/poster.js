new Vue({
    el: '#poster',
    data: {
        data: {}
    },
    created: function () {
        var _self = this;
        _self.data.id = base.getQuery().id;
        _self.data.name = base.getQuery().name;
        if (!_self.data.id || !_self.data.name) {
            window.location.href = app_config.WEB_URL;
        }
        _self.getData(_self.data.id);

    },
    methods: {
        getData: function (id) {
            var _self = this;
            base.ajax({
                url: app_config.API_URL + 'product/' + id
            }, function (data) {
                $(document).attr('title', data.name);
            });
        }
    }
});