<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('poll_options');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('poll_options');?></li>
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
                                'searching' => false,
                                'paging' => false,
                                'info' => false,
                                'ordering' => false,
                                'iDisplayLength' => -1,
                                'language' => [
                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                ],
                                'ajax' => [
                                    'url' => base_url('polls/inline/options/'.$id.'/getlist'),
                                    'cache' => false,
                                    'data' => 'function(data){data.question_id = '.$id.';}'
                                ],
                                'columns' => [
                                    ['title' => lang('polls_id'), 'data' => 'id'],
                                    ['title' => lang('poll_answer'), 'data' => 'answer'],
                                    ['title' => lang('polls_action'), 'data' => 'action']
                                ],
                                'fnRowCallback' => 'function(nRow, aData ){if ( aData.status == \'0\' ){$(nRow).alterClass( \'tr-status-*\', \'tr-status-danger\' );}}',
                                'columnDefs' => [
                                    ['className' => 'text-center', 'targets' => [0,2]]
                                ],
                                'responsive' => true,
                                'buttonsDom' => 'Bfrtip',
                                'dom' => 'Bfrtip',
                                'buttons' => [
                                    [
                                        'text' => '<i class="fa fa-plus"></i> ' . lang('polls_add'),
                                        'className' => 'btn-success',
                                        'action' => 'function(e,dt,node,config){ $(".bs-add-modal").modal("show") }'
                                    ]
                                ],
                                'drawCallback' => 'function(settings){
                                    ($.fn.editableform.buttons = \'<button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>\');

                                    $(".inline-answer").editable({
                                        validate: function (e) {
                                            if ("" == $.trim(e)) return "'.lang('poll_this_field_is_required').'";
                                        },
                                        name: "answer",
                                        placeholder: "'.lang('poll_enter_sort_number').'",
                                        title: "'.lang('poll_enter_sort_number').'",
                                        type: "text",
                                        mode: "inline",
                                        inputclass: "form-control-sm",
                                        url: "'. base_url('polls/inline/options/'.$id.'/update_field') .'",
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
                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="polls_inline_options" data-params="<?php echo $dataParams; ?>"></table>

                        <?php
                            unset($dataParams);
                        ?>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</div>

<div class="modal fade bs-add-modal" role="dialog" aria-hidden="true" style="overflow:hidden;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('poll_add_info');?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="ajax-form" method="POST" action="<?php echo base_url('polls/inline/options/'.$id.'/addanswer');?>" autocomplete="off">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <input type="text" name="answer" class="form-control" id="formrow-answer" placeholder="<?php echo lang('poll_enter_option');?>">
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info btn-sm waves-effect waves-light bs-filter-button" data-table="users_archive"><?php echo lang('polls_save');?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->