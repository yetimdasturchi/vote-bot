<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Andozalar</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Bildirishnomalar</a></li>
                                            <li class="breadcrumb-item active">Andozalar</li>
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
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5 float-right">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="input-group searchbox">
                                                                    <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Qidirish..." data-table-search="client_nitifications_templates">
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
                                                            'url' => base_url('client/notifications/templates/getlist'),
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
                                                            ['title' => "ID", 'data' => 'id'],
                                                            ['title' => "Andoza", 'data' => 'name'],
                                                            ['title' => "Sana", 'data' => 'date'],
                                                            ['title' => "So'nggi xabarnoma", 'data' => 'last_send'],
                                                            ['title' => "Barcha xabarlar", 'data' => 'all'],
                                                            ['title' => "Yuborilganlar", 'data' => 'success'],
                                                            ['title' => "Xatoliklar", 'data' => 'error'],
                                                            ['title' => "Harakat", 'data' => 'action'],

                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 2, 3, 4, 5, 6]],
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
                                                <table class="table" data-datatable="client_nitifications_templates" data-params="<?php echo $dataParams; ?>"></table>

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

                <div class="modal fade" tabindex="-1" role="dialog" id="sendModal" aria-labelledby="sendModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sendModalLabel">Yuborish</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="ajax-form" method="POST" action="<?php echo base_url('client/notifications/templates/send');?>" autocomplete="off">
                                    <input type="hidden" name="template" value="0" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-to" class="form-label">Qabul qilivchi</label>
                                                <select id="formrow-to" name="to" class="form-select">
                                                    <option value="users">Foydalanuvchilar</option>
                                                    <optgroup label="Arxiv">
                                                        <?php
                                                        $categories = $this->db->get('users_archive_categories');
                                                        if ( $categories->num_rows() > 0 ) {
                                                            foreach ($categories->result_array() as $category) {
                                                                echo "<option value=\"archive_{$category['users_archive_category_id']}\">{$category['users_archive_category_name']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        $slect2Params = [
                                            'language' => getDefaultLanguage(),
                                            'ajax' => [
                                                'url' => base_url('client/notifications/templates/get_users'),
                                                'tags' => true,
                                                'type' => 'get',
                                                'multiple' => true,
                                                'dataType' => 'json',
                                                'allowClear' => true,
                                                'delay' => 250,
                                                'cache' => true,
                                                'data' => 'function(params){return { search: params.term, page: params.page || 1 }; }',
                                                'processResults' => 'function (response) {return {results: response}}'
                                            ]
                                        ];
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="formrow-polls" class="form-label">Tanlangan foydalanuvchilar</label>
                                                <select class="select2 form-control select2-multiple" name="selected_users[]" multiple="multiple" data-placeholder="Tanlash" data-params="<?php echo htmlspecialchars(  json_encode($slect2Params), ENT_QUOTES, 'UTF-8' ); ?>"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-voted" class="form-label">Ovoz berish holati</label>
                                                <select id="formrow-voted" name="voted" class="form-select">
                                                    <option value="">Tanlash</option>
                                                    <option value="3">Jarayonda</option>
                                                    <option value="1">Ovoz bergan</option>
                                                    <option value="2">Tasdiqlanmagan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="formrow-language" class="form-label">Til</label>
                                                <select id="formrow-language" name="language" class="form-select">
                                                    <option value="">Tanlash</option>
                                                    <option value="uzbek">O'zbek</option>
                                                    <option value="russian">Rus</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="formrow-phone" class="form-label">Telefon raqam</label>
                                                <select id="formrow-phone" name="phone" class="form-select">
                                                    <option value="">Tanlash</option>
                                                    <option value="not_empty">Kiritilgan</option>
                                                    <option value="is_empty">Kiritilmagan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="formrow-language" class="form-label">Hudud</label>
                                                <select id="formrow-language" name="ct" class="form-select">
                                                    <option value="">Tanlash</option>
                                                    <?php
                                                        foreach ($ct as $k => $v) {
                                                            echo "<option value=\"{$k}\">{$v[0]}</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="formrow-phone" class="form-label">Jins</label>
                                                <select id="formrow-phone" name="gender" class="form-select">
                                                    <option value="">Tanlash</option>
                                                    <?php
                                                        foreach ($gender as $k => $v) {
                                                            echo "<option value=\"{$k}\">{$v[0]}</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Yuborish</button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->