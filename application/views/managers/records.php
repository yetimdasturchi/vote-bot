<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('managers_records');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('managers_managers');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('managers_records');?></li>
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
                                                    'url' => base_url('managers/records/getlist'),
                                                    'cache' => false,
                                                ],
                                                'columns' => [
                                                    ['title' => lang('manager_id'), 'data' => 'manager_id'],
                                                    ['title' => lang('manager_name'), 'data' => 'manager_name'],
                                                    ['title' => lang('manager_telegram'), 'data' => 'manager_telegram'],
                                                    ['title' => lang('manager_created'), 'data' => 'manager_created'],
                                                    ['title' => lang('manager_logged'), 'data' => 'manager_logged'],
                                                    ['title' => lang('manager_action'), 'data' => 'manager_action'],
                                                ],
                                                'fnRowCallback' => 'function(nRow, aData ){if ( aData.manager_status == \'0\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-danger\' );}}',
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
                                                    [lang('managers_records_10'), lang('managers_records_25'), lang('managers_records_50'), lang('managers_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('managers_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('managers_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ]
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="managers_records" data-params="<?php echo $dataParams; ?>"></table>

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