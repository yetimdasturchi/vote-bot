<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('notifications_add');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('notifications');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('notifications_add');?></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('notifications/add');?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-language" class="form-label"><?php echo lang('notifications_to');?></label>
                                        <select id="formrow-language" name="to" class="form-select">
                                            <option value="users"><?php echo lang('notifications_users');?></option>
                                            <optgroup label="<?php echo lang('notifications_archive');?>">
                                                <?php
                                                $categories = $this->archive->getCategories();
                                                if ( $categories->num_rows() > 0 ) {
                                                    foreach ($categories->result_array() as $category) {
                                                        echo "<option value=\"archive_{$category['id']}\">{$category['name']}</option>";
                                                    }
                                                }
                                                ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-language" class="form-label"><?php echo lang('notifications_language');?></label>
                                        <select id="formrow-language" name="language" class="form-select">
                                            <option value=""><?php echo lang('notifications_select');?></option>
                                            <?php
                                                $languages = getLanguages(TRUE);
                                                foreach ($languages as $k => $v) {
                                            ?>
                                            <option value="<?php echo $k;?>"><?php echo $v['name'];?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-phone" class="form-label"><?php echo lang('notifications_phone');?></label>
                                        <select id="formrow-phone" name="phone" class="form-select">
                                            <option value=""><?php echo lang('notifications_select');?></option>
                                            <option value="not_empty"><?php echo lang('notifications_phone_not_empty');?></option>
                                            <option value="is_empty"><?php echo lang('notifications_phone_is_empty');?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $slect2Params = [
                                    'language' => getDefaultLanguage(),
                                    'ajax' => [
                                        'url' => base_url('notifications/add/get_users'),
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="formrow-polls" class="form-label"><?php echo lang('notifications_selected_users');?></label>
                                        <select class="select2 form-control select2-multiple" name="selected_users[]" multiple="multiple" data-placeholder="<?php echo lang('notifications_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-message" class="form-label"><?php echo lang('notifications_message');?></label>
                                        <textarea type="number" name="message" class="form-control" id="formrow-message" placeholder="<?php echo lang('polls_poll');?>" rows="5"></textarea>
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
                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="<?php echo lang('notifications_button_add');?>"/>
                                    <div data-repeater-list="buttons">
                                        <div data-repeater-item class="row" style="display:none;">
                                            <div  class="mb-3 col-lg-4">
                                                <label for="name"><?php echo lang('notifications_button_text');?></label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('notifications_button_enter_text');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-4">
                                                <label for="subject"><?php echo lang('notifications_button_value');?></label>
                                                <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('notifications_button_enter_value');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-2">
                                                <label for="email"><?php echo lang('notifications_button_type');?></label>
                                                <select id="formrow-first_command" name="type" class="form-select">
                                                    <option value="" disabled selected hidden><?php echo lang('notifications_select');?></option>
                                                    <option value="url"><?php echo lang('notifications_button_type_url');?></option>
                                                    <option value="callback"><?php echo lang('notifications_button_type_callback');?></option>
                                                    <option value="webapp"><?php echo lang('notifications_button_type_webapp');?></option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-lg-2 align-self-center">
                                                <div class="d-grid">
                                                    <label for="subject"></label>
                                                    <input data-repeater-delete type="button" class="btn btn-primary" value="<?php echo lang('notifications_button_remove');?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('notifications_add');?></button>
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