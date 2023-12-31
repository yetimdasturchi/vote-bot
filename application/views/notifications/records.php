<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('notifications_records');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('notifications');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('notifications_records');?></li>
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
                                            $status = file_exists( APPPATH . 'config/notifications.php' ) ? include( APPPATH . 'config/notifications.php' ) : FALSE;
                                            $dataParams = [
                                                'processing' => true,
                                                'serverSide' => true,
                                                'searching' => false,
                                                'serverMethod' => 'post',
                                                'fnDrawCallback' => "function(){\$('[data-bs-toggle=\"tooltip\"]').tooltip();}",
                                                'language' => [
                                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                                ],
                                                'ajax' => [
                                                    'url' => base_url('notifications/records/getlist'),
                                                    'cache' => false,
                                                ],
                                                'columns' => [
                                                    ['title' => lang('notifications_user'), 'data' => 'user'],
                                                    ['title' => lang('notifications_message'), 'data' => 'message'],
                                                    ['title' => lang('notifications_type'), 'data' => 'type'],
                                                    ['title' => lang('notifications_time'), 'data' => 'time']
                                                ],
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [1, 2, 3]]
                                                ],
                                                'order' => [
                                                    [0, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                'lengthMenu' => [
                                                    [10, 25, 50, -1],
                                                    [lang('notifications_records_10'), lang('notifications_records_25'), lang('notifications_records_50'), lang('notifications_records_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('notifications_records_1'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('notifications_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-trash"></i>',
                                                        'className' => 'btn-danger',
                                                        'attr' => [
                                                            'data-ajax-button' => base_url('notifications/records/clear'),
                                                            'data-message' => lang('notifications_are_you_want_clear')
                                                        ]
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-'.( $status ? 'pause' : 'play' ).'"></i>',
                                                        'className' => 'notifications-status btn-'.( $status ? 'warning' : 'success' ),
                                                        'attr' => [
                                                            'data-ajax-button' => base_url('notifications/records/' . ( $status ? 'pause' : 'play' ) ),
                                                            'data-message' => lang( ( $status ? 'notifications_are_you_want_pause' : 'notifications_are_you_want_play' ) )
                                                        ]
                                                    ]
                                                ],
                                                'initCompleted' => 'function (settings, json){(function delay() {$dtables[\'notifications_records\'].ajax.reload(null, false);setTimeout(delay, 3000);})();}'
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="notifications_records" data-params="<?php echo $dataParams; ?>"></table>

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