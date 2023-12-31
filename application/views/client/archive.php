<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Arxiv</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Foydalanuvchilar</a></li>
                                            <li class="breadcrumb-item active">Arxiv</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card client-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 m-2">
                                                <div class="row question-filter">
                                                    <div class="col-12 col-md-7">
                                                        <div class="row">
                                                            <div class="col-12 col-md-3">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Arxiv</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                                                                        <?php
                                                                            $users_archive_categories = $this->db->get('users_archive_categories');

                                                                            if ( $users_archive_categories->num_rows() > 0 ) {
                                                                                foreach ($users_archive_categories->result_array() as $category) {
                                                                        ?>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_uarchive" data-reload-table="client_archive" type="checkbox" value="<?php echo $category['users_archive_category_id'];?>" id="cat_<?php echo $category['users_archive_category_id'];?>" />
                                                                                <label class="form-check-label" for="cat_<?php echo $category['users_archive_category_id'];?>"><?php echo $category['users_archive_category_name'];?></label>
                                                                            </div>
                                                                        </a>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5 float-right">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="input-group searchbox">
                                                                    <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Qidirish..." data-table-search="client_archive">
                                                                    <div class="input-group-text"><i class="bx bx-search-alt-2"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <?php 
                                                    $dataParams = [
                                                        'processing' => true,
                                                        'serverSide' => true,
                                                        'serverMethod' => 'post',
                                                        "autoWidth" => false,
                                                        'language' => [
                                                            'url' => base_url('assets/json/datatable-uzbek.json')
                                                        ],
                                                        'ajax' => [
                                                            'url' => base_url('client/archive/getlist'),
                                                            'cache' => false,
                                                            'data' => 'function(data){
                                                                var uarchive = [];
                                                                $(".question-filter .filtered_uarchive:checked").each(function(i, e) {
                                                                  uarchive.push($(this).val());
                                                                });
                                                                data.uarchive = uarchive.join("|");
                                                            }'
                                                        ],
                                                        'columns' => [
                                                            ['title' => "ID", 'data' => 'users_archive_users_id'],
                                                            ['title' => "Arxiv", 'data' => 'users_archive_category_name'],
                                                            ['title' => "Telefon raqam", 'data' => 'users_archive_users_phone'],
                                                            ['title' => "Telegram ID", 'data' => 'users_archive_users_telegram']
                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 1, 2, 3]],
                                                        ],
                                                        'responsive' => true,
                                                        'buttonsDom' => 'Bfrtip',
                                                        'dom' => 'ltrip',
                                                        'bLengthChange' => false,
                                                        'bFilter' => true,
                                                        'bInfo' => true,
                                                        'ordering' => false
                                                    ];

                                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                                ?>
                                                <table class="table" data-datatable="client_archive" data-params="<?php echo $dataParams; ?>"></table>

                                                <?php
                                                    unset($dataParams);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->