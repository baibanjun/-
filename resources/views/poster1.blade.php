<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
		<title>吃喝玩乐-专属海报</title>
        <script>
            var app_config = {
                API_URL: '{{config('console.api_url')}}',
                            APP_ID: '{{env('APPID')}}',
                WEB_URL: '{{url('/')}}/',
                CUR_URL: '{{Request::path()}}',
                PIC_URL: '{{config('console.pic_url')}}'
            };
        </script>
		<link rel="stylesheet" type="text/css" href="{{statics('css/aui.css')}}" />
		<link rel="stylesheet" href="{{statics('css/intial.css')}}" />
		<style type="text/css">
			* {
				font-family: "黑体";
			}

			body,
			html {
				height: 100%;
			}

			html {
				background-color: hsla(0, 0%, 96%, 1.00);
			}

			body {
				background: none;
			}

			section {
				font-size: 0.9rem;
			}

			#headimg {
				position: relative;
				width: 19rem;
				margin: auto;
				background-size: contain !important;
				background-repeat: no-repeat;
				background-position: center top;
			}

			.btn {
				position: absolute;
				top: 6.8rem;
				left: 4rem;
				z-index: 10;
				height: 10.8rem;
				width: 11.4rem;
				margin: 0 auto;
				line-height: 10.8rem;
				font-size: 0.8rem;
				color: #fd1142 !important;
			}

			.btn img {
				display: inline-block;
				width: 0.75rem;
				margin-left: 0.1rem;
			}


			/*截图上传页面*/
			.clipbg {
				position: fixed;
				background: black;
				top: 0;
				z-index: 999;
				width: 100%;
				height: 100%;
				left: 0;
			}

			.loading {
				position: absolute;
				top: 40%;
				width: 38%;
				left: 31%;
				height: 1.6rem;
				line-height: 1.6rem;
				z-index: 99999;
				text-align: center;
				color: #ffffff;
				border-radius: 0.2rem;
				background: #9f9f9f;
			}

			.clipbg #clipArea {
				width: 100%;
				height: 80%;
				margin: auto;

			}

			.clipbg .footer {
				width: 90%;
				position: absolute;
				left: 5%;
				bottom: 0px;
				z-index:9999;
				text-align: center;
			}

			.clipbg dl {
				background: #ffffff;
				border-radius: 0.4rem;
				overflow: hidden;
				margin-bottom: 0.6rem;
			}

			.clipbg dd {
				position: relative;
				height: 2.25rem;
				line-height: 2.25rem;
				border-bottom: 1px solid #999999;
			}

			.clipbg .back {
				height: 2.25rem;
				line-height: 2.25rem;
				border-radius: 0.4rem;
				margin-bottom: 0.4rem;
				background: #ffffff;
			}

			.clipbg dd input {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				z-index: 11;
				filter: alpha(opacity=0);
				-moz-opacity: 0;
				-khtml-opacity: 0;
				opacity: 0;
			}
			
			.qr-code{
				position: absolute;
				bottom:0.6rem;
				left:7.7rem;
				border:1px solid red;
				width:3.3rem;
				height:3.3rem;
			}
			.edit-ame{
				position: absolute;
				width:300px;
				height:90px;
				top:50%;
				left:50%;
				margin-top:60px;
				margin-left:-150px;
				border:1px solid red;
				background-image: url({{statics('images/name.png')}});
				background-size: cover;
				z-index:1001;
			}
			#edit-ame-ipt{color:#ffffff;
                         transform: rotate(-30deg);
                         position: relative;
                         top: -3.1rem;
                         left: 1rem;
                         width: 4rem;
                         z-index:1;}

            #btnbtn{    position: absolute;
                        width: 100%;
                        height: 100%;
                        top: 0;
                        left: 0;background: #ffffff;
                                    opacity: 0.1;}
		</style>
	</head>
	<body>
		<section class="aui-text-center">
			<div id="headimg" style="background-image:url({{statics('images/back_img.png')}});">
				<div class="btn">
					点我上传照片
					<div id="btnbtn"></div>
					 <input id="edit-ame-ipt" type="text" maxlength="11" value="">
				</div>
				<div class="qr-code">
					
				</div>
			</div>
		</section>

		<div class="clipbg displaynone">
			<div id="clipArea">
				<!--<div class="edit-ame">
				</div>-->
			</div>
			<div class="loading displaynone">正在载入图片...</div>
			<div class="footer">
				<dl>
					<dd style="background: #fe1041; color: #ffffff;border: none;">重新上传<input type="file" id="file" accept="image/*"></dd>
					<dd id="clipBtn">生成海报</dd>
				</dl>
				<div class="back">取消</div>
			</div>
		</div>
	</body>
	<script src="{{statics('js/jquery.js')}}"></script>
	<script type="text/javascript" src="{{statics('js/hammer.min.js')}}"></script>
	<script type="text/javascript" src="{{statics('js/lrz.all.bundle.js')}}"></script>
	<script type="text/javascript" src="{{statics('js/iscroll-zoom-min.js')}}"></script>
	<script type="text/javascript" src="{{statics('js/PhotoClip.js')}}"></script>
    <script src="{{statics('js/jsencrypt.min.js')}}"></script>
    <script src="{{statics('js/sha1.js')}}"></script>
	<script src="{{statics('js/layui/layui.all.js')}}"></script>
    <script src="https://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
	<script src="{{statics('js/base.js')}}"></script>
	<script>

        var app_ewm = "{{config('console.app_ewm')}}";
		function resizeDiv() {
			// 获取 div 元素
			var div = document.getElementById("headimg");
			// 兼容 style
			var style = div.currentStyle || getComputedStyle(div, false);
			// 从 url("/path/to/image.jpg") 中获取图像地址
			var img_src = style.backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');

			// 创建新图像
			var img = new Image();
			// 图像加载
			img.onload = function() {
				// 获取宽高比
				var ratio = img.width / img.height;
				// 根据比例设置 div 高度
				div.style.height = parseInt(div.offsetWidth / ratio) + "px";
				img = null;
			};
			img.src = img_src;
		};

		// 页面加载，设置 div 高度
		window.onload = function() {
			resizeDiv();

			$("#edit-ame-ipt").val(base.wxInfo.nickname);
		};

		// 窗口变化，设置 div 高度
		window.onresize = function() {
			resizeDiv();
		};

		$("#btnbtn").click(function() {
			$(".clipbg").fadeIn();
			$("#file").click();
		})
		var dddd = $(window).width() - 10;
		var clipArea = new PhotoClip("#clipArea", {
			size: [dddd, dddd], //裁剪框大小
			outputSize: [440, 413], //打开图片大小，[0,0]表示原图大小
			file: "#file",
			ok: "#clipBtn",
			loadStart: function() { //图片开始加载的回调函数。this 指向当前 PhotoClip  ，并将正在加载的 file 对象作为参数传入。（如果是使用非 file 的方式加载图片，则该参数为图片的 url）
				$(".loading").removeClass("displaynone");

			},
			loadComplete: function() { //图片加载完成的回调函数。this 指向当前 PhotoClip 的实例对象，并将图片的 <img> 对象作为参数传入。
				$(".loading").addClass("displaynone");

			},
			done: function(dataURL) { //裁剪完成的回调函数。this 指向当前 PhotoClip 的实例对象，会将裁剪出的图像数据DataURL作为参数传入。

				//console.log(dataURL); //dataURL裁剪后图片地址base64格式提交给后台处理


                base.ajax({
                    type:'post',
                    url: app_config.API_URL + 'poster',
                    data: {
                        base64_img: dataURL,
                        user_name: $("#edit-ame-ipt").val()
                    },
                    no_code: true
                    //layer: true
                },function(data){
                    console.log(data);

                    if(data.code==='0000'){

                        window.location.href = app_config.WEB_URL + 'poster2?pic_name='+ data.data.name;

                        setTimeout(function(){
                            $(".clipbg").fadeOut();
                        },3000);
                    } else if (data.code === '0023') {
                        layer.open({
                            btn: [],
                            title: '微信扫码（长按）关注公众号',
                            offset: '1rem',
                            content: '<div style="text-align: center;"><img style="width: 100%;" src="' + app_ewm + '"></div>'
                        });

                     } else {
                         base.layer.msg(base.code[data.code]);
                     }

                });

			}
		});
		$(".back").click(function() {
			$(".clipbg").fadeOut()
		});

        $('#edit-ame-ipt').bind("focus",function(){
           //$(".footer").css({"position":"static","bottom":0});
        }).bind("blur",function(){
           //$(".footer").css("position","fixed");
           $(window).scrollTop(0);
        });


	</script>
</html>
