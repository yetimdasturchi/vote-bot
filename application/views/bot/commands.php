<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('commands');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('commands_telegram_bot');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('commands');?></li>
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
                                                    'url' => base_url('bot/commands/getlist'),
                                                    'cache' => false,
                                                ],
                                                'columns' => [
                                                    ['title' => lang('command_id'), 'data' => 'command_id'],
                                                    ['title' => lang('command_command'), 'data' => 'command_set'],
                                                    ['title' => lang('command_parent'), 'data' => 'parent_name'],
                                                    ['title' => lang('command_sort'), 'data' => 'sort'],
                                                    ['title' => lang('command_type'), 'data' => 'type'],
                                                    ['title' => lang('command_chunk'), 'data' => 'chunk'],
                                                    ['title' => lang('command_function'), 'data' => 'command_function'],
                                                    ['title' => lang('command_language'), 'data' => 'language'],
                                                    ['title' => lang('command_action'), 'data' => 'command_action'],
                                                ],
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 2, 3, 4, 5, 6, 7, 8]],
                                                    ['orderable' => false, 'targets' => [4,8]]
                                                ],
                                                'order' => [
                                                    [1, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                'lengthMenu' => [
                                                    [10, 25, 50, -1],
                                                    [lang('commands_records_10'), lang('commands_records_25'), lang('commands_records_50'), lang('commands_records_all')]
                                                ],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('commands_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('commands_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-plus"></i> ' . lang('command_add'),
                                                        'className' => 'btn-success',
                                                        'action' => 'function(e,dt,node,config){ window.location.href = "'.base_url('bot/commands/add').'" }'
                                                    ]
                                                ],
                                                'drawCallback' => 'function(settings){
                                                    ($.fn.editableform.buttons = \'<button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>\');

                                                    $(".inline-sort").editable({
                                                        validate: function (e) {
                                                            if ("" == $.trim(e)) return "'.lang('command_this_field_is_required').'";
                                                        },
                                                        name: "sort",
                                                        placeholder: "'.lang('command_enter_sort_number').'",
                                                        title: "'.lang('command_enter_sort_number').'",
                                                        type: "number",
                                                        mode: "inline",
                                                        inputclass: "form-control-sm",
                                                        url: "'. base_url('bot/commands/update_field') .'",
                                                        ajaxOptions: {
                                                            type: \'post\'
                                                        },
                                                        success: function(res, newValue) {
                                                            if ( res.status ) {
                                                                if ( res.hasOwnProperty(\'messages\') ) {
                                                                    $.each(res.messages, function (key, val) {
                                                                        $.growl.warning({ title:"", message: val, location: "tr"});
                                                                    });
                                                                }
                                                            }
                                                        }, 
                                                    });

                                                    $(".inline-chunk").editable({
                                                        validate: function (e) {
                                                            if ("" == $.trim(e)) return "'.lang('command_this_field_is_required').'";
                                                        },
                                                        name: "chunk",
                                                        title: "'.lang('command_chunk').'",
                                                        type: "select",
                                                        mode: "inline",
                                                        inputclass: "form-control-sm",
                                                        url: "'. base_url('bot/commands/update_field') .'",
                                                        source: [
                                                            { value: 1, text: "1" },
                                                            { value: 2, text: "2" },
                                                            { value: 3, text: "3" },
                                                            { value: 4, text: "4" },
                                                            { value: 5, text: "5" },
                                                            { value: 6, text: "6" },
                                                        ],
                                                        ajaxOptions: {
                                                            type: \'post\'
                                                        },
                                                        success: function(res, newValue) {
                                                            if ( res.status ) {
                                                                if ( res.hasOwnProperty(\'messages\') ) {
                                                                    $.each(res.messages, function (key, val) {
                                                                        $.growl.warning({ title:"", message: val, location: "tr"});
                                                                    });
                                                                }
                                                            }
                                                        }, 
                                                    });
                                                }'
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="commands" data-params="<?php echo $dataParams; ?>"></table>

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