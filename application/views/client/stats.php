<div class="page-content stats-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Statistika</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Barcha ovozlar</p>
                                        <h4 class="mb-0"><?php echo $all_votes['count'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-pink mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-pink">
                                                <i class="bx bx-user-voice font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Tasdiqlangan ovozlar</p>
                                        <h4 class="mb-0"><?php echo $all_votes['checked'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-check-double font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Bekor qilingan</p>
                                        <h4 class="mb-0"><?php echo $all_votes['invalid'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-danger mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-danger">
                                                <i class="bx bx-block font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Jarayonda</p>
                                        <h4 class="mb-0"><?php echo $all_votes['unchecked'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-warning">
                                                <i class="bx bx-time-five font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Ovoz bergan foydalanuvchilar</p>
                                        <h4 class="mb-0"><?php echo $all_votes_group['allv'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-pink mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-pink">
                                                <i class="bx bx-user-voice font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Tasdiqlangan foydalanuvchilar</p>
                                        <h4 class="mb-0"><?php echo $all_votes_group['success'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-check-double font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Bekor qilingan foydalanuvchilar</p>
                                        <h4 class="mb-0"><?php echo $all_votes_group['ignored'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-danger mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-danger">
                                                <i class="bx bx-block font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Jarayondagi foydalanuvchilar</p>
                                        <h4 class="mb-0"><?php echo $all_votes_group['process'];?></h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-warning">
                                                <i class="bx bx-time-five font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="card">
                            <div class="card-title">
                                Foydalanuvchilar
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => [intval(str_replace(' ', '', $all_users['all_womans'])), intval(str_replace(' ', '', $all_users['all_mans'])), intval(str_replace(' ', '', $all_users['all_undefined']))],
                                        "labels" => ["Ayollar: <b>".$all_users['all_womans']."</b>", "Erkaklar: <b>".$all_users['all_mans']."</b>", "Noaniq: <b>".$all_users['all_undefined']."</b>"],
                                        "colors" => ["#ff87a3", "#1b79e5", "#f1b44c"],
                                        "chart" => [
                                            "height" => '100%',
                                            "type" => "donut",
                                        ],
                                        "legend" => [
                                            "show" => true,
                                            "position" => "bottom",
                                            "horizontalAlign" => "center",
                                            "verticalAlign" => "middle",
                                            "floating" => false,
                                            "fontSize" => "14px",
                                            "offsetX" => 0
                                        ],
                                        "tooltip" => [
                                            "y" => [
                                                "title" => [
                                                    "formatter" => 'function( title ){ return title.replace(/\s+<b>.*<\/b>/g, "") }',
                                                ]
                                            ]
                                        ],
                                        "plotOptions" => [
                                            "pie" => [
                                                "donut" => [
                                                    "labels" => [
                                                        "show" => true,
                                                        "name" => [
                                                            "show" => true,
                                                            "formatter" => 'function( val ){return val.replace(/\s+<b>.*<\/b>/g, "");}',
                                                        ],
                                                        "total" => [
                                                            "show" => true,
                                                            "label" => 'Umumiy',
                                                            "formatter" => 'function(){return \''.$all_users['all_users'].'\';}',
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ];
                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div class="apex-charts" data-apexchart="users" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-title">
                                Foydalanuvchilar dinamikasi
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => [
                                            [
                                                'name' => 'Umumiy',
                                                'data' => array_map(function( $x ) {
                                                    return $x['count'];
                                                }, $monthly_users)
                                            ]
                                        ],
                                        "chart" => [
                                            "height" => '100%',
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
                                            "categories" => array_map(function( $x ) {
                                                return date("m-d", strtotime( $x['day'] ) );
                                            }, $monthly_users),
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
                                <div data-apexchart="user_dyn" class="apex-charts" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="card">
                            <div class="card-title">
                                Foydalanuvchilar tili
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => [intval(str_replace(' ', '', $all_language['all_uzbek'])), intval(str_replace(' ', '', $all_language['all_russian'])), intval(str_replace(' ', '', $all_language['all_undefined']))],
                                        "labels" => ["O'zbek: <b>".$all_language['all_uzbek']."</b>", "Rus: <b>".$all_language['all_russian']."</b>", "Noaniq: <b>".$all_language['all_undefined']."</b>"],
                                        "colors" => ["#F48D48", "#914ED6", "#f1b44c"],
                                        "chart" => [
                                            "height" => '100%',
                                            "type" => "donut",
                                        ],
                                        "legend" => [
                                            "show" => true,
                                            "position" => "bottom",
                                            "horizontalAlign" => "center",
                                            "verticalAlign" => "middle",
                                            "floating" => false,
                                            "fontSize" => "14px",
                                            "offsetX" => 0
                                        ],
                                        "tooltip" => [
                                            "y" => [
                                                "title" => [
                                                    "formatter" => 'function( title ){ return title.replace(/\s+<b>.*<\/b>/g, "") }',
                                                ]
                                            ]
                                        ],
                                        "plotOptions" => [
                                            "pie" => [
                                                "donut" => [
                                                    "labels" => [
                                                        "show" => true,
                                                        "name" => [
                                                            "show" => true,
                                                            "formatter" => 'function( val ){return val.replace(/<\/?[^>]+(>|$)/g, "");}',
                                                        ],
                                                        "total" => [
                                                            "show" => true,
                                                            "label" => 'Umumiy',
                                                            "formatter" => 'function(){return \''.$all_users['all_users'].'\';}',
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ];
                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div class="apex-charts" data-apexchart="language" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-title text-center">
                                Ovozlar
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => [
                                            [
                                                'name' => 'Tekshirilgan',
                                                'data' => array_map(function( $x ) {
                                                    return $x['count'];
                                                }, $monthly_votes['checked'])
                                            ],
                                            [
                                                'name' => 'Jarayonda',
                                                'data' => array_map(function( $x ) {
                                                    return $x['count'];
                                                }, $monthly_votes['unchecked'])
                                            ],
                                            [
                                                'name' => 'Bekor qilingan',
                                                'data' => array_map(function( $x ) {
                                                    return $x['count'];
                                                }, $monthly_votes['ignored'])
                                            ]
                                        ],
                                        "chart" => [
                                            "height" => "100%",
                                            "type" => "bar",
                                            "toolbar" => ["show" => false],
                                        ],
                                        "plotOptions" => [
                                            "bar" => [
                                                "horizontal" => false,
                                                "columnWidth" => "45%",
                                                "endingShape" => "rounded",
                                            ]
                                        ],
                                        'colors' => ["#54C397", "#F3C04F", "#F45459"],
                                        "dataLabels" => ["enabled" => false],
                                        "stroke" => [
                                            "show" => true,
                                            "width" => 2,
                                            "colors" => ["transparent"]
                                        ],
                                        "xaxis" => [
                                            "categories" => array_map(function( $x ) {
                                                return date("m-d", strtotime( $x['day'] ) );
                                            }, $monthly_votes['ignored']),
                                        ],
                                        "grid" => [
                                            "borderColor" => "#f1f1f1"
                                        ],
                                        "fill" => [ "opacity" => 1 ]
                                        
                                    ];
                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div data-apexchart="votes" class="apex-charts" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-title">
                                Foydalanuvchilar yoshi
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => array_map(function( $x ) {
                                                    return $x['count'];
                                                }, $users_age),
                                        "labels" => array_map(function( $x ) {
                                                    return $x['rng'].": <b>".$x['count']."</b>";
                                                }, $users_age),
                                        "colors" => ["#BA6DD5", "#9656E2", "#78CB9A", "#E1C55C", "#EF9E49", "#EF8DAE", "#D56B72", "#BEBEBE"],
                                        "chart" => [
                                            "height" => '100%',
                                            "type" => "donut",
                                        ],
                                        "legend" => [
                                            "show" => true,
                                            "position" => "right",
                                            "horizontalAlign" => "center",
                                            "verticalAlign" => "middle",
                                            "floating" => false,
                                            "fontSize" => "14px",
                                            "offsetX" => 0
                                        ],
                                        "tooltip" => [
                                            "y" => [
                                                "title" => [
                                                    "formatter" => 'function( title ){ return title.replace(/\s+<b>.*<\/b>/g, "") }',
                                                ]
                                            ]
                                        ],
                                        "plotOptions" => [
                                            "pie" => [
                                                "donut" => [
                                                    "labels" => [
                                                        "show" => true,
                                                        "name" => [
                                                            "show" => true,
                                                            "formatter" => 'function( val ){return val.replace(/\s+<b>.*<\/b>/g, "");}',
                                                        ],
                                                        "total" => [
                                                            "show" => true,
                                                            "label" => 'Umumiy',
                                                            "formatter" => 'function(){return \''.$all_users['all_users'].'\';}',
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        "responsive" => [
                                            [
                                                "breakpoint" => 1320,
                                                "options" => [
                                                    "legend" => [
                                                        "position" => "bottom"
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ];
                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div class="apex-charts" data-apexchart="age" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <!-- <div class="col-12 col-md-8">
                        <div class="card">
                            <div class="card-title">
                                Xarita
                            </div>
                            <div class="card-body">
                                <?php
                                    $mapdata = [];
                                    foreach ($cities as $value) {
                                        $mapdata[] = [ $value['code'], $value['values']['uzbek'], $value['values']['russian'], $value['values']['isnull'] ];
                                    }
                                    $mapdata = htmlspecialchars(  json_encode($mapdata), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div id="vectormap" data-mapdata="<?php echo $mapdata;?>" style="height: 1000px"></div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-title text-center p-3">
                                Hududlar kesimi
                            </div>
                            <div class="card-body" style="height: 500px;">
                                <?php
                                    $all = [];
                                    $uzbek = [];
                                    $russian = [];
                                    $isnull = [];
                                    $labels = [];
                                    foreach ($cities as $value) {
                                        $all[] = intval( $value['values']['uzbek'] ) + intval( $value['values']['russian'] ) + intval( $value['values']['isnull'] );
                                        $uzbek[] = $value['values']['uzbek'];
                                        $russian[] = $value['values']['russian'];
                                        $isnull[] = $value['values']['isnull'];
                                        if ( str_word_count( $value['name'] ) ) {
                                            $labels[] = explode( ' ' , $value['name']);
                                        }else{
                                            $labels[] = $value['name'];
                                        }
                                    }

                                    $dataParams = [
                                        "series" => [
                                            [
                                                'name' => 'Barchasi',
                                                'data' => $all
                                            ],
                                            [
                                                'name' => 'O\'zbek',
                                                'data' => $uzbek
                                            ],
                                            [
                                                'name' => 'Rus',
                                                'data' => $russian
                                            ],
                                            [
                                                'name' => 'Noaniq',
                                                'data' => $isnull
                                            ]
                                        ],
                                        "chart" => [
                                            "height" => "100%",
                                            "type" => "bar",
                                            "toolbar" => ["show" => false],
                                        ],
                                        "xaxis" => [
                                            "categories" => $labels,
                                            "labels" => [
                                                "rotate" => -45
                                            ]
                                        ],
                                        "legend" => [
                                            "show" => true,
                                            "position" => "top",
                                            "horizontalAlign" => "center",
                                            "verticalAlign" => "middle",
                                            "floating" => false,
                                            "fontSize" => "14px",
                                            "offsetX" => 0
                                        ],
                                        "plotOptions" => [
                                            "bar" => [
                                                "horizontal" => false,
                                                "columnWidth" => "45%",
                                                "endingShape" => "rounded",
                                            ]
                                        ],
                                        'colors' => ["#54C397", "#F3C04F", "#ff87a3", "#F45459"],
                                        "dataLabels" => ["enabled" => false],
                                        "stroke" => [
                                            "show" => true,
                                            "width" => 2,
                                            "colors" => ["transparent"]
                                        ],
                                        "grid" => [
                                            "borderColor" => "#f1f1f1"
                                        ],
                                        "fill" => [ "opacity" => 1 ]
                                        
                                    ];
                                    $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                ?>
                                <div data-apexchart="cities" class="apex-charts" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-title">
                                Hududlar kesimi
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 ct-table">
                                        <tbody>
                                            <?php
                                                $x = 0;
                                                foreach ($cities as $ct) {
                                                $x++;
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $x;?></th>
                                                <td><?php echo $ct['name'];?></td>
                                                <td style="width: 25%;text-align: right;"><?php echo number_format($ct['all'], 0, ',', ' ');?></td>
                                            </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- end row -->
        
    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->