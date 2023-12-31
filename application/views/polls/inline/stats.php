<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><?php echo lang('poll_stats');?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo lang('polls');?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('poll_stats');?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="min-height: 388px;">
                        <?php if ( !empty( $poll_answers ) ): ?>
                            <div data-simplebar class="mt-2" style="max-height: 280px;">
                                <ul class="stats verti-timeline list-unstyled">
                                    <?php
                                        foreach ($poll_answers as $answer) {
                                    ?>
                                        <li class="event-list active">
                                            <div class="event-timeline-dot">
                                                <i class="bx bxs-right-arrow-circle font-size-18 bx-fade-right"></i>
                                            </div>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <h5 class="font-size-14"><?php echo $answer['hashtag_answer_id'];?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <?php echo $answer['answer'];?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                              <?php echo lang('poll_no_data');?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="min-height: 388px;">
                        <?php if ( !empty( $poll_series ) ): ?>
                            <?php
                                $dataParams = [
                                    "series" => $poll_series,
                                    "chart" => [
                                        "height" => 350,
                                        "type" => "area",
                                        "toolbar" => ["show" => false],
                                    ],
                                    "dataLabels" => ["enabled" => false],
                                    "stroke" => ["curve" => "smooth", "width" => 2],
                                    "fill" => [
                                        "type" => "gradient",
                                        "gradient" => [
                                            "shadeIntensity" => 1,
                                            "inverseColors" => false,
                                            "opacityFrom" => 0.45,
                                            "opacityTo" => 0.05,
                                            "stops" => [20, 100, 100, 100],
                                        ],
                                    ],
                                    "xaxis" => [
                                        "categories" => ['Du', 'Se', 'Cho', 'Pa', 'Ju', 'Sha', 'Ya'],
                                    ],
                                    "markers" => [
                                        "size" => 3,
                                        "strokeWidth" => 3,
                                        "hover" => ["size" => 4, "sizeOffset" => 2],
                                    ],
                                    "legend" => ["position" => "top", "horizontalAlign" => "right"],
                                ];
                                $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                            ?>
                            <div class="apex-charts" data-apexchart="weekly" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                              <?php echo lang('poll_no_data');?>
                            </div>
                        <?php endif ?>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="min-height: 388px;">
                        <?php if ( !empty( $poll_answers ) ): ?>
                            <?php
                                $dataParams = [
                                    "chart" => ["height" => 382, "type" => "pie"],
                                    "series" => array_column($poll_answers, 'total'),
                                    "labels" => array_column($poll_answers, 'hashtag_answer_id'),
                                    "legend" => [
                                        "show" => true,
                                        "position" => "bottom",
                                        "horizontalAlign" => "center",
                                        "verticalAlign" => "middle",
                                        "floating" => false,
                                        "fontSize" => "14px",
                                        "offsetX" => 0,
                                    ],
                                ];
                                $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                            ?>
                            <div class="apex-charts" data-apexchart="overall" data-params="<?php echo $dataParams; ?>"></div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                              <?php echo lang('poll_no_data');?>
                            </div>
                        <?php endif ?>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
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
                                'paging' => true,
                                'info' => false,
                                'ordering' => false,
                                'iDisplayLength' => 10,
                                'language' => [
                                    'url' => base_url('assets/json/datatable-' . getDefaultLanguage() . '.json')
                                ],
                                'ajax' => [
                                    'url' => base_url('polls/inline/stats/'.$id.'/getlist'),
                                    'cache' => false,
                                    'data' => 'function(data){data.question_id = '.$id.';}'
                                ],
                                'columns' => [
                                    ['title' => lang('polls_id'), 'data' => 'id'],
                                    ['title' => lang('poll_user'), 'data' => 'user'],
                                    ['title' => lang('poll_answer'), 'data' => 'answer'],
                                    ['title' => lang('poll_datetime'), 'data' => 'date']
                                ],
                                'columnDefs' => [
                                    ['className' => 'text-center', 'targets' => [0,3]]
                                ],
                                'responsive' => true,
                                'buttonsDom' => 'Bfrtip',
                                'dom' => 'Bfrtip',
                                'buttons' => [],
                            ];

                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                        ?>
                        <table class="table table-striped dt-responsive table-sm nowrap w-100" data-datatable="polls_inline_options" data-params="<?php echo $dataParams; ?>"></table>

                        <?php
                            unset($dataParams);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>