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
                        <a href="<?php echo base_url('users/view/'.$id.'/additional');?>" class="active"><i class="bx bx-id-card me-2"></i><?php echo lang('users_additional_informations');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/referrals');?>"><i class="bx bx-link-alt me-2"></i><?php echo lang('users_referrals');?></a>
                       <!--  <a href="<?php echo base_url('users/view/'.$id.'/inline');?>"><i class="bx bx-poll me-2"></i><?php echo lang('users_poll_inline');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/web');?>"><i class="bx bx-chart me-2"></i><?php echo lang('users_poll_web');?></a> -->
                    </div>
                </div>
                <!-- End Left sidebar -->

                <!-- Right Sidebar -->
                <div class="email-rightbar mb-3">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap mb-0">
                                    <tbody>
                                        <?php
                                            if ( !empty( $additionals ) ) {
                                                foreach ($additionals as $additional) {
                                                    if ( !empty( $additional['source'] ) ) {
                                                        $data_type = "select";
                                                        $data_source = htmlspecialchars( json_encode( $additional['source'] ), ENT_QUOTES, 'UTF-8');
                                                    }else{
                                                        $data_type = "text";
                                                        $data_source = "";
                                                    }
                                        ?>
                                        <tr>
                                            <th scope="row" width="50%"><?php echo $additional['name'];?></th>
                                            <td>
                                                <a href="#" class="editable editable-click edit-additiona-info" data-name="<?php echo $additional['field'];?>" data-prepend="<?php echo lang('users_choose');?>" data-type="<?php echo $data_type;?>" data-source="<?php echo $data_source;?>" data-pk="<?php echo $id;?>" data-url="<?php echo base_url('users/view/'.$id.'/additional');?>" data-title="<?php echo lang('user_enter_data');?>" data-empty="<?php echo lang('users_undefined');?>">
                                                    <?php echo $additional['value'];?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                                }
                                            }else{
                                        ?>
                                            <tr>
                                                <td class="text-center"><code><?php echo lang('users_no_data');?></code></td>
                                            </tr>
                                        <?php
                                            }
                                        ?>
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