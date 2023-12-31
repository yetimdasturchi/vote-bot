<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_add');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('polls_add');?></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/inline/add');?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <input type="text" name="name" class="form-control" id="formrow-name" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-question" class="form-label"><?php echo lang('polls_poll');?></label>
                                        <textarea type="number" name="question" class="form-control" id="formrow-question" placeholder="<?php echo lang('polls_enter_poll');?>" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1"><?php echo lang('polls_active');?></option>
                                            <option value="0"><?php echo lang('polls_inactive');?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="formrow-language" class="form-label"><?php echo lang('polls_expire');?> (<?php echo lang('polls_date');?>)</label>
                                                <div class="input-group" id="datepicker">
                                                    <input type="text" name="date" class="form-control"
                                                        data-date-format="dd-mm-yyyy" data-date-container='#datepicker' data-provide="datepicker"
                                                        data-date-autoclose="true" data-date-language="<?php echo getDefaultLanguage();?>">

                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div><!-- input-group -->
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="formrow-language" class="form-label"><?php echo lang('polls_expire');?> (<?php echo lang('polls_time');?>)</label>
                                                <div class="input-group timepicker-input-group">
                                                    <input type="text" name="time" class="form-control timepicker"
                                                        data-provide="timepicker" data-default-time="" value="">
                                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="formrow-additional_field" class="form-label"><?php echo lang('polls_additional_field');?></label>
                                        <select id="formrow-additional_field" name="polls_additional_field" class="form-select">
                                            <option value="" selected><?php echo lang('polls_select');?></option>
                                            <?php
                                                if ( !empty( $xfields ) ) {
                                                    foreach ($xfields as $xfield) {
                                            ?>
                                                <option value="<?php echo $xfield['xfield'];?>"><?php echo $xfield['name'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <?php
                                            $dataParams = [
                                                'messages' => [
                                                    'default' => lang('dropify_default'),
                                                    'replace' => lang('dropify_replace'),
                                                    'remove' => lang('dropify_remove'),
                                                    'error' => lang('dropify_error'),
                                                ],
                                                'error' => [
                                                    'fileSize' => lang('dropify_fileSize'),
                                                    'minWidth' => lang('dropify_minWidth'),
                                                    'maxWidth' => lang('dropify_maxWidth'),
                                                    'minHeight' => lang('dropify_minHeight'),
                                                    'maxHeight' => lang('dropify_maxHeight'),
                                                    'imageFormat' => lang('dropify_imageFormat'),
                                                    'fileExtension' => lang('dropify_fileExtension')
                                                ]
                                            ];
                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <input type="file" name="file" class="dropify" title=" " data-show-remove="true" data-params="<?php echo $dataParams; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 repeater" data-sorting="true">
                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="<?php echo lang('polls_button_add');?>"/>
                                    <div data-repeater-list="buttons">
                                        <div data-repeater-item class="row" style="display:none;">
                                            <div  class="mb-3 col-lg-4">
                                                <label for="name"><?php echo lang('polls_button_text');?></label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('polls_button_enter_text');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-4">
                                                <label for="subject"><?php echo lang('polls_button_value');?></label>
                                                <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('polls_button_enter_value');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-2">
                                                <label for="email"><?php echo lang('polls_button_type');?></label>
                                                <select id="formrow-first_command" name="type" class="form-select">
                                                    <option value="" disabled selected hidden><?php echo lang('polls_select');?></option>
                                                    <option value="url"><?php echo lang('polls_button_type_url');?></option>
                                                    <option value="callback"><?php echo lang('polls_button_type_callback');?></option>
                                                    <option value="webapp"><?php echo lang('polls_button_type_webapp');?></option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-lg-2 align-self-center">
                                                <div class="d-grid">
                                                    <label for="subject"></label>
                                                    <input data-repeater-delete type="button" class="btn btn-primary" value="<?php echo lang('polls_button_remove');?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('polls_save');?></button>
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