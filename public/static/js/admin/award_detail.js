new Vue({
    el: '#award_detail',
    data: {
        info: {}
    },
    created: function () {
        var _self = this;
        _self.info = JSON.parse(window.localStorage.award_info);
        console.log(_self.info)
    },
    methods: {
        to_award_list: function () {
            window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
        },
        edit_award: function (_id) {
            $.cookie('award_id', _id, base.cookieConfig(60000));
            window.location.href = WEB_CONFIG.WEB_URL + 'editAward';
        },
        putaway: function (_id) {
            var _self = this;
            layer.confirm('确认上架该抽奖活动？', function (index) {
                base.ajax({
                    type: 'put',
                    url: WEB_CONFIG.API_URL + 'admin/lottery_draw_status/' + _id,
                    data: {
                        status: 2
                    }
                }, function (res) {
                    layer.close(index);
                    window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
                }, function (res) {
                    layer.close(index);
                });
            })
        },
        conceal: function (_id) {
            var _self = this;
            layer.confirm('确认隐藏该抽奖活动？', function (index) {
                base.ajax({
                    type: 'put',
                    url: WEB_CONFIG.API_URL + 'admin/lottery_draw_status/' + _id,
                    data: {
                        status: 1
                    }
                }, function (res) {
                    layer.close(index);
                    window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
                }, function (res) {
                    layer.close(index);
                });
            })
        },
        del: function (_id) {
            var _self = this;
            layer.confirm('确认删除该抽奖活动？', function (index) {
                base.ajax({
                    type: 'DELETE',
                    url: WEB_CONFIG.API_URL + 'admin/lottery_draw/' + _id,
                    data: {}
                }, function (res) {
                    layer.close(index);
                    window.location.href = WEB_CONFIG.WEB_URL + 'awardList';
                }, function (res) {
                    layer.close(index);
                });
            })
        }

    }//methods

});