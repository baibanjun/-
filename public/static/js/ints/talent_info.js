new Vue({
    el: '#talent_infoApp',
    data: {
        data: {},
        teamData: ''
    },
    created: function () {
        var _self = this;

        $(document).attr('title', '我的金库');

        base.ajax({
            url: app_config.API_URL + 'my/coffer',
            no_code: true
        }, function (data) {
            if (data.code === '0000') {
            } else {
                window.location.href = app_config.WEB_URL + 'user';
            }
        });

        _self.getData();
        _self.getTeamData();
    },
    methods: {
        getData: function () {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'my/talent'
            }, function (data) {
                _self.data = data;
            });
        },
        getTeamData: function () {
            var _self = this;

            base.ajax({
                url: app_config.API_URL + 'my/team',
                no_code: true
            }, function (data) {
                _self.teamData = data;
            });
        }
    }
});