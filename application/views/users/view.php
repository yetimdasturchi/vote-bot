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
                        <a href="<?php echo base_url('users/view/'.$id);?>" class="active"><i class="bx bx-user-pin me-2"></i> <?php echo lang('users_profile');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/additional');?>"><i class="bx bx-id-card me-2"></i><?php echo lang('users_additional_informations');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/referrals');?>"><i class="bx bx-link-alt me-2"></i><?php echo lang('users_referrals');?></a>
                        <!-- <a href="<?php echo base_url('users/view/'.$id.'/inline');?>"><i class="bx bx-poll me-2"></i><?php echo lang('users_poll_inline');?></a>
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
                                            <th scope="row"><?php echo lang('users_id');?></th>
                                            <td><?php echo $id?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_chat_id');?></th>
                                            <td><?php echo $chat_id?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_username');?></th>
                                            <td><?php echo empty( $username ) ? "<code>".lang('users_undefined')."</code>" : '@'.$username;?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_first_name');?></th>
                                            <td><?php echo empty( $first_name ) ? "<code>".lang('users_undefined')."</code>" : $first_name;?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_last_name');?></th>
                                            <td><?php echo empty( $last_name ) ? "<code>".lang('users_undefined')."</code>" : $last_name;?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_registered');?></th>
                                            <td><?php echo date($GLOBALS['system_config']['dateformat'], $registered);?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_last_action');?></th>
                                            <td><?php echo date($GLOBALS['system_config']['dateformat'], $last_action)?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_language');?></th>
                                            <td>
                                                <?php
                                                    if ( !empty( $language ) ) {
                                                        $language_data = getLanguagedata( $language );
                                                        echo $language_data ? $language_data['name'] : "<code>".lang('users_undefined')."</code>";
                                                    }else{
                                                        echo "<code>".lang('users_undefined')."</code>";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_phone');?></th>
                                            <td><?php echo empty( $phone ) ? "<code>".lang('users_undefined')."</code>" : format_phone($phone);?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php echo lang('users_referrals');?></th>
                                            <td><?php echo $referrals;?></td>
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