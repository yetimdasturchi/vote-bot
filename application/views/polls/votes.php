<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_votes');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('polls_votes');?></li>
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
                                                'ordering' => false,
                                                'serverMethod' => 'post',
                                                'language' => [
                                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                                ],
                                                'ajax' => [
                                                    'url' => base_url('polls/votes/getlist'),
                                                    'cache' => false,
                                                    'data' => 'function(data){data.check_status_filter = $("[name=\'check_status_filter[]\']").val()}'
                                                ],
                                                'columns' => [
                                                    ['title' => lang('polls_id'), 'data' => 'id'],
                                                    ['title' => lang('poll_user'), 'data' => 'u_name'],
                                                    ['title' => lang('polls_contest'), 'data' => 'contest_name'],
                                                    ['title' => lang('polls_nomination'), 'data' => 'nomination_name'],
                                                    ['title' => lang('polls_member'), 'data' => 'member_name'],
                                                    ['title' => lang('polls_time'), 'data' => 'date'],
                                                    ['title' => lang('polls_action'), 'data' => 'action'],
                                                ],
                                                'fnRowCallback' => 'function(nRow, aData ){
                                                    if ( aData.check_status == \'0\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-warning\' );}
                                                    if ( aData.check_status == \'2\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-danger\' );}
                                                }',
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 5]],
                                                    ['orderable' => false, 'targets' => [6]]
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
                                                        'text' => '<i class="fa fa-filter"></i> ' . lang('polls_filter'),
                                                        'className' => 'btn-success',
                                                        'action' => 'function(e,dt,node,config){$(".bs-filter-modal").modal("show")}'
                                                    ]
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="votes" data-params="<?php echo $dataParams; ?>"></table>

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

                <div class="modal fade bs-filter-modal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="overflow:hidden;">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mySmallModalLabel"><?php echo lang('polls_filter');?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <select class="select2 form-control select2-multiple" name="check_status_filter[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode(['language' => getDefaultLanguage()]), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <option value="0"><?php echo lang('polls_in_queue');?></option>
                                            <option value="1"><?php echo lang('polls_in_success');?></option>
                                            <option value="2"><?php echo lang('polls_in_canceled');?></option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light bs-filter-button" data-table="votes"><?php echo lang('polls_save');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->