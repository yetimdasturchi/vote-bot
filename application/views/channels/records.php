<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('channels_records');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('channels');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('channels_records');?></li>
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
                                                    'url' => base_url('channels/records/getlist'),
                                                    'cache' => false,
                                                ],
                                                'columns' => [
                                                    ['title' => lang('channels_id'), 'data' => 'channel_id'],
                                                    ['title' => lang('channels_name'), 'data' => 'channel_name'],
                                                    ['title' => lang('channel_chat_username'), 'data' => 'channel_chat_username'],
                                                    ['title' => lang('channel_chat_id'), 'data' => 'channel_chat_id'],
                                                    ['title' => lang('channel_subscription'), 'data' => 'channel_subscription'],
                                                    ['title' => lang('channel_action'), 'data' => 'channel_action'],
                                                ],
                                                'fnRowCallback' => 'function(nRow, aData ){if ( aData.channel_status == \'0\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-danger\' );}}',
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 3, 4, 5]],
                                                    ['orderable' => false, 'targets' => [5]]
                                                ],
                                                'order' => [
                                                    [0, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                'lengthMenu' => [
                                                    [10, 25, 50, -1],
                                                    [lang('channels_records_10'), lang('channels_records_25'), lang('channels_records_50'), lang('channels_records_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('channels_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('channels_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ]
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="channels_records" data-params="<?php echo $dataParams; ?>"></table>

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