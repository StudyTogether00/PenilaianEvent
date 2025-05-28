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
                    "icon" => '<span class="sidebar-mini">DA</span>',
                    "title" => "Data Admin",
                    "url" => "MasterData/Admin",
                ],
            ],
        ], [
            "icon" => '<i class="material-icons">content_copy</i>',
            "title" => "Proses",
            "sub_menu" => [
                [
                    "icon" => '<span class="sidebar-mini">DE</span>',
                    "title" => "Register Peserta",
                    "url" => "Process/Register",
                ],
                [
                    "icon" => '<span class="sidebar-mini">Dk</span>',
                    "title" => "Penilaian",
                    "url" => "Process/Nilai",
                ],
            ],
        ], [
            "icon" => '<i class="material-icons">content_copy</i>',
            "title" => "Laporan",
            "sub_menu" => [
                [
                    "icon" => '<span class="sidebar-mini">DE</span>',
                    "title" => "Normalisasi",
                    "url" => "Report/Normalisasi",
                ],
            ],
        ],
    ],
];
