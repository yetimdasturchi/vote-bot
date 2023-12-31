<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('users_short_archive');?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('users_users');?></a></li>
                                            <li class="breadcrumb-item active"><?php echo lang('users_short_archive');?></li>
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
                                        <?php 
                                            $dataParams = [
                                                'processing' => true,
                                                'serverSide' => true,
                                                'serverMethod' => 'post',
                                                'language' => [
                                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                                ],
                                                'ajax' => [
                                                    'url' => base_url('users/archive/getlist'),
                                                    'cache' => false,
                                                    'data' => 'function(data){data.filter_category = $("[name=\'filter_category[]\']").val()}'
                                                ],
                                                'columns' => [
                                                    ['title' => lang('users_id'), 'data' => 'users_archive_users_id'],
                                                    ['title' => lang('users_category'), 'data' => 'users_archive_users_category'],
                                                    //['title' => 'Ism', 'data' => 'users_archive_users_name'],
                                                    ['title' => lang('users_telegram'), 'data' => 'users_archive_users_telegram'],
                                                    ['title' => lang('users_phone'), 'data' => 'users_archive_users_phone'],
                                                ],
                                                'columnDefs' => [
                                                    ['className' => 'text-center', 'targets' => [0, 1, 2, 3]]
                                                ],
                                                'order' => [
                                                    [0, "asc"]
                                                ],
                                                'responsive' => true,
                                                'buttonsDom' => 'Bfrtip',
                                                'dom' => 'Bfrtip',
                                                "lengthMenu" => [[10, 25, 50, -1], [lang('users_records_10'), lang('users_records_25'), lang('users_records_50'), lang('users_all')]],
                                                'buttons' => [
                                                    [
                                                        'text' => '<i class="fa fa-list-ol"></i> ' . lang('users_records'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'pageLength'
                                                    ],
                                                    [
                                                        'text' => '<i class="fa fa-list"></i> ' . lang('users_columns'),
                                                        'className' => 'btn-inverse',
                                                        'extend' => 'colvis'
                                                    ],
                                                    [
                                                        'text' => '<i class="fas fa-filter"></i> ' .  lang('users_categories'),
                                                        'className' => 'btn-info',
                                                        'action' => 'function(e,dt,node,config){$(".bs-filter-modal").modal("show")}'
                                                    ]
                                                ]
                                            ];

                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="users_archive" data-params="<?php echo $dataParams; ?>"></table>

                                        <?php
                                            unset($dataParams);
                                        ?>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <div class="modal fade bs-filter-modal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="overflow:hidden;">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mySmallModalLabel"><?php echo lang('users_categories');?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <select class="select2 form-control select2-multiple" name="filter_category[]" multiple="multiple" data-placeholder="<?php echo lang('users_choose');?>" data-params="<?php echo htmlspecialchars(  json_encode(['language' => getDefaultLanguage()]), ENT_QUOTES, 'UTF-8' ); ?>">
                                            <?php
                                                $categories = $this->archive->getCategories();
                                                if ( $categories->num_rows() > 0 ) {
                                                    foreach ($categories->result_array() as $category) {
                                            ?>
                                                <option value="<?php echo $category['id'];?>"><?php echo $category['name'];?></option>
                                            <?php
                                                    }

                                                    unset($category);
                                                }
                                                unset($categories);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light bs-filter-button" data-table="users_archive"><?php echo lang('users_filter');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
