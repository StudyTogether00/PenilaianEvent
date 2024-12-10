<?php
return [
    "menu" => [
        [
            "icon" => '<i class="material-icons">dashboard</i>',
            "title" => "Dashboard",
            "url" => "/",
        ], [
            "icon" => '<i class="material-icons">content_copy</i>',
            "title" => "Master Data",
            "sub_menu" => [
                [
                    "icon" => '<span class="sidebar-mini">DE</span>',
                    "title" => "Data Event",
                    "url" => "MasterData/Event",
                ],
                [
                    "icon" => '<span class="sidebar-mini">Dk</span>',
                    "title" => "Data Kriteria",
                    "url" => "MasterData/Kriteria",
                ],
                [
                    "icon" => '<span class="sidebar-mini">DB</span>',
                    "title" => "Data Bobot",
                    "url" => "MasterData/Bobot",
                ],
                [
                    "icon" => '<span class="sidebar-mini">DP</span>',
                    "title" => "Data Peserta",
                    "url" => "MasterData/Peserta",
                ],
                [
                    "icon" => '<span class="sidebar-mini">DN</span>',
                    "title" => "Data Nilai",
                    "url" => "MasterData/MstUser",
                ]
            ]
        ]
    ],
];
