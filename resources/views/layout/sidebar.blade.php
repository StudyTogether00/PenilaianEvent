<div class="sidebar" data-color="rose" data-background-color="black" data-image="assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="/" class="simple-text logo-mini">AS</a>
        <a href="/" class="simple-text logo-normal">Aplikasi Seleksi</a>
    </div>
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo"><img src="assets/img/faces/adit.jpeg" /></div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>
                        Aditya Nur Hakim
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> MP </span>
                                <span class="sidebar-normal"> My Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> EP </span>
                                <span class="sidebar-normal"> Edit Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> S </span>
                                <span class="sidebar-normal"> Settings </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">
            @php

                $currentUrl = Request::path();
                function renderSubMenu($value, $currentUrl)
                {
                    $subMenu = '';
                    $GLOBALS['sub_level'] += 1;
                    $GLOBALS['active'][$GLOBALS['sub_level']] = '';
                    $currentLevel = $GLOBALS['sub_level'];
                    foreach ($value as $key => $menu) {
                        $GLOBALS['subparent_level'] = '';

                        // Setting variable untuk Sub sub_menu dan tidak
                        $url = !empty($menu['sub_menu'])
                            ? "data-toggle=\"collapse\" href=\"#{$GLOBALS['sub_level']}0{$key}\""
                            : "href=\"{$menu['url']}\"";
                        $title = !empty($menu['sub_menu']) ? "<b class=\"caret\"></b>" : '';
                        $title = "<p>{$menu['title']}{$title}</p>";

                        // Sub Sub Menu
                        $subsubMenu = '';
                        if (!empty($menu['sub_menu'])) {
                            $subsubMenu = "<div class=\"collapse\" id=\"{$GLOBALS['sub_level']}0{$key}\"><ul class=\"nav\">";
                            $subsubMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
                            $subsubMenu .= '</ul></div>';
                        }

                        // Setting Sub Menu Active
                        $active = $currentUrl == $menu['url'] ? 'active' : '';
                        if ($active == 'active') {
                            $GLOBALS['parent_active'] = true;
                            $GLOBALS['active'][$GLOBALS['sub_level'] - 1] = true;
                        }

                        $subMenu .= "<li class=\"nav-item {$active}\">
                                        <a class=\"nav-link\" {$url}>
                                            {$menu['icon']} {$title}
                                        </a>
                                        {$subsubMenu}
                                    </li>";
                    }
                    return $subMenu;
                }

                foreach (config('sidebar.menu') as $key => $value) {
                    $GLOBALS['parent_active'] = '';
                    // Setting variable untuk sub_menu dan tidak
                    $url = !empty($value['sub_menu'])
                        ? "data-toggle=\"collapse\" href=\"#{$key}\""
                        : "href=\"{$value['url']}\"";
                    $title = !empty($value['sub_menu']) ? "<b class=\"caret\"></b>" : '';
                    $title = "<p>{$value['title']}{$title}</p>";

                    // Sub Menu
                    $subMenu = '';
                    if (!empty($value['sub_menu'])) {
                        $GLOBALS['sub_level'] = 0;
                        $subMenu = "<div class=\"collapse\" id=\"{$key}\"><ul class=\"nav\">";
                        $subMenu .= renderSubMenu($value['sub_menu'], $currentUrl);
                        $subMenu .= '</ul></div>';
                    }
                    // Setting Menu Active
                    $active = '';
                    if (isset($value['url'])) {
                        $active =
                            $currentUrl == $value['url'] || ($value['url'] == '/' && $currentUrl == '/')
                                ? 'active'
                                : '';
                    }
                    $active = empty($active) && !empty($GLOBALS['parent_active']) ? 'active' : $active;

                    // hasil yang ditampilkan
                    $hsl = "<li class=\"nav-item {$active}\">
                                <a class=\"nav-link\" {$url}>
                                    {$value['icon']} {$title}
                                </a>
                                {$subMenu}
                            </li>";
                    echo $hsl;
                }
            @endphp
            <li class="nav-item ">
                <a class="nav-link" href="../pages/Normalisasi">
                    <i class="material-icons">widgets</i>
                    <p> Normalisasi </p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="../pages/Laporan">
                    <i class="material-icons">timeline</i>
                    <p> laporan </p>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-background"></div>
</div>
