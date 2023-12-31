<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('polls_add');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls_nominations');?></a></li>
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
                        <form class="ajax-form" method="POST" action="<?php echo base_url('polls/web/nominations/'.$id.'/add');?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-name" class="form-label"><?php echo lang('poll_name');?></label>
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" id="formrow-name" />
                                            <div class="input-group-text cursor-pointer" data-toggle=".name-translations"><i class="bx bx-globe"></i></div>
                                        </div>
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_status');?></label>
                                        <select id="formrow-status" name="status" class="form-select">
                                            <option value="1"><?php echo lang('polls_active');?></option>
                                            <option value="0"><?php echo lang('polls_inactive');?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-status" class="form-label"><?php echo lang('polls_max_votes');?></label>
                                        <input type="number" min="1" max="1000" name="max_votes" class="form-control" id="formrow-status" value="1" />
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