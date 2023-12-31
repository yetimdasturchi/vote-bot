<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('command_add');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('commands');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('command_add');?></li>
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
                                        <form class="ajax-form" method="POST" action="<?php echo base_url('bot/commands/add');?>" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-command" class="form-label"><?php echo lang('command_command');?></label>
                                                        <input type="text" name="name" class="form-control" id="formrow-command" placeholder="<?php echo lang('commands_enter_command');?>">
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
                                                                    'allowClear' => true,
                                                                    'delay' => 250,
                                                                    'cache' => true,
                                                                    'data' => 'function(params){ console.log(params); return { search: params.term, page: params.page || 1 }; }',
                                                                    'processResults' => 'function (response) {return {results: response}}'
                                                                ]
                                                            ];
                                                        ?>
                                                        <select class="select2 form-control select2-multiple" name="parent" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
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
                                                                $language = $this->config->item('language');
                                                                foreach ($modules_map as $k => $v) {
                                                                    if ( !file_exists( APPPATH . 'modules/' . $v . 'models/Hook_model.php' ) ) {
                                                                        continue;
                                                                    }
                                                                    $module_config = $this->module->load_config( $v );
                                                            ?>
                                                            <option value="<?php echo trim($v, '/');?>"><?php echo $module_config['name'][$language];?></option>
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
                                                            <option value="<?php echo $k;?>"><?php echo $v['name'];?></option>
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
                                                            <option value="0"><?php echo lang('command_no');?></option>
                                                            <option value="1"><?php echo lang('command_yes');?></option>
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
                                                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="formrow-sort" class="form-label"><?php echo lang('command_sort');?></label>
                                                        <input type="number" name="sort" class="form-control" id="formrow-sort" placeholder="<?php echo lang('commands_enter_sort');?>" min="1" value="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="formrow-message" class="form-label"><?php echo lang('command_message');?></label>
                                                        <textarea type="number" name="message" class="form-control" id="formrow-message" placeholder="<?php echo lang('commands_enter_message');?>" min="0" value="0"></textarea>
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
                                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="<?php echo lang('commands_button_add');?>"/>
                                                    <div data-repeater-list="buttons">
                                                        <div data-repeater-item class="row" style="display:none;">
                                                            <div  class="mb-3 col-lg-4">
                                                                <label for="name"><?php echo lang('commands_button_text');?></label>
                                                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('commands_button_enter_text');?>"/>
                                                            </div>
                
                                                            <div  class="mb-3 col-lg-4">
                                                                <label for="subject"><?php echo lang('commands_button_value');?></label>
                                                                <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('commands_button_enter_value');?>"/>
                                                            </div>

                                                            <div  class="mb-3 col-lg-2">
                                                                <label for="email"><?php echo lang('commands_button_type');?></label>
                                                                <select id="formrow-first_command" name="type" class="form-select">
                                                                    <option value="" disabled selected hidden><?php echo lang('command_select');?></option>
                                                                    <option value="url"><?php echo lang('commands_button_type_url');?></option>
                                                                    <option value="callback"><?php echo lang('commands_button_type_callback');?></option>
                                                                    <option value="webapp"><?php echo lang('commands_button_type_webapp');?></option>
                                                                    <option value="switch_inline_query"><?php echo lang('commands_button_type_switch_inline_query');?></option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-lg-2 align-self-center">
                                                                <div class="d-grid">
                                                                    <label for="subject"></label>
                                                                    <input data-repeater-delete type="button" class="btn btn-primary" value="<?php echo lang('commands_button_remove');?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
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