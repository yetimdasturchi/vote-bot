<?php
$nominations = explode(',', $nomination);
if ( empty( $nominations ) ) {
    $nominations = [ 0 ];
}

                                        

?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_edit');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls_memebers');?></a></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/web/members/'.$contest.'/'.$nominations[0].'/edit/'.$id);?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" id="formrow-name" value="<?php echo $name;?>" />
                                            <div class="input-group-text cursor-pointer" data-toggle=".name-translations"><i class="bx bx-globe"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1" <?php if( $status == '1' ) echo 'selected';?>><?php echo lang('polls_active');?></option>
                                            <option value="0" <?php if( $status == '0' ) echo 'selected';?>><?php echo lang('polls_inactive');?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none name-translations">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_uzbek" class="form-label">O‘zbek</label>
                                        <input type="text" name="name_uzbek" class="form-control" id="formrow-name_uzbek" value="<?php echo $name_uzbek;?>" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_uzbek_cyr" class="form-label">Ўзбек</label>
                                        <input type="text" name="name_uzbek_cyr" class="form-control" id="formrow-name_uzbek_cyr" value="<?php echo $name_uzbek_cyr;?>" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_russian" class="form-label">Русский</label>
                                        <input type="text" name="name_russian" class="form-control" id="formrow-name_russian" value="<?php echo $name_russian;?>" />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-name_english" class="form-label">English</label>
                                        <input type="text" name="name_english" class="form-control" id="formrow-name_english" value="<?php echo $name_english;?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-description" class="form-label"><?php echo lang('polls_description');?></label>
                                        <div class="input-group">
                                            <textarea name="description" class="form-control" id="formrow-description" rows="3"><?php echo $description;?></textarea>
                                            <div class="input-group-text cursor-pointer" data-toggle=".description-translations"><i class="bx bx-globe"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none description-translations">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_uzbek" class="form-label">O‘zbek</label>
                                        <textarea name="description_uzbek" class="form-control" id="formrow-description_uzbek" rows="3"><?php echo $description_uzbek;?></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_uzbek_cyr" class="form-label">Ўзбек</label>
                                        <textarea name="description_uzbek_cyr" class="form-control" id="formrow-description_uzbek_cyr" rows="3"><?php echo $description_uzbek_cyr;?></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_russian" class="form-label">Русский</label>
                                        <textarea name="description_russian" class="form-control" id="formrow-description_russian" rows="3"><?php echo $description_russian;?></textarea>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-description_english" class="form-label">English</label>
                                        <textarea name="description_english" class="form-control" id="formrow-description_english" rows="3"><?php echo $description_english;?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <?php
                                        
                                        $slect2Params = [
                                            'language' => getDefaultLanguage(),
                                            'ajax' => [
                                                'url' => base_url('polls/web/members/'.$contest.'/'.$nominations[0].'/getnominations'),
                                                'tags' => true,
                                                'type' => 'get',
                                                'multiple' => true,
                                                'dataType' => 'json',
                                                'allowClear' => true,
                                                'delay' => 250,
                                                'cache' => true,
                                                'data' => 'function(params){return { search: params.term, page: params.page || 1, contest: '.$contest.' }; }',
                                                'processResults' => 'function (response) {return {results: response}}'
                                            ]
                                        ];
                                    ?>
                                    <div class="mb-3">
                                        <label for="formrow-polls" class="form-label"><?php echo lang('polls_nominations');?></label>
                                        <select class="select2 form-control select2-multiple" name="nomination[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                if ( !empty( $selected_nominations ) ) {
                                                    foreach ($selected_nominations as $selected_nomination) {
                                            ?>
                                                    <option value="<?php echo $selected_nomination['id'];?>" selected="selected"><?php echo $selected_nomination['text'];?></option>
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
                                                    $this->load->view('polls/web/inline_buttons', $item);
                                                }
                                            }else{
                                                $this->load->view('polls/web/inline_buttons');
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