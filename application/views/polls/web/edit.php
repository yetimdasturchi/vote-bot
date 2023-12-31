<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_edit');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls_contests');?></a></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/web/edit/'.$id);?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" id="formrow-name" value="<?php echo $name;?>" />
                                            <div class="input-group-text cursor-pointer" data-toggle=".name-translations"><i class="bx bx-globe"></i></div>
                                        </div>
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
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1" <?php if( $status == '1' ) echo 'selected';?>><?php echo lang('polls_active');?></option>
                                            <option value="0" <?php if( $status == '0' ) echo 'selected';?>><?php echo lang('polls_inactive');?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="formrow-max_votes" class="form-label"><?php echo lang('polls_max_votes');?></label>
                                                <input type="number" min="1" max="1000" name="max_votes" class="form-control" id="formrow-max_votes" value="<?php echo $max_votes;?>" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="formrow-polls_check" class="form-label"><?php echo lang('polls_check_votes');?></label>
                                                <input type="number" min="1" max="100" name="polls_check" class="form-control" id="formrow-polls_check" value="<?php echo $polls_check;?>" />
                                            </div>
                                        </div>
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
                            </div>
                            <?php
                                $slect2Params = [
                                    'language' => getDefaultLanguage(),
                                    'ajax' => [
                                        'url' => base_url('polls/web/add/get_polls'),
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
                                        <label for="formrow-polls" class="form-label"><?php echo lang('polls_select_inline');?></label>
                                        <div class="input-group">
                                            <select class="select2 form-control select2-multiple" name="polls[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                                <?php
                                                    if ( !empty( $selected_polls ) ) {
                                                        foreach ($selected_polls as $poll) {
                                                ?>
                                                        <option value="<?php echo $poll['id'];?>" selected="selected"><?php echo $poll['text'];?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="input-group-text cursor-pointer" data-toggle=".polls-translations"><i class="bx bx-globe"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none polls-translations d-none-select2">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-polls_uzbek" class="form-label">O‘zbek</label>
                                        <select class="select2 form-control select2-multiple" name="polls_uzbek[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                if ( !empty( $selected_polls_uzbek ) ) {
                                                    foreach ($selected_polls_uzbek as $poll_uzbek) {
                                            ?>
                                                    <option value="<?php echo $poll_uzbek['id'];?>" selected="selected"><?php echo $poll_uzbek['text'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-polls_uzbek_cyr" class="form-label">Ўзбек</label>
                                        <select class="select2 form-control select2-multiple" name="polls_uzbek_cyr[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                if ( !empty( $selected_polls_uzbek_cyr ) ) {
                                                    foreach ($selected_polls_uzbek_cyr as $poll_uzbek_cyr) {
                                            ?>
                                                    <option value="<?php echo $poll_uzbek_cyr['id'];?>" selected="selected"><?php echo $poll_uzbek_cyr['text'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-polls_russian" class="form-label">Русский</label>
                                        <select class="select2 form-control select2-multiple" name="polls_russian[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                if ( !empty( $selected_polls_russian ) ) {
                                                    foreach ($selected_polls_russian as $poll_russian) {
                                            ?>
                                                    <option value="<?php echo $poll_russian['id'];?>" selected="selected"><?php echo $poll_russian['text'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="formrow-polls_english" class="form-label">English</label>
                                        <select class="select2 form-control select2-multiple" name="polls_english[]" multiple="multiple" data-placeholder="<?php echo lang('polls_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                if ( !empty( $selected_polls_english ) ) {
                                                    foreach ($selected_polls_english as $poll_english) {
                                            ?>
                                                    <option value="<?php echo $poll_english['id'];?>" selected="selected"><?php echo $poll_english['text'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
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