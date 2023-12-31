<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Savollar</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Foydalanuvchilar</a></li>
                                            <li class="breadcrumb-item active">Savollar</li>
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
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="pollsDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Savol turi</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="pollsDropdown">
                                                                        <div class="scroll-list">
                                                                            <?php
                                                                                $questions = $this->db->get_where('poll_questions', ['status' => '1']);
                                                                                if ( $questions->num_rows() > 0 ) {
                                                                                    foreach ($questions->result_array() as $question) {
                                                                            ?>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input filtered_poll_ids" data-reload-table="client_questions" name="filtered_poll_ids[]" value="<?php echo $question['id'];?>" type="checkbox" id="quesion_<?php echo $question['id'];?>" <?php if( in_array($question['id'], $ids) ) echo "checked";?> />
                                                                                    <label class="form-check-label" for="quesion_<?php echo $question['id'];?>"><?php echo $question['name'];?></label>
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
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Javob holati</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="statusDropdown">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_qstatus" data-reload-table="client_questions" type="checkbox" value="0" id="statusInProcess" />
                                                                                <label class="form-check-label" for="statusInProcess">Jarayonda</label>
                                                                            </div>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_qstatus" data-reload-table="client_questions" type="checkbox" value="1" id="statusSuccess" />
                                                                                <label class="form-check-label" for="statusSuccess">Javob berilgan</label>
                                                                            </div>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_qstatus" data-reload-table="client_questions" type="checkbox" value="2" id="statusError" />
                                                                                <label class="form-check-label" for="statusError">Javob berilmagan</label>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <button type="button" class="btn btn-outline-success waves-effect waves-light action-button client-export-question">Eksport qilish</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5 float-right">
                                                        <div class="row">
                                                            <div class="col-12 col-md-7">
                                                                <div class="input-group searchbox">
                                                                    <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Qidirish..." data-table-search="client_questions">
                                                                    <div class="input-group-text"><i class="bx bx-search-alt-2"></i></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-5">
                                                                <button type="button" class="btn btn-primary waves-effect waves-light action-button question-resend">Barchaga yuborish</button>
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
                                                            'url' => base_url('client/question/getlist'),
                                                            'cache' => false,
                                                            'data' => 'function(data){
                                                                var ids = [];
                                                                $(".question-filter .filtered_poll_ids:checked").each(function(i, e) {
                                                                  ids.push($(this).val());
                                                                });
                                                                data.poll_ids = ids.join("|");

                                                                var status = [];
                                                                $(".question-filter .filtered_qstatus:checked").each(function(i, e) {
                                                                  status.push($(this).val());
                                                                });
                                                                data.poll_status = status.join("|");
                                                            }'
                                                        ],
                                                        'columns' => [
                                                            ['title' => "ID", 'data' => 'id'],
                                                            ['title' => "Foydalanuvchi", 'data' => 'u_name'],
                                                            ['title' => "Telefon raqam", 'data' => 'u_phone'],
                                                            ['title' => "Savol", 'data' => 'question'],
                                                            ['title' => "Yuborish vaqti", 'data' => 'send_date'],
                                                            ['title' => "Javob berilgan", 'data' => 'answered'],
                                                            ['title' => "Holat", 'data' => 'q_status'],
                                                            ['title' => "Harakat", 'data' => 'action'],
                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 2, 4, 5, 6, 7]],
                                                            ['orderable' => false, 'targets' => [7]]
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
                                                <table class="table" data-datatable="client_questions" data-params="<?php echo $dataParams; ?>"></table>

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