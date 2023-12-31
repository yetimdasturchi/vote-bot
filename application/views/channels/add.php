<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('channels_add');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('channels');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('channels_add');?></li>
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
                                        <form class="ajax-form" method="POST" action="<?php echo base_url('channels/add');?>" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label"><?php echo lang('channels_name');?></label>
                                                        <input type="text" name="name" class="form-control" id="formrow-firstname-input" placeholder="<?php echo lang('channels_enter_name');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label"><?php echo lang('channel_chat_username');?></label>
                                                        <input type="text" name="username" class="form-control" id="formrow-firstname-input"  placeholder="<?php echo lang('channel_enter_chat_username');?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label"><?php echo lang('channel_chat_id');?></label>
                                                        <input type="number" name="chat_id" class="form-control" id="formrow-firstname-input"  placeholder="<?php echo lang('channels_enter_chat_id');?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputState" class="form-label"><?php echo lang('channel_subscription');?></label>
                                                        <select id="formrow-inputState" name="subscription" class="form-select">
                                                            <option value="0"><?php echo lang('channel_off');?></option>
                                                            <option value="1"><?php echo lang('channel_on');?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputState" class="form-label"><?php echo lang('channel_status');?></label>
                                                        <select id="formrow-inputState" name="status" class="form-select">
                                                            <option value="0"><?php echo lang('channel_inactive');?></option>
                                                            <option value="1"><?php echo lang('channel_active');?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('channel_save');?></button>
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