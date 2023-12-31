<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('users_active');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('users_users');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('users_active');?></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php 
                                            $dataParams = [
                                                'processing' => true,
                                                'serverSide' => true,
                                                'serverMethod' => 'post',
                                                'language' => [
                                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                                ],
                                                'ajax' => [
                                                    'url' => base_url('users/active/getlist'),
                                                    'cache' => false,
                                                ],
                                                'columns' => [
                                                    ['title' => lang('users_id'), 'data' => 'id'],
                                                    ['title' => lang('users_username'), 'data' => 'username'],
                                                    ['title' => lang('users_phone'), 'data' => 'phone'],
                                                    ['title' => lang('users_registered'), 'data' => 'registered'],
                                                    ['title' => lang('users_last_action'), 'data' => 'last_action'],
                                                    ['title' => lang('users_action'), 'data' => 'action']
                                                ],
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 2, 3, 4, 5]],
                                                    ['orderable' => false, 'targets' => [5]]
                                                ],
                                                'order' => [
                                                    [4, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                'lengthMenu' => [
                                                    [10, 25, 50, -1],
                                                    [lang('users_records_10'), lang('users_records_25'), lang('users_records_50'), lang('users_records_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('users_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('users_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ],
                                                    [
                                                        'text' => '<i class="far fa-file-excel"></i> ' . lang('user_export'),
                                                        'className' => 'btn-warning',
                                                        'attr' => [
                                                            'data-export-button' => base_url('/users/active/export')
                                                        ]
                                                    ],
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="polls_web_records" data-params="<?php echo $dataParams; ?>"></table>

                                        <?php
                                            unset($dataParams);
                                        ?>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->