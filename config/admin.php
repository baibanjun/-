<?php
return [
    // 管理后台
    'login_error_num' => 5, // 后台登录错误次数限制
    'login_error_next_time' => 3600, // 后台登录错误解除限制的时间间隔（秒）
    
    'code' => [
        'sys' => [
            'success' => '0000', // 成功
            'fail' => '0001', // 失败
            'dataDoesNotExist' => '0002', // 数据不存在
            'dataDoesExist' => '0003', // 数据已存在
            'statusIsNotNormal' => '0004', // 状态不正常
            'insufficientFunds' => '0005', // 金额不够
            'amountIsTooSmall' => '0006', // 金额太小
            'unprocessedWithdrawal' => '0007', // 有正在处理的提现
            'rolesAlreadyExist' => '0008', // 角色已存在
            'incorrect_password' => '0009', // 密码不正确
            'codeIsAuthenticated' => '0010', // 已核销过了
            'wx_code_error' => '0011', // 微信认证code错误
            'wx_access_token_error' => '0012', // 微信认证access_token错误
            'wx_refresh_token_error' => '0013', // 微信认证使用refresh_token请求时错误
            'weChatNeedsToBeReauthorized' => '0014', // 微信须要重新授权
            
            'wxPayFail' => '0015', // 微信支付失败
            'userStatusIsNotNormal' => '0016', // 用户状态不正常
            'authenticationFailed' => '0017', // API认证失败,检查token
            'authenticationFailedTime' => '0018', // API认证失败,检查时间差
            'authenticationFailedExists' => '0019', // API认证失败,检查签名在配制的最大时间内使用过
            'authenticationFailedEncrypted' => '0020', // API认证失败,签名解决失败
            'smsDataDoesNotExist' => '0021', // 验证码数据不存在
            'smsCodeFail' => '0022', // 验证码错误
            'dontAttention' => '0023', // 未关注公众号
            'busStatusIsNotNormal' => '0024', // 商家状态不正常
            'proStatusIsNotNormal' => '0025', // 产品状态不正常
            'insufficientInventory' => '0026', // 库存不足
            'talentStatusIsNotNormal' => '0027', // 达人状态不正常
            'teamAlreadyExist' => '0028', // 已在其它团队
            'productEndOfCountdown' => '0029', // 产品倒计时结束
            'captainsAndPlayerNotAlike' => '0030', // 队长和队员不能相同
            
            'dataFali' => '0031', // 数据错误
            'userAccountNotExist' => '0032', // 用户账户不存在
        ],
        'admin' => [
            'loginErrorNum' => '1001', // 密码错误到达上限
            'passwordMistake' => '1002', // 账号或密码错误
            'loginFail' => '1003', // 登录失败
            'tokenFail' => '1004', // 登录认证失效
            'paramFail' => '1005', // 数据错误,
            'hasNoPowerRegister' => '1006', // 没有新增用户的权限
            'timeFail' => '1007', // 产品倒计时时间不正确
            'businessFreezeOrderFail' => '1008', // 商家有已支付未完成的订单时不能冻结商家
            'businessUsernameOrMobileExist' => '1009', // 核销系统用户名或登录手机号已存在
            'businessIsNotExistOrStatusFail' => '1010', // 商家不存在或已冻结
            'cityCodeFail' => '1011', // 城市不存在
            'lottoryDrawNumberFail' => '1012', //奖品数量错误
            'lottoryDrawProbabilityFail' => '1013', //所有的奖品中奖率之和必须为100%
            'lottoryDrawListNotExist' => '1014', //奖品不存在
            'lottoryDrawSurplusNumberFail' => '1015', //活动奖品总库存为0
            'lottoryDrawHasEnough' => '1016', //平台已有三个正在进行的抽奖活动
        ]
    ]
];