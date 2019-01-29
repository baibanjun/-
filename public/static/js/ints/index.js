new Vue({
    el: '#goodsApp',
    data: {
        page: 1,
        isPost: true,
        data: [],
        detailsUrl: detailsUrl,
        moreAny1: false,
        moreAny: false
    },
    created: function () {
        var _self = this;

        if (productType === 1) {
            $(document).attr('title', '吃喝玩乐go');
        }
        if (productType === 3) {
            $(document).attr('title', '联盟商城');
        }

        $('.goods').css('height', $(window).height());

    },
    mounted: function () {
        var _self = this;

        base.ajax({
            url: app_config.API_URL + 'product',
            data: {
                type: productType,
                city: localStorage.getItem('city'),
                page: _self.page,
                limit: 10
            }
        }, function (data) {

            for (var i = 0; i < data.data.length; i++) {
                var obj = data.data[i];

                // 过期时间戳
                var date = new Date(obj.updated_at.replace(/-/g, '/'));
                var time = Date.parse(date) / 1000;

                obj.time = time + obj.time_limit;

                _self.$set(
                    obj, "djs", base.InitTime(obj.time)
                );
            }

            if (data.data.length > 0) {
                for (var m = 0; m < data.data.length; m++) {
                    var standards = data.data[m].standards;
                    data.data[m].is_on_hand = false;
                    if (standards.length > 0) {
                        for (var n = 0; n < standards.length; n++) {
                            var onhand = standards[n].onhand;
                            console.log(data.data[m].id + ':' + onhand);
                            if (!data.data[m].is_on_hand) {
                                data.data[m].is_on_hand = onhand > 0;
                            }
                        }
                    }
                }
            }

            _self.data = data;
        });

        setInterval(function () {
            for (var key in _self.data.data) {

                var obj = _self.data.data[key];

                var aaa = parseInt(obj["time"]);
                var bbb = parseInt(new Date().getTime() / 1000);

                var rightTime = aaa - bbb;

                if (rightTime > 0) {
                    var dd = Math.floor(rightTime / 60 / 60 / 24);
                    var hh = Math.floor((rightTime / 60 / 60) % 24);
                    var mm = Math.floor((rightTime / 60) % 60);
                    var ss = Math.floor((rightTime) % 60);
                    if (dd === 0) {
                        obj["djs"] = hh + "小时" + mm + "分" + ss + "秒";
                    } else {
                        obj["djs"] = dd + "天" + hh + "小时" + mm + "分" + ss + "秒";
                    }
                } else {
                    obj["djs"] = '已结束';
                }
            }
        }, 1000);
    },
    methods: {
        getData: function (type) {
            var _self = this;
            var wrap_height = $('#goodsApp').height();  //容器的高度
            var scroll_top = $('#goodsApp').scrollTop();   //滚动条的scrolltop
            var scroll_height = $('#goodsApp').prop('scrollHeight');  //内容的高度
            var is_height = scroll_height - wrap_height - scroll_top - 125   //判断是否为0，既是否到了底部（这里的-125是因为我定了个padding-bottom=125,所以要减掉）

            if (is_height < 50 && scroll_height > $(window).height() && _self.isPost) {
                // console.log(_self.page);
                _self.page++;
                _self.isPost = false;
                _self.moreAny1 = true;

                setTimeout(function () {

                    base.ajax({
                        url: app_config.API_URL + 'product',
                        data: {
                            type: productType,
                            city: localStorage.getItem('city'),
                            page: _self.page,
                            limit: 10
                        }
                    }, function (data) {
                        _self.moreAny1 = false;
                        if (data.data.length < 1) {
                            _self.moreAny = true;
                        }

                        if (data.data.length > 0) {
                            for (var m = 0; m < data.data.length; m++) {
                                var standards = data.data[m].standards;
                                data.data[m].is_on_hand = false;
                                if (standards.length > 0) {
                                    for (var n = 0; n < standards.length; n++) {
                                        var onhand = standards[n].onhand;
                                        console.log(data.data[m].id + ':' + onhand);
                                        if (!data.data[m].is_on_hand) {
                                            data.data[m].is_on_hand = onhand > 0;
                                        }
                                    }
                                }
                            }
                        }

                        for (var i = 0; i < data.data.length; i++) {
                            var obj = data.data[i];

                            // 过期时间戳
                            var date = new Date(obj.updated_at.replace(/-/g, '/'));
                            var time = Date.parse(date) / 1000;

                            obj.time = time + obj.time_limit;

                            _self.$set(
                                obj, "djs", base.InitTime(obj.time)
                            );

                            _self.data.data.push(obj);
                            _self.isPost = true;
                        }

                    });

                }, 1000);

            }
        }
    }
});