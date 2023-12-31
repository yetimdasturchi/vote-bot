<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_edit');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('polls_edit');?></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/inline/edit/'.$id);?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <input type="text" name="name" class="form-control" id="formrow-name" placeholder="" value="<?php echo $name;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-question" class="form-label"><?php echo lang('polls_poll');?></label>
                                        <textarea type="number" name="question" class="form-control" id="formrow-question" placeholder="<?php echo lang('polls_enter_poll');?>" rows="3"><?php echo $question;?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1" <?php if( $status == '1' ) echo "selected";?>><?php echo lang('polls_active');?></option>
                                            <option value="0" <?php if( $status == '0' ) echo "selected";?>><?php echo lang('polls_inactive');?></option>
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
                                                        data-date-autoclose="true" data-date-language="<?php echo getDefaultLanguage();?>" value="<?php if( $expire > 0 ) echo date('d-m-Y', $expire);?>">

                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div><!-- input-group -->
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="formrow-language" class="form-label"><?php echo lang('polls_expire');?> (<?php echo lang('polls_time');?>)</label>
                                                <div class="input-group timepicker-input-group">
                                                    <input type="text" name="time" class="form-control timepicker"
                                                        data-provide="timepicker" data-default-time="" value="<?php if( $expire > 0 ) echo date('H:i', $expire);?>">
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
                                            <option value="" <?php if( $additional_field == '' ) echo "selected";?>><?php echo lang('polls_select');?></option>
                                            <?php
                                                if ( !empty( $xfields ) ) {
                                                    foreach ($xfields as $xfield) {
                                            ?>
                                                <option value="<?php echo $xfield['xfield'];?>" <?php if( $additional_field == $xfield['xfield'] ) echo "selected";?>><?php echo $xfield['name'];?></option>
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
                                <div class="col-md-12 repeater" data-sorting="true" data-init="<?php echo !empty( $buttons ) ? 'false' : 'true';?>">
                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="<?php echo lang('polls_button_add');?>"/>
                                    <div data-repeater-list="buttons">
                                        <?php
                                            if ( !empty( $buttons ) ) {
                                                $buttons = json_decode( $buttons, TRUE );
                                                foreach ($buttons as $item) {
                                                    $item['display'] = true;
                                                    $this->load->view('polls/inline/inline_buttons', $item);
                                                }
                                            }else{
                                                $this->load->view('polls/inline/inline_buttons');
                                            }
                                        ?>
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