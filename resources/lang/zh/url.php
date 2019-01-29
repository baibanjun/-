<?php
return [
    // 获取accessToken get
    'accessTokenUrl'    => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=:APPID&secret=:APPSECRET',
    // 创建菜单 post
    'createMenuUrl'     => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=:access_token',
    // 获取用户基本信息 get
    'user_info'         => 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=:access_token&openid=:openid&lang=zh_CN',
    // 获取二维码
    'qrcode'            => 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=:access_token',
    //获取
    'jsapi_ticket'      => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=:access_token&type=jsapi',
    
    // 微信网页授权-获取code get
    'auth_get_code'     => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=:appid&redirect_uri=:redirect_uri&response_type=code&scope=:scope&state=:state#wechat_redirect',
    // 微信网页授权-通过code换取网页授权access_token get
    'auth_get_access_token' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=:appid&secret=:secret&code=:code&grant_type=authorization_code',
    // 刷新access_token get
    'auth_refresh_token'    => 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=:appid&grant_type=refresh_token&refresh_token=:refresh_token',
    // 拉取用户信息(需scope为 snsapi_userinfo)
    'auth_get_userinfo'     => 'https://api.weixin.qq.com/sns/userinfo?access_token=:access_token&openid=:openid&lang=zh_CN',
    // 检验授权凭证（access_token）是否有效 get
    'auth_validation_access_token' => 'https://api.weixin.qq.com/sns/auth?access_token=:access_token&openid=:openid',
    
    // 主动发送文字信息
    'send_text'=>'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=:access_token',
    // 主动发送模板信息
    'send_template'=>'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=:access_token',
    
    //获取永久素材
    'material'=>'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=:access_token',
    //获取素材列表
    'batchget_material'=>'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=:access_token',
];