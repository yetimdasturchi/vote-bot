<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Referallar</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Foydalanuvchilar</a></li>
                                            <li class="breadcrumb-item active">Referallar</li>
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
                                                            'url' => base_url('client/referrals/getlist'),
                                                            'cache' => false
                                                        ],
                                                        'columns' => [
                                                            ['title' => "Foydalanuvchi", 'data' => 'o_name'],
                                                            ['title' => "Telefon raqam", 'data' => 'o_phone'],
                                                            ['title' => "Referallar", 'data' => 'count'],
                                                            ['title' => "Ovozlar", 'data' => 'votes'],
                                                            ['title' => "Harakat", 'data' => 'action']
                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [1, 2, 3, 4]],
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