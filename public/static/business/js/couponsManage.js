seajs.use(['base', 'page'], function (base) {

    base.headMobile(); //解决手机端input获取焦点时候 头部固定偏移问题

    var app = new Vue({
        el: "#app",
        data: {
            name: null, //姓名
            tel: null, //电话
            code: null, //电子码
            sn: null, //订单号
            title: null,
            gridData: [], //返回的数据

            curPage: 0, //当前页
            showPages: 0, //显示多少页
            totalPages: 0, //总页数
            isPage: true, //是否显示分页

        },
        created: function () {
            var _self = this;
            _self.getList(1);
        },
        components: {
            // 引用组件
            'pagination': pagination
        },
        filters: {
            statusText: function (value) {
                return value == 0 ? '未支付' : value == 1 ? '已支付' : value == 2 ? '已预约' : value == 3 ? '已发货' : value == 4 ? '已完成' :
                    '';
            }
        },
        mounted: function () {
            laydate.render({
                elem: '#creatTime', //指定元素
                type: 'datetime',
                max: 'date',
                min: '1970-01-01',
                range: '~'
            });

            laydate.render({
                elem: '#editTime', //指定元素
                type: 'datetime',
                max: 'date',
                min: '1970-01-01',
                range: '~'
            });
        },
        methods: {
            getList: function (p) {
                var _self = this;

                var creatTime = $('#creatTime').val().split(' ~ ');
                var editTime = $('#editTime').val().split(' ~ ');

                base.Ajax({
                    url: app_config.BUS_API_URL + 'business/verify_the_coupon',
                    type: 'get',
                    data: {
                        type: 3, //核销记录
                        limit: 15,
                        page: p,

                        title: _self.title,
                        c_start_time: creatTime[0],
                        c_end_time: creatTime[1],
                        u_start_time: editTime[0],
                        u_end_time: editTime[1]
                    }
                }, function (data) {
                    if (data.code == '0000') {
                        _self.gridData = data.data.data;
                        let d = data.data;

                        _self.curPage = d.current_page; //当前页
                        _self.totalPages = d.last_page; //总页数
                        _self.showPages = d.last_page == 0 ? 0 : d.last_page < 5 ? d.last_page : 5; //可以点击的页数

                        if (d.last_page == 0) {
                            _self.isPage = false;
                        }
                    }
                })
            },
            orderDetails: function (item) {
                localStorage.setItem('orderDetails_sd', JSON.stringify(item))
                window.location.href = "/web_business/couponsDetails?id=" + item.id;
            }
        }
    })


});
