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
        <link href="<?php echo base_url('assets/css/app.min.css?v=1.4');?>" id="app-style" rel="stylesheet" type="text/css" />
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
                            <a href="<?php echo base_url('client');?>" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?php echo base_url('assets/images/logo.svg');?>" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?php echo base_url('assets/images/logo-dark.png');?>" alt="" height="17">
                                </span>
                            </a>

                            <a href="<?php echo base_url('client');?>" class="logo logo-light">
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
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="<?php echo $this->session->userdata('client_photo_url');?>"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1" key="t-henry">
                                    <?php
                                        if ( $this->session->has_userdata('client_first_name') ) {
                                            echo $this->session->userdata('client_first_name');
                                        }else if ( $this->session->has_userdata('client_username') ) {
                                            echo "@" . $this->session->userdata('client_username');
                                        }else{
                                           echo "Foydalanuvchi"; 
                                        }
                                    ?>
                                </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item text-danger" href="<?php echo base_url('client/login/logout');?>"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Chiqish</span></a>
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

                            <li class="menu-title">Statistika</li>

                            <li>
                                <a href="<?php echo base_url('client');?>" class="waves-effect">
                                    <i class="bx bx-customize"></i>
                                    <span>Umumiy</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/infographic');?>" class="waves-effect">
                                    <i class="bx bx-selection"></i>
                                    <span>Infografika</span>
                                </a>
                            </li>

                            <li class="menu-title">Telegram bot</li>

                            <li>
                                <a href="<?php echo base_url('client/users');?>" class="waves-effect">
                                    <i class="bx bx-user-circle"></i>
                                    <span>Foydalanuvchilar</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/referrals');?>" class="waves-effect">
                                    <i class="bx bx-link"></i>
                                    <span>Referallar</span>
                                </a>
                            </li>


                            <li>
                                <a href="<?php echo base_url('client/archive');?>" class="waves-effect">
                                    <i class="bx bx-archive-in"></i>
                                    <span>Arxiv</span>
                                </a>
                            </li>

                            <li class="menu-title">Bildirishnomalar</li>

                            <li>
                                <a href="<?php echo base_url('client/notifications/add');?>" class="waves-effect">
                                    <i class="bx bx-edit"></i>
                                    <span>Qo'shish</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/notifications/templates');?>" class="waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Andozalar</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/notifications/records');?>" class="waves-effect">
                                    <i class="bx bx-mail-send"></i>
                                    <span>Jarayon</span>
                                </a>
                            </li>

                            <!-- <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-bell"></i>
                                    <span>Bildirishnomalar</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <a href="<?php echo base_url('client/notifications/add');?>">Qo'shish</a>
                                    <a href="<?php echo base_url('client/notifications/templates');?>">Andozalar</a>
                                    <a href="<?php echo base_url('client/notifications/records');?>">Jarayon</a>
                                </ul>
                            </li> -->

                            <li class="menu-title">Ovozlar</li>

                            <li>
                                <a href="<?php echo base_url('client/votes?status[]=0');?>" class="waves-effect">
                                    <i class="bx bx-time-five"></i>
                                    <span>Jarayonda</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/votes?status[]=1');?>" class="waves-effect">
                                    <i class="bx bx-check-double"></i>
                                    <span>Tekshirilgan</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url('client/votes?status[]=2');?>" class="waves-effect">
                                    <i class="bx bx-block"></i>
                                    <span>Bekor qilingan</span>
                                </a>
                            </li>

                            <li class="menu-title">Savollar</li>

                            <li>
                                <a href="<?php echo base_url('client/question?id[]=13&id[]=14');?>" class="waves-effect">
                                    <i class="bx bx-help-circle"></i>
                                    <span>Jinsingizni ko'rsating</span>
                                </a>
                                <a href="<?php echo base_url('client/question?id[]=15&id[]=16');?>" class="waves-effect">
                                    <i class="bx bx-help-circle"></i>
                                    <span>Hududni ko'rsating</span>
                                </a>
                                <a href="<?php echo base_url('client/question?id[]=17&id[]=18');?>" class="waves-effect">
                                    <i class="bx bx-help-circle"></i>
                                    <span>Yoshingizni ko'rsating</span>
                                </a>
                            </li>
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
                                © <?php echo date('Y');?>
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

        <div class="modal fade bs-ajax-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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

        <script src="<?php echo base_url('assets/js/app.js?v=1.4');?>"></script>

    </body>
</html>