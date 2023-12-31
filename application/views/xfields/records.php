<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('xfields_list');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('xfields');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('xfields_list');?></li>
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
                                'searching' => false,
                                'paging' => false,
                                'info' => false,
                                'ordering' => false,
                                'iDisplayLength' => -1,
                                'language' => [
                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                ],
                                'ajax' => [
                                    'url' => base_url('xfields/records/getlist'),
                                    'cache' => false
                                ],
                                'columns' => [
                                    ['title' => lang('xfields_name'), 'data' => 'name'],
                                    ['title' => lang('xfields_xfield'), 'data' => 'xfield'],
                                    ['title' => lang('xfields_action'), 'data' => 'action']
                                ],
                                'columnDefs' => [
                                    ['className' => 'text-center', 'targets' => [0,1,2]]
                                ],
                                'responsive' => true,
                                'buttonsDom' => 'Bfrtip',
                                'dom' => 'Bfrtip',
                                'buttons' => [
                                    [
                                        'text' => '<i class="fa fa-plus"></i> ' . lang('xfields_add'),
                                        'className' => 'btn-success',
                                        'action' => 'function(e,dt,node,config){ window.location.href = "'.base_url('xfields/add').'" }'
                                    ]
                                ]
                            ];

                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                        ?>
                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="xfields_records" data-params="<?php echo $dataParams; ?>"></table>

                        <?php
                            unset($dataParams);
                        ?>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</div>