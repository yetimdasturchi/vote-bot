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
                        <a href="<?php echo base_url('users/view/'.$id.'/referrals');?>" class="active"><i class="bx bx-link-alt me-2"></i><?php echo lang('users_referrals');?></a>
                        <!-- <a href="<?php echo base_url('users/view/'.$id.'/inline');?>"><i class="bx bx-poll me-2"></i><?php echo lang('users_poll_inline');?></a>
                        <a href="<?php echo base_url('users/view/'.$id.'/web');?>"><i class="bx bx-chart me-2"></i><?php echo lang('users_poll_web');?></a> -->
                    </div>
                </div>
                <!-- End Left sidebar -->

                <!-- Right Sidebar -->
                <div class="email-rightbar mb-3">

                    <div class="card">
                        <div class="card-body">
                            <?php 
                                $dataParams = [
                                    'processing' => true,
                                    'serverSide' => true,
                                    'searching' => false,
                                    'ordering' => false,
                                    'serverMethod' => 'post',
                                    'language' => [
                                        'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                    ],
                                    'ajax' => [
                                        'url' => base_url('users/referrals/getlist'),
                                        'cache' => false,
                                        'data' => 'function(data){data.owner_id = '.$chat_id.';}'
                                    ],
                                    'columns' => [
                                        ['title' => lang('users_id'), 'data' => 'id'],
                                        ['title' => lang('users_user'), 'data' => 'u_name'],
                                        ['title' => lang('users_time'), 'data' => 'date'],
                                    ],
                                    'columnDefs' => [
                                        ['className' => 'text-center', 'targets' => [0, 2]]
                                    ],
                                    'responsive' => true,
                                    'buttonsDom' => 'Bfrtip',
                                    'dom' => 'Bfrtip',
                                    'lengthMenu' => [
                                        [10, 25, 50, -1],
                                        [lang('users_records_10'), lang('users_records_25'), lang('users_records_50'), lang('users_records_all')]
                                    ],
                                    'buttons' => []
                                ];

                                $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                            ?>
                            <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="referrals" data-params="<?php echo $dataParams; ?>"></table>

                            <?php
                                unset($dataParams);
                            ?>
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