<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('command_edit');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('commands');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('command_edit');?></li>
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
                                        <form class="ajax-form" method="POST" action="<?php echo base_url('bot/commands/edit/' . $command_id );?>" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-command" class="form-label"><?php echo lang('command_command');?></label>
                                                        <input type="text" name="name" class="form-control" id="formrow-command" placeholder="<?php echo lang('commands_enter_command');?>" value="<?php echo $command_set;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-parent" class="form-label"><?php echo lang('command_parent');?></label>
                                                        <?php
                                                            $slect2Params = [
                                                                'language' => getDefaultLanguage(),
                                                                'ajax' => [
                                                                    'url' => base_url('bot/commands/parent'),
                                                                    'type' => 'get',
                                                                    'dataType' => 'json',
                                                                    'delay' => 250,
                                                                    'cache' => true,
                                                                    'data' => 'function(params){ console.log(params); return { search: params.term, page: params.page || 1 }; }',
                                                                    'processResults' => 'function (response) {return {results: response}}'
                                                                ]
                                                            ];
                                                        ?>
                                                        <select class="select2 form-control select2-multiple" name="parent" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                                            <option value="<?php echo $prent_command;?>" selected=”selected”><?php echo $parent_name;?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-function" class="form-label"><?php echo lang('command_function');?></label>
                                                        <select id="formrow-function" name="function" class="form-select">
                                                            <option value="0"><?php echo lang('command_select');?></option>
                                                            <?php
                                                                $modules_map = directory_map( APPPATH . 'modules', 1);
                                                            if (($key = array_search('index.html', $modules_map)) !== FALSE) unset($modules_map[$key]);

                                                            if ( !empty( $modules_map ) ) {
                                                                $language_m = $this->config->item('language');
                                                                foreach ($modules_map as $k => $v) {
                                                                    if ( !file_exists( APPPATH . 'modules/' . $v . 'models/Hook_model.php' ) ) {
                                                                        continue;
                                                                    }
                                                                    $module_config = $this->module->load_config( $v );
                                                            ?>
                                                            <option value="<?php echo trim($v, '/');?>" <?php if($function == trim($v, '/')){echo "selected";};?>><?php echo $module_config['name'][$language_m];?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="formrow-language" class="form-label"><?php echo lang('command_language');?></label>
                                                        <select id="formrow-language" name="language" class="form-select">
                                                            <?php
                                                                $languages = getLanguages(TRUE);
                                                                foreach ($languages as $k => $v) {
                                                            ?>
                                                            <option value="<?php echo $k;?>" <?php if($language == $k){echo "selected";};?>><?php echo $v['name'];?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="formrow-first_command" class="form-label"><?php echo lang('command_first');?></label>
                                                        <select id="formrow-first_command" name="first_command" class="form-select">
                                                            <option value="0" <?php if($first_command == '0'){echo "selected";};?>><?php echo lang('command_no');?></option>
                                                            <option value="1" <?php if($first_command == '1'){echo "selected";};?>><?php echo lang('command_yes');?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="formrow-chunk" class="form-label"><?php echo lang('command_chunk');?></label>
                                                        <select id="formrow-chunk" name="chunk" class="form-select">
                                                            <?php
                                                                for ($i=1; $i <= 6; $i++) { 
                                                            ?>
                                                                <option value="<?php echo $i;?>" <?php if($chunk == $i){echo "selected";};?>><?php echo $i;?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="formrow-sort" class="form-label"><?php echo lang('command_sort');?></label>
                                                        <input type="number" name="sort" class="form-control" id="formrow-sort" placeholder="<?php echo lang('commands_enter_sort');?>" min="1" value="<?php echo $sort;?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="formrow-message" class="form-label"><?php echo lang('command_message');?></label>
                                                        <textarea type="number" name="message" class="form-control" id="formrow-message" placeholder="<?php echo lang('commands_enter_message');?>" min="0" value="0"><?php echo $command_message;?></textarea>
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
                                                <div class="col-md-12 repeater" data-sorting="true" data-init="<?php echo !empty( $inline_keyboard ) ? 'false' : 'true';?>">
                                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="<?php echo lang('commands_button_add');?>"/>
                                                    <div data-repeater-list="buttons">
                                                        <?php
                                                            if ( !empty( $inline_keyboard ) ) {
                                                                $inline_keyboard = json_decode( $inline_keyboard, TRUE );
                                                                foreach ($inline_keyboard as $item) {
                                                                    $item['display'] = true;
                                                                    $this->load->view('bot/inline_buttons', $item);
                                                                }
                                                            }else{
                                                                $this->load->view('bot/inline_buttons');
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('command_save');?></button>
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