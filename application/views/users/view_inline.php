<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('users_view');?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('users_users');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('users_view');?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <!-- Left sidebar -->
                <div class="email-leftbar card">
                    <div class="mail-list">
                        <a href="<?php echo base_url('users/view/'.$id);?>"><i class="bx bx-user-pin me-2"></i> <?php echo lang('users_profile');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/additional');?>"><i class="bx bx-id-card me-2"></i><?php echo lang('users_additional_informations');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/referrals');?>"><i class="bx bx-link-alt me-2"></i><?php echo lang('users_referrals');?></a>
                        <!-- <a href="<?php echo base_url('users/view/'.$id.'/inline');?>"  class="active"><i class="bx bx-poll me-2"></i><?php echo lang('users_poll_inline');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/web');?>"><i class="bx bx-chart me-2"></i><?php echo lang('users_poll_web');?></a> -->
                    </div>
                </div>
                <!-- End Left sidebar -->

                <!-- Right Sidebar -->
                <div class="email-rightbar mb-3">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                                <td class="text-center"><code><?php echo lang('users_no_data');?></code></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card -->

            </div>
            <!-- end Col-9 -->

        </div>

    </div>
    <!-- End row -->
</div>
<!-- container-fluid -->