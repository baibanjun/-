new Vue({
    el: '#poster2',
    data: {
        data: {
            // name: 'product/201901/15464173591289196950.png'
        }
    },
    created: function () {
        var _self = this;

        _self.data.name = base.getQuery().pic_name;

        if (!_self.data.name) {
            window.location.href = app_config.WEB_URL + 'poster1';
        }


        var shareData = {
            title: '吃喝玩乐-专属海报',
            desc: '吃喝玩乐-专属海报',
            imgUrl: base.cosPic(_self.data.name, 750)
        };

        $(document).attr('title', shareData.desc);

        wx.ready(function () {


            wx.onMenuShareTimeline({
                title: shareData.title, // 分享标题
                link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: shareData.imgUrl, // 分享图标
                success: function () {
                    // 用户点击了分享后执行的回调函数
                }
            });

            wx.onMenuShareAppMessage({
                title: shareData.title, // 分享标题
                desc: shareData.desc, // 分享描述
                link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: shareData.imgUrl, // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
// 用户点击了分享后执行的回调函数
                }
            });


        });

    },
    methods: {}
});