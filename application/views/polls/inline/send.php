<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('poll_send');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('poll_send');?></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/inline/send/'.$id);?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-question" class="form-label"><?php echo lang('polls_channels');?></label>
                                        <?php
                                            $slect2Params = [
                                                'language' => getDefaultLanguage(),
                                                'ajax' => [
                                                    'url' => base_url('polls/inline/send/'.$id.'/channels'),
                                                    'tags' => true,
                                                    'type' => 'get',
                                                    'multiple' => true,
                                                    'dataType' => 'json',
                                                    'allowClear' => true,
                                                    'delay' => 250,
                                                    'cache' => true,
                                                    'data' => 'function(params){return { search: params.term, page: params.page || 1 }; }',
                                                    'processResults' => 'function (response) {return {results: response}}'
                                                ]
                                            ];
                                        ?>
                                        <select class="select2 form-control select2-multiple" name="channels[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('poll_send');?></button>
                            </div>
                        </form>
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