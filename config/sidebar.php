<?php
return [
    "menu"=> [
        [
            "icon"=>'<i class="material-icons">dashboard</i>',
            "title"=>"Dashboard",
            "url"=>"/",
        ],[
            "icon"=>'<i class="material-icons">content_copy</i>',
            "title"=>"Master Data",
            "sub_menu"=>[
                [
                    "icon"=>'<span class="sidebar-mini">U</span>',
                    "title"=>"Master user",
                    "url"=>"/MasterData/MstUser",
                ],
                [
                    "icon"=>'<span class="sidebar-mini">U</span>',
                    "title"=>"Master Bidang",
                    "url"=>"/MasterData/MstUser",
                ]
            ]
        ]
    ],
];