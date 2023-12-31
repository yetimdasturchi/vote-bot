<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Jarayon</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Bildirishnomalar</a></li>
                                            <li class="breadcrumb-item active">Jarayon</li>
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
                                                <div class="row question-filter notification-button">
                                                    <div class="col-12 col-md-9">
                                                        <div class="row">
                                                            <div class="col-12 col-md-3">
                                                                <?php
                                                                    $status = file_exists( APPPATH . 'config/notifications.php' ) ? include( APPPATH . 'config/notifications.php' ) : FALSE;
                                                                ?>
                                                                <button type="button" 
                                                                data-ajax-button="<?php echo base_url('client/notifications/records/' . ( $status ? 'pause' : 'play' ) );?>" 
                                                                data-message="<?php echo ( $status ? "Siz chindan ham bildirishnoma yuborishni to'xtatmoqchimisiz?" : "Siz chindan ham bildirishnoma yuborishni yoqishni istaysizmi?" );?>" 
                                                                class="btn btn-outline-<?php echo ( $status ? 'warning' : 'success' );?> waves-effect waves-light action-button"><?php echo ( $status ? "Yuborishni to'xtatish" : 'Yuborishni boshlash' );?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-3 float-right">
                                                        <button type="button" 
                                                        class="btn btn-outline-danger waves-effect waves-light action-button" 
                                                        data-ajax-button="<?php echo base_url('client/notifications/records/clear');?>" 
                                                        data-message="Siz chindan ham jarayondagi xabarlarni tozalamoqchimisiz?"
                                                        >Xabarlarni tozalash</button>
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
                                                            'url' => base_url('client/notifications/records/getlist'),
                                                            'cache' => false
                                                        ],
                                                        'columns' => [
                                                            ['title' => "ID", 'data' => 'id'],
                                                            ['title' => "Foydalanuvchi", 'data' => 'u_name'],
                                                            ['title' => "Vaqt", 'data' => 'date'],
                                                            ['title' => "Andoza", 'data' => 'template_name'],

                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 2, 3]],
                                                        ],
                                                        'responsive' => true,
                                                        'buttonsDom' => 'Bfrtip',
                                                        'dom' => 'ltrip',
                                                        'bLengthChange' => false,
                                                        'bFilter' => true,
                                                        'bInfo' => true,
                                                        'ordering' => false,
                                                        'fnDrawCallback' => "function(){\$('[data-bs-toggle=\"tooltip\"]').tooltip();}",
                                                        'initCompleted' => 'function (settings, json){(function delay() {$dtables[\'client_nitifications_records\'].ajax.reload(null, false);setTimeout(delay, 5000);})();}'
                                                    ];

                                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                                ?>
                                                <table class="table" data-datatable="client_nitifications_records" data-params="<?php echo $dataParams; ?>"></table>

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