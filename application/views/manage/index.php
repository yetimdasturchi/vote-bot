<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo ( isset( $index_title ) ? $index_title : lang( 'index_title' ) ); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico');?>">

        <!-- Bootstrap Css -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?php echo base_url('assets/css/icons.min.css');?>" rel="stylesheet" type="text/css" />

        <?php
            if ( isset( $load_css ) ) {
                foreach ($load_css as $css_file) {
        ?>
                <link href="<?php echo $css_file;?>" rel="stylesheet" type="text/css" />
        <?php  
                }

                unset( $load_css, $css_file );
            }
        ?>

        <!-- App Css-->
        <link href="<?php echo base_url('assets/css/app.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="<?php echo base_url('manage');?>" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?php echo base_url('assets/images/logo.svg');?>" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?php echo base_url('assets/images/logo-dark.png');?>" alt="" height="17">
                                </span>
                            </a>

                            <a href="<?php echo base_url('manage');?>" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="<?php echo base_url('assets/images/logo-light.svg');?>" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?php echo base_url('assets/images/logo-light.png');?>" alt="" height="19">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block">
                            <?php
                                $default_language = getDefaultLanguage( TRUE );
                            ?>
                            <button type="button" class="btn header-item waves-effect"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img id="header-lang-img" src="<?php echo base_url('assets/images/flags/'. $default_language['code'] .'.jpg');?>" height="16">
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php
                                    $languages = getLanguages();

                                    foreach ($languages as $k => $v) {
                                ?>
                                    <a href="<?php echo base_url( 'language/' . $k );?>" class="dropdown-item notify-item">
                                        <img src="<?php echo base_url('assets/images/flags/'. $v['code'] .'.jpg');?>" alt="user-image" class="me-1" height="12"> <span class="align-middle"><?php echo $v['name'];?></span>
                                    </a>
                                <?php
                                    }
                                    unset( $default_language, $languages, $index_title, $v, $k );
                                ?>
                            </div>
                        </div>

                        <?php echo build_navbar_menu();?>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="<?php echo $this->session->userdata('manager_photo_url');?>"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1" key="t-henry">
                                    <?php
                                        if ( $this->session->has_userdata('manager_first_name') ) {
                                            echo $this->session->userdata('manager_first_name');
                                        }else if ( $this->session->has_userdata('manager_username') ) {
                                            echo "@" . $this->session->userdata('manager_username');
                                        }else{
                                           echo "Foydalanuvchi"; 
                                        }
                                    ?>
                                </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item text-danger" href="<?php echo base_url('logout');?>"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout"><?php echo lang('index_logout');?></span></a>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">

                            <li class="menu-title"><?php echo lang('index_telegram_bot');?></li>

                            <li>
                                <a href="<?php echo base_url('users/active');?>" class="waves-effect">
                                    <i class="bx bx-user-circle"></i>
                                    <span><?php echo lang('index_users');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('users/referrals');?>" class="waves-effect">
                                    <i class="bx bx-link"></i>
                                    <span><?php echo lang('index_referrals');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-rss"></i>
                                    <span><?php echo lang('index_channels');?></span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <a href="<?php echo base_url('channels/add');?>"><?php echo lang('index_add');?></a>
                                    <a href="<?php echo base_url('channels/records');?>"><?php echo lang('index_list');?></a>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-bell"></i>
                                    <span><?php echo lang('index_nitifications');?></span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <a href="<?php echo base_url('notifications/add');?>"><?php echo lang('index_add');?></a>
                                    <a href="<?php echo base_url('notifications/records');?>"><?php echo lang('index_list');?></a>
                                </ul>
                            </li>

                            
                            <li>
                                <a href="<?php echo base_url('users/archive');?>" class="waves-effect">
                                    <i class="bx bx-archive-in"></i>
                                    <span><?php echo lang('index_archive');?></span>
                                </a>
                            </li>

                            <li class="menu-title"><?php echo lang('index_polls');?></li>

                            <li>
                                <a href="<?php echo base_url('polls/inline/records');?>" class="waves-effect">
                                    <i class="bx bx-poll"></i>
                                    <span><?php echo lang('index_polls_inline');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('polls/web/records');?>" class="waves-effect">
                                    <i class="bx bx-chart"></i>
                                    <span><?php echo lang('index_polls_webview');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('polls/votes');?>" class="waves-effect">
                                    <i class="bx bx-user-voice"></i>
                                    <span><?php echo lang('index_votes');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('polls/queue');?>" class="waves-effect">
                                    <i class="bx bxs-hourglass"></i>
                                    <span><?php echo lang('index_queue');?></span>
                                </a>
                            </li>

                            <?php echo build_sidebar_menu();?>
                            

                            <li class="menu-title"><?php echo lang('index_settings');?></li>

                            <li>
                                <a href="<?php echo base_url('xfields/records');?>" class="waves-effect">
                                    <i class="bx bx-id-card"></i>
                                    <span><?php echo lang('index_xfields');?></span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo base_url('bot/commands');?>" class="waves-effect">
                                    <i class="bx bx-bot"></i>
                                    <span><?php echo lang('index_commands');?></span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-crown"></i>
                                    <span><?php echo lang('index_managers');?></span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <a href="<?php echo base_url('managers/add');?>"><?php echo lang('index_add');?></a>
                                    <a href="<?php echo base_url('managers/records');?>"><?php echo lang('index_list');?></a>
                                </ul>
                            </li>
                            <?php
                                $modules_map = directory_map( APPPATH . 'modules', 1);
                                if (($key = array_search('index.html', $modules_map)) !== FALSE) unset($modules_map[$key]);

                                if ( !empty( $modules_map ) ) {
                            ?>
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                                            <i class="bx bx-code-block"></i>
                                            <span><?php echo lang('index_modules');?></span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <?php
                                                $language = $this->config->item('language');
                                                foreach ($modules_map as $k => $v) {
                                                    $module_config = $this->module->load_config( $v );
                                            ?>
                                            <a href="<?php echo base_url('modules/'.$v);?>"><?php echo $module_config['name'][$language];?></a>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            

            <div class="main-content">
                <?php
                    if ( isset( $content ) ) {
                        $this->load->view( $content, ( isset( $content_data ) ? $content_data : [] ) );
                        unset( $content, $content_data );
                    }
                    if ( isset( $display_content ) ) {
                        echo $display_content;
                    }
                ?>
                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                © <script>document.write(new Date().getFullYear())</script>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    <a href="https://manu.uz">@yetimdasturchi</a> -  (<?php echo $this->benchmark->elapsed_time();?> | <?php echo $this->benchmark->memory_usage();?>)
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>

        </div>
        <!-- END layout-wrapper -->
        <div id="export_data" style="display: none;"></div>

        <!-- JAVASCRIPT -->
        <script src="<?php echo base_url('assets/libs/jquery.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/bootstrap.bundle.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/metisMenu.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/simplebar.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/waves.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/jquery.growl.js');?>"></script>
        <script src="<?php echo base_url('assets/js/core.js');?>"></script>

        <?php
            if ( isset( $load_js ) ) {
                foreach ($load_js as $js_file) {
        ?>
                <script src="<?php echo $js_file;?>"></script>
        <?php  
                }

                unset( $load_js, $js_file );
            }
        ?>

        <?php if($this->session->flashdata('toast_error')): ?>
            <script type="text/javascript">
                $.growl.error({ title:"", message: "<?php echo $this->session->flashdata('toast_error'); ?>", location: "tr"});
            </script>
        <?php endif; ?>

        <?php if($this->session->flashdata('toast_success')): ?>
            <script type="text/javascript">
                $.growl.warning({ title:"", message: "<?php echo $this->session->flashdata('toast_success'); ?>", location: "tr"});
            </script>
        <?php endif; ?>

        <script src="<?php echo base_url('assets/js/app.js');?>"></script>

    </body>
</html>