<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Foydalanuvchilar</h4>
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
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Til</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="languageDropdown">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_language" data-reload-table="client_users" type="checkbox" value="uzbek" id="langUzbek" />
                                                                                <label class="form-check-label" for="langUzbek">O'zbek</label>
                                                                            </div>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_language" data-reload-table="client_users" type="checkbox" value="russian" id="langRussian" />
                                                                                <label class="form-check-label" for="langRussian">Rus</label>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="fieldsDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Hudud</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="fieldsDropdown">
                                                                        <div class="scroll-list">
                                                                            <?php
                                                                                if ( !empty( $ct ) ) {
                                                                                    foreach ($ct as $k => $v) {
                                                                            ?>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input filtered_ucity" data-reload-table="client_users" type="checkbox" value="<?php echo $k;?>" id="city_<?php echo $k;?>" />
                                                                                    <label class="form-check-label" for="city_<?php echo $k;?>"><?php echo $v['0'];?></label>
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
                                                            <div class="col-12 col-md-3">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="fieldsDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Jins</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="fieldsDropdown">
                                                                        <?php
                                                                            if ( !empty( $gender ) ) {
                                                                                foreach ($gender as $k => $v) {
                                                                        ?>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_ugender" data-reload-table="client_users" type="checkbox" value="<?php echo $k;?>" id="gender_<?php echo $k;?>" />
                                                                                <label class="form-check-label" for="gender_<?php echo $k;?>"><?php echo remove_emojis( $v['0'] );?></label>
                                                                            </div>
                                                                        </a>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <button type="button" class="btn btn-outline-success waves-effect waves-light action-button client-export-users">Eksport qilish</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="input-group searchbox">
                                                                    <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Qidirish..." data-table-search="client_users">
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
                                                            'url' => base_url('client/users/getlist'),
                                                            'cache' => false,
                                                            'data' => 'function(data){
                                                                var ulanguage = [];
                                                                $(".question-filter .filtered_language:checked").each(function(i, e) {
                                                                  ulanguage.push($(this).val());
                                                                });
                                                                data.ulanguage = ulanguage.join("|");

                                                                var ucity = [];
                                                                $(".question-filter .filtered_ucity:checked").each(function(i, e) {
                                                                  ucity.push($(this).val());
                                                                });
                                                                data.ucity = ucity.join("|");

                                                                var ugender = [];
                                                                $(".question-filter .filtered_ugender:checked").each(function(i, e) {
                                                                  ugender.push($(this).val());
                                                                });
                                                                data.ugender = ugender.join("|");
                                                            }'
                                                        ],
                                                        'columns' => [
                                                            ['title' => "ID", 'data' => 'id'],
                                                            ['title' => "Chat ID", 'data' => 'chat_id'],
                                                            ['title' => "Nomi", 'data' => 'username'],
                                                            ['title' => "Ro'yxatdan o'tgan", 'data' => 'registered'],
                                                            ['title' => "So'nggi aktivlik", 'data' => 'last_action'],
                                                            ['title' => "Til", 'data' => 'language'],
                                                            ['title' => "Telefon raqam", 'data' => 'phone'],
                                                            ['title' => "Tug'ilgan yil", 'data' => 'age'],
                                                            ['title' => "Hudud", 'data' => 'city'],
                                                            ['title' => "Jins", 'data' => 'gender'],
                                                            ['title' => "Harakat", 'data' => 'action'],
                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 3, 4, 5, 6, 7, 9]],
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
                                                <table class="table" data-datatable="client_users" data-params="<?php echo $dataParams; ?>"></table>

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