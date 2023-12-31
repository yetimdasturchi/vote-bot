<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Ovozlar</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Foydalanuvchilar</a></li>
                                            <li class="breadcrumb-item active">Ovozlar</li>
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
                                                                        <span>Javob holati</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_vstatus" data-reload-table="client_votes" type="checkbox" value="0" id="statusInProcess" <?php if( in_array( '0', $status ) ) echo "checked";?> />
                                                                                <label class="form-check-label" for="statusInProcess">Jarayonda</label>
                                                                            </div>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_vstatus" data-reload-table="client_votes" type="checkbox" value="1" id="statusSuccess" <?php if( in_array( '1', $status ) ) echo "checked";?> />
                                                                                <label class="form-check-label" for="statusSuccess">Tekshirilgan</label>
                                                                            </div>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input filtered_vstatus" data-reload-table="client_votes" type="checkbox" value="2" id="statusError" <?php if( in_array( '2', $status ) ) echo "checked";?> />
                                                                                <label class="form-check-label" for="statusError">Bekor qilingan</label>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Nominatsiyalar</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                                                                        <div class="scroll-list">
                                                                            <?php
                                                                                $nominations = $this->db->get_where('nominations', [
                                                                                    'status' => 1
                                                                                ]);

                                                                                if ( $nominations->num_rows() > 0 ) {
                                                                                    foreach ($nominations->result_array() as $nomination) {
                                                                            ?>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input filtered_vnomination" data-reload-table="client_votes" type="checkbox" value="<?php echo $nomination['id'];?>" id="nomination_<?php echo $nomination['id'];?>" />
                                                                                    <label class="form-check-label" for="nomination_<?php echo $nomination['id'];?>"><?php echo $nomination['name'];?></label>
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
                                                                <div class="dropdown members-dropdown">
                                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                        <span>Ishtirokchilar</span> <i class="mdi mdi-chevron-down"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                                                                        <div class="p-2">
                                                                            <input class="form-control filtered_member" type="text"  placeholder="Qidirish..."/>
                                                                        </div>
                                                                        <div class="members-list">
                                                                            <?php
                                                                                $members = $this->db->get_where('members', [
                                                                                    'status' => 1
                                                                                ]);

                                                                                if ( $members->num_rows() > 0 ) {
                                                                                    foreach ($members->result_array() as $member) {
                                                                            ?>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input filtered_vmember" data-reload-table="client_votes" type="checkbox" value="<?php echo $member['id'];?>" id="nomination_<?php echo $member['id'];?>" />
                                                                                    <label class="form-check-label" for="nomination_<?php echo $member['id'];?>"><?php echo $member['name'];?></label>
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
                                                                <button type="button" class="btn btn-outline-success waves-effect waves-light action-button client-export-votes">Eksport qilish</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5 float-right">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="input-group searchbox">
                                                                    <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Qidirish..." data-table-search="client_votes">
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
                                                            'url' => base_url('client/votes/getlist'),
                                                            'cache' => false,
                                                            'data' => 'function(data){
                                                                var vstatus = [];
                                                                $(".question-filter .filtered_vstatus:checked").each(function(i, e) {
                                                                  vstatus.push($(this).val());
                                                                });
                                                                data.vstatus = vstatus.join("|");

                                                                var vnomination = [];
                                                                $(".question-filter .filtered_vnomination:checked").each(function(i, e) {
                                                                  vnomination.push($(this).val());
                                                                });
                                                                data.vnomination = vnomination.join("|");

                                                                var vmember = [];
                                                                $(".question-filter .filtered_vmember:checked").each(function(i, e) {
                                                                  vmember.push($(this).val());
                                                                });
                                                                data.vmember = vmember.join("|");
                                                            }'
                                                        ],
                                                        'columns' => [
                                                            ['title' => "ID", 'data' => 'id'],
                                                            ['title' => "Foydalanuvchi", 'data' => 'u_name'],
                                                            ['title' => "Telefon raqam", 'data' => 'u_phone'],
                                                            ['title' => "Nominatsiya", 'data' => 'nomination_name'],
                                                            ['title' => "Ishtirokchi", 'data' => 'member_name'],
                                                            ['title' => "Holat", 'data' => 'check_status'],
                                                            ['title' => "Vaqt", 'data' => 'date'],
                                                        ],
                                                        'columnDefs' => [
                                                            ['className' => 'text-center', 'targets' => [0, 4, 5]],
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
                                                <table class="table" data-datatable="client_votes" data-params="<?php echo $dataParams; ?>"></table>

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