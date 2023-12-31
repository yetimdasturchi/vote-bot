<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_add');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls_memebers');?></a></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/web/members/'.$contest_id.'/'.$nomination_id.'/add');?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" id="formrow-name" />
                                            <div class="input-group-text cursor-pointer" data-toggle=".name-translations"><i class="bx bx-globe"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1"><?php echo lang('polls_active');?></option>
                                            <option value="0"><?php echo lang('polls_inactive');?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none name-translations">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_uzbek" class="form-label">O‘zbek</label>
                                        <input type="text" name="name_uzbek" class="form-control" id="formrow-name_uzbek" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_uzbek_cyr" class="form-label">Ўзбек</label>
                                        <input type="text" name="name_uzbek_cyr" class="form-control" id="formrow-name_uzbek_cyr" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_russian" class="form-label">Русский</label>
                                        <input type="text" name="name_russian" class="form-control" id="formrow-name_russian" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_english" class="form-label">English</label>
                                        <input type="text" name="name_english" class="form-control" id="formrow-name_english" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-description" class="form-label"><?php echo lang('polls_description');?></label>
                                        <div class="input-group">
                                            <textarea name="description" class="form-control" id="formrow-description" rows="3"></textarea>
                                            <div class="input-group-text cursor-pointer" data-toggle=".description-translations"><i class="bx bx-globe"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none description-translations">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_uzbek" class="form-label">O‘zbek</label>
                                        <textarea name="description_uzbek" class="form-control" id="formrow-description_uzbek" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_uzbek_cyr" class="form-label">Ўзбек</label>
                                        <textarea name="description_uzbek_cyr" class="form-control" id="formrow-description_uzbek_cyr" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_russian" class="form-label">Русский</label>
                                        <textarea name="description_russian" class="form-control" id="formrow-description_russian" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_english" class="form-label">English</label>
                                        <textarea name="description_english" class="form-control" id="formrow-description_english" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <?php
                                        $slect2Params = [
                                            'language' => getDefaultLanguage(),
                                            'ajax' => [
                                                'url' => base_url('polls/web/members/'.$contest_id.'/'.$nomination_id.'/getnominations'),
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
                                    <div class="mb-3">
                                        <label for="formrow-polls" class="form-label"><?php echo lang('polls_nominations');?></label>
                                        <select class="select2 form-control select2-multiple" name="nomination[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>"></select>
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
                                            <div  class="mb-3 col-lg-3">
                                                <label for="name"><?php echo lang('polls_button_text');?></label>
                                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('polls_button_enter_text');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-3">
                                                <label for="subject"><?php echo lang('polls_button_value');?></label>
                                                <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('polls_button_enter_value');?>"/>
                                            </div>

                                            <div  class="mb-3 col-lg-2">
                                                <label for="email"><?php echo lang('polls_button_type');?></label>
                                                <select id="formrow-first_command" name="type" class="form-select">
                                                    <option value="" disabled selected hidden><?php echo lang('polls_select');?></option>
                                                    <option value="url"><?php echo lang('polls_button_type_url');?></option>
                                                </select>
                                            </div>

                                             <div  class="mb-3 col-lg-2">
                                                <label for="email"><?php echo lang('polls_language');?></label>
                                                <select id="formrow-language" name="language" class="form-select">
                                                    <option value="" disabled selected hidden><?php echo lang('polls_select');?></option>
                                                    <option value="uzbek">O‘zbek</option>
                                                    <option value="uzbek_cyr">Ўзбек</option>
                                                    <option value="russian">Русский</option>
                                                    <option value="english">English</option>
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