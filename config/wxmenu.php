<?php
return [
    'button' => [
        [
            'type' => 'view',
            'name' => '商城首页',
            'url' => env('WEB_INDEX')
        ],
        [
            'type' => 'view',
            'name' => '点击抽奖',
            'sub_button'=>[]
        ],
        [
            'name' => '用户中心',
            "sub_button" => [
                [
                    "type" => "view",
                    "name" => "我的订单",
                    "url" => env('WEB_INDEX').'user'
                ],
                [
                    "type" => "view",
                    "name" => "我的优惠卷",
                    "url" => env('WEB_INDEX').'my_coupon'
                ],
                [
                    "type" => "view",
                    "name" => "申请合伙人",
                    "url" => env('WEB_INDEX').'extension'
                ],
                [
                    "type" => "click",
                    "name" => "客服热线",
                    "key" => "click2"
                ],
                [
                    "type" => "click",
                    "name" => "商务合作",
                    "key" => "click3"
                ]
            ]
        ]
    ]
];