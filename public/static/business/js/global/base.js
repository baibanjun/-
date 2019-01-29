define(function(require, exports, module) {
	var wx = require('jweixin');
	var sha1 = require('sha1');

	var base = {
		/**
		 * 判断移动设备还是电脑浏览器访问
		 */
		browserRedirect: function() {
			let isEquipment;
			let sUserAgent = navigator.userAgent.toLowerCase();
			let bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
			let bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
			let bIsMidp = sUserAgent.match(/midp/i) == "midp";
			let bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
			let bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
			let bIsAndroid = sUserAgent.match(/android/i) == "android";
			let bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
			let bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
			if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
				isEquipment = 'iphone';
			} else {
				isEquipment = 'pc';
			}
			return isEquipment;
		},
		/**
		 *	判断是否是微信浏览器
		 */
		isWx: function() {
			let ua = navigator.userAgent.toLowerCase();
			if (ua.match(/MicroMessenger/i) == "micromessenger") {
				return true;
			} else {
				return false;
			}
		},
		/**
		 * 获取url地址传递的参数
		 * 栗子：
		 * 若地址栏URL为：abc.html?id=123&url=http://www.maidq.com
		 * 那么，但你用上面的方法去调用：alert(GetQueryString("url"));
		 */
		GetQueryString: function(name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
			var r = window.location.search.substr(1).match(reg);
			if (r != null) return unescape(r[2]);
			return null;
		},
		/**
		 * 获取用户基本信息
		 */
		userInfo: function() {
			var Info;
			if (!localStorage.getItem('busInfo')) {
				layer.msg('登录过期!!!', {
					icon: 2
				});
				setTimeout(function() {
					window.location.href = "/web_business/login";
					return false;
				}, 1000);
			} else {
				Info = JSON.parse(localStorage.getItem('busInfo'));
				$('#showUserName').text(Info.business.name);
			}
			return Info;
		},
		Ajax: function(parameter, successCallback, errorCallback) {
			let t = parameter.type.toUpperCase();
			if (t) {
				var api_certification = base.apiData(); //获取API认证盐值
				var onSite = parameter.onSite; //站内还是站外
				var ajaxParameter = {
					type: t, //HTTP请求类型
					url: parameter.url,
					data: !parameter.data?{}:parameter.data,
					async: false,
					dataType: 'json', //服务器返回json格式数据
					timeout: 10000, //超时时间设置为10秒；
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						sign: api_certification.sign,
						random: api_certification.random,
						timestamp: api_certification.timestamp
					},
					success: function(data) {
						if (data.code) {
							if (data.code == '0017') {
								window.location.href = "/web_business/login"
							} else {
								successCallback(data);
							}
						} else {
							console.info(data);
						}
					},
					error: function(error) {
						if (typeof errorCallback === 'function') {
							errorCallback(error);
						} else {
							console.info(error);
							layer.msg('error:' + error);
						}
					}
				}

				if (!onSite) { //站内
					ajaxParameter.headers.token = base.userInfo().token;
				}

				$.ajax(ajaxParameter);
			} else {
				layer.alert("请求type错误！");
			}
		},
		/**
		 * 微信扫一扫
		 */
		scanning: function(btn) {

			base.wxConfig = wxConfig = {
				appId: app_config.APP_ID,
				noncestr: 'nihao123',
				timestamp: (Date.parse(new Date()) / 1000 + 7200).toString(),
				url: window.location.href
			};


			base.Ajax({
				type: 'get',
				url: app_config.BUS_API_URL + 'business/jsapi_ticket',
			}, function(data) {
				wxConfig.ticket = data.data.ticket;

				var signatureString = 'jsapi_ticket=' + wxConfig.ticket + '&noncestr=' + wxConfig.noncestr + '&timestamp=' +
					wxConfig.timestamp + '&url=' + wxConfig.url + '';

				wx.config({
					debug: false,
					// 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					appId: wxConfig.appId,
					// 必填，公众号的唯一标识
					timestamp: wxConfig.timestamp,
					// 必填，生成签名的时间戳
					nonceStr: wxConfig.noncestr,
					// 必填，生成签名的随机串
					signature: hex_sha1(signatureString),
					// 必填，签名
					jsApiList: ["scanQRCode"] // 必填，需要使用的JS接口列表
				});
			
				wx.ready(function() {
					//点击按钮扫描二维码
					wx.scanQRCode({
						needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
						scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
						success: function(res) {
							var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
							window.location.href = result; //因为我这边是扫描后有个链接，然后跳转到该页面
						}
					});

				});
			});
		},
		uuid: function(len, radix) {
			var timestamp = new Date().getTime();
			var char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' + timestamp;
			var chars = char.split('');

			var uuid = [],
				i;
			radix = radix || chars.length;

			if (len) {
				// Compact form
				for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
			} else {
				// rfc4122, version 4 form
				var r;

				// rfc4122 requires these characters
				uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
				uuid[14] = '4';

				// Fill in random data.  At i==19 set the high bits of clock sequence as
				// per rfc4122, sec. 4.1.5
				for (i = 0; i < 36; i++) {
					if (!uuid[i]) {
						r = 0 | Math.random() * 16;
						uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
					}
				}
			}
			return uuid.join('');
		},
		/**
		 * API认证
		 */
		apiData: function() {
			var $data = {
				random: base.uuid(16, Math.floor(Math.random() * (75 - 16 + 1) + 16)),
				timestamp: Date.parse(new Date()) / 1000
			};

			var encrypt = new JSEncrypt();
			encrypt.setPublicKey();
			var encryptData = encrypt.encrypt(JSON.stringify($data));

			return {
				sign: encryptData,
				random: $data.random,
				timestamp: $data.timestamp
			};
		},
		/**
		 * 手机号码校验
		 */
		isPhone: function(value) {
			let isTrue = true;
			if (!value || !(/^[1][34578]\d{9}$/).test(value) || !(/^[1-9]\d*$/).test(value) || value.length !== 11) {
				isTrue = false;
			}
			return isTrue;
		},
		/**
		 * 解决手机端头部fixed问题
		 */
		headMobile: function() {
			if (base.browserRedirect() != 'pc') {
				$('input').bind('focus', function() {
					$('.admin-header').css('position', 'static');
					//或者$('#viewport').height($(window).height()+'px');
				}).bind('blur', function() {
					$('.admin-header').css({
						'position': 'fixed'
					});
					//或者$('#viewport').height('auto');
				});
			}
		},
		/**
		 * 错误码
		 */
		errorCode: function(code) {
			var explain = {
				'0000': '成功',
				'0001': '失败',
				'0002': '数据不存在',
				'0003': '数据已存在',
				'0004': '状态不正常',
				'0005': '金额不够',
				'0006': '金额太小',
				'0007': '有正在处理的提现',
				'0008': '角色已存在',
				'0009': '密码不正确',
				'0010': '已核销过了',
				'0011': '微信认证code错误',
				'0012': '微信认证access_token错误',
				'0013': '微信认证使用refresh_token请求时错误',
				'0014': '微信须要重新授权',
				'0015': '微信支付失败',
				'0016': '用户状态不正常',
				'0017': 'API认证失败,检查token',
				'0018': 'API认证失败,检查时间差',
				'0019': 'API认证失败,检查签名在配制的最大时间内使用过',
				'0020': 'API认证失败,签名解决失败',
				'0021': '验证码数据不存在',
				'0022': '验证码错误',
				'0023': '未关注公众号',
				'0024': '商家状态不正常',
				'0025': '产品状态不正常',
				'0026': '库存不足',
				'0027': '达人状态不正常',
				'0028': '已在其它团队',
				'0029': '产品倒计时结束',
				'0030': '队长和队员不能相同',
				'0031': '订单已过期'
			}
			for (c in explain) {
				if (!explain[code]) {
					layer.msg('错误码不存在！code:' + code);
					return false;
				}
			}
			return explain[code];
		},
		isSendTrue: true, //是否可以点击发送短信
		findPwdUserInfo: '', //存储找回密码的参数
		sendMsg: function(settings) {
			let btn = settings.btn; //按钮id
			let time = settings.time; //倒计时时间
			let mobile = settings.mobile; //手机号

			if (btn && /^[0-9]*$/.test(time) && base.isSendTrue) {
				base.isSendTrue = false;
				base.Ajax({
					onSite: 1, //标识站内还是站外 1：站外
					type: 'post',
					url: app_config.BUS_API_URL + 'business/forget_pwd',
					data: {
						mobile: mobile
					}
				}, function(data) {
					if (data.code == '0000') {
// 						layer.msg('短信发送成功', {
// 							icon: 1
// 						});
						base.findPwdUserInfo = data.data;

						$(btn).addClass('am-disabled');
						var countdown = setInterval(function() {
							time -= 1;
							$(btn).val(time + 's 后重新发送');
							if (time == 0) { //倒计时结束
								clearInterval(countdown);
								$(btn).removeClass('am-disabled');
								$(btn).val('重新发送');
								base.isSendTrue = true;
							}
						}, 1000);
					}else{
						layer.msg(base.errorCode(data.code));
						base.isSendTrue = true;
					}
				})
			}
		},

	};
	module.exports = base;
});

/**
 * 全屏查看
 */

// (function($) {
// 	'use strict';

$(function() {
	'use strict';
	var $fullText = $('.admin-fullText');
	$('#admin-fullscreen').on('click', function() {
		$.AMUI.fullscreen.toggle();
	});

	$(document).on($.AMUI.fullscreen.raw.fullscreenchange, function() {
		$fullText.text($.AMUI.fullscreen.isFullscreen ? '退出全屏' : '开启全屏');
	});
});
// })(jQuery);
