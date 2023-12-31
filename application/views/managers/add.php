<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('manager_add');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('managers_managers');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('manager_add');?></li>
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
                                        <form class="ajax-form" method="POST" action="<?php echo base_url('managers/add');?>" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label"><?php echo lang('manager_name');?></label>
                                                        <input type="text" name="name" class="form-control" id="formrow-firstname-input" placeholder="<?php echo lang('manager_enter_name');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label"><?php echo lang('manager_telegram');?></label>
                                                        <input type="number" name="telegram" class="form-control" id="formrow-firstname-input" min="0"  placeholder="<?php echo lang('manager_enter_telegram_id');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputState" class="form-label"><?php echo lang('manager_status');?></label>
                                                        <select id="formrow-inputState" name="status" class="form-select">
                                                            <option value="0"><?php echo lang('manager_inactive');?></option>
                                                            <option value="1"><?php echo lang('manager_active');?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                foreach (config_item('managers_permissions') as $k => $v) {
                                            ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="col-12">
                                                            <h5 class="font-size-14 mb-1"><?php echo lang('managers_permissions_'.$k);?>:</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <?php
                                                                    foreach ($v as $kk => $vv) {
                                                                ?>
                                                                <div class="col-3">
                                                                    <div class="form-check form-check-right mb-1">
                                                                        <input class="form-check-input" name="modules[<?php echo $k;?>][<?php echo $kk;?>]" type="checkbox" id="manager-chk-<?php echo $k.'-'.$kk;?>">
                                                                        <label class="form-check-label text-muted" for="manager-chk-<?php echo $k.'-'.$kk;?>">
                                                                            <?php echo lang('managers_permissions_'.$kk);?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                }
                                            ?>
                                            <div class="d-grid gap-2 mt-3">
                                                <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('manager_save');?></button>
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