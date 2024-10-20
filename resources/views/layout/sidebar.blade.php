<div class="sidebar" data-color="rose" data-background-color="black" data-image="assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="/" class="simple-text logo-mini">
          AS
        </a>
        <a href="/" class="simple-text logo-normal">
          Aplikasi Seleksi
        </a></div>
      <div class="sidebar-wrapper">
        <div class="user">
          <div class="photo">
            <img src="assets/img/faces/avatar.jpg" />
          </div>
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
                        
                        // Setting variable untuk sub_menu dan tidak
                        $url = !empty($menu['sub_menu'])
                            ? "data-toggle=\"collapse\" href=\"#{$GLOBALS['sub_level']}0{$key}\""
                            : "href=\"{$menu['url']}\"";
                        $title = !empty($menu['sub_menu']) ? "‹b class=\"caret\"></b>" : '';
                        $title = "<p>{$menu['title']}{$title}</p>";

                        //sub sub menu
                        $subsubMenu = '';
                        if (!empty($menu['sub_menu'])) {
                            $subsubMenu = "<div class=\"collapse\"id=\"{$GLOBALS['sub_level']}0{$key}\"><ul class>
                            $subsubMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
                            $subsubMenu .= '</ul></div>'; 
                        }

                        // setting sub menu active

            @endphp
          <li class="nav-item active ">
            <a class="nav-link" href="/">
              <i class="material-icons">dashboard</i>
              <p> Dashboard </p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="datapengguna">
              <i class="material-icons">image</i>
              <p> Data Pengguna
              </p>
            </a>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="dataevent">
              <i class="material-icons">apps</i>
              <p> Data Event
              </p>
            </a>
          <li class="nav-item ">
            <a class="nav-link" href="datakriteria">
              <i class="material-icons">content_paste</i>
              <p> Data Kriteria
              </p>
            </a>
          <li class="nav-item ">
            <a class="nav-link" href="databobot">
              <i class="material-icons">grid_on</i>
              <p> Data Bobot
              </p>
            </a>
          <li class="nav-item ">
            <a class="nav-link" href="datapeserta">
              <i class="material-icons">place</i>
              <p> Data Peserta
              </p>
            </a>
          <li class="nav-item ">
            <a class="nav-link" href="datanilai">
              <i class="material-icons">widgets</i>
              <p> Data Nilai </p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="Normalisasi">
              <i class="material-icons">timeline</i>
              <p> Normalisasi </p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="laporan">
              <i class="material-icons">date_range</i>
              <p> Laporan </p>
            </a>
            <li class="nav-item ">
            <a class="nav-link" href="ujicoba">
              <i class="material-icons">content_paste</i>
              <p> Uji Coba</p>
            </a>
          </li>
        </ul>
      </div>
      <div class="sidebar-background"></div>
    </div>