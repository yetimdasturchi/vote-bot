<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_nominations');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls_contests');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('polls_nominations');?></li>
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
                                                    'url' => base_url('polls/web/nominations/'.$id.'/getlist'),
                                                    'cache' => false,
                                                    'data' => 'function(data){data.contest = '.$id.';}'
                                                ],
                                                'columns' => [
                                                    ['title' => lang('polls_id'), 'data' => 'id'],
                                                    ['title' => lang('poll_name'), 'data' => 'name'],
                                                    ['title' => lang('polls_max_votes'), 'data' => 'max_votes'],
                                                    ['title' => lang('polls_action'), 'data' => 'action']
                                                ],
                                                'fnRowCallback' => 'function(nRow, aData ){if ( aData.status == \'0\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-danger\' );}}',
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 2, 3]],
                                                    ['orderable' => false, 'targets' => [3]]
                                                ],
                                                'order' => [
                                                    [0, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                'lengthMenu' => [
                                                    [10, 25, 50, -1],
                                                    [lang('polls_records_10'), lang('polls_records_25'), lang('polls_records_50'), lang('polls_records_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('polls_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('polls_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-plus"></i> ' . lang('polls_add'),
                                                        'className' => 'btn-success',
                                                        'action' => 'function(e,dt,node,config){ window.location.href = "'.base_url('polls/web/nominations/'.$id.'/add').'" }'
                                                    ]
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="polls_web_nominations" data-params="<?php echo $dataParams; ?>"></table>

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