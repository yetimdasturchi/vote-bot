<div class="page-content">
    <div class="container-fluid infographic-content">
        <div class="row">
            <div class="col-12 col-md-6 align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Infografika</h4>
            </div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <span>Javob holati</span> <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                                <a class="dropdown-item" href="#">
                                    <div class="form-check">
                                        <input class="form-check-input filtered_vstatus" data-reload-infographic="client_votes" type="checkbox" value="0" id="statusInProcess" <?php if( in_array( '0', $status ) ) echo "checked";?> />
                                        <label class="form-check-label" for="statusInProcess">Jarayonda</label>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <div class="form-check">
                                        <input class="form-check-input filtered_vstatus" data-reload-infographic="client_votes" type="checkbox" value="1" id="statusSuccess" <?php if( in_array( '1', $status ) ) echo "checked";?> />
                                        <label class="form-check-label" for="statusSuccess">Tekshirilgan</label>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <div class="form-check">
                                        <input class="form-check-input filtered_vstatus" data-reload-infographic="client_votes" type="checkbox" value="2" id="statusError" <?php if( in_array( '2', $status ) ) echo "checked";?> />
                                        <label class="form-check-label" for="statusError">Bekor qilingan</label>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
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
                                            <input class="form-check-input filtered_vnomination" data-reload-infographic="client_votes" type="checkbox" value="<?php echo $nomination['id'];?>" id="nomination_<?php echo $nomination['id'];?>" <?php if( in_array($nomination['id'], $selected_nominations) ) echo "checked";?> />
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
                    <div class="col-12 col-md-4">
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
                                    <a class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input filtered_vmember" data-reload-infographic="client_votes" type="checkbox" value="<?php echo $member['id'];?>" id="member_<?php echo $member['id'];?>" <?php if( in_array($member['id'], $selected_members) ) echo "checked";?> />
                                            <label class="form-check-label" for="member_<?php echo $member['id'];?>"><?php echo $member['name'];?></label>
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
            </div>
            <div class="col-12 col-md-1">
                <button type="button" class="btn btn-outline-primary waves-effect waves-light save-infographic">Yuklash</button>
            </div>
        </div>
        <!-- end page title -->
        <div class="row save-infographic-content">
            <div class="col-12">
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <div class="card">
                            <div class="card-title p-3">
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
                                <div class="apex-charts" data-apexchart="users" dir="ltr" data-params="<?php echo $dataParams; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="card">
                            <div class="card-title p-3">
                                Foydalanuvchilar tili
                            </div>
                            <div class="card-body" style="height: 350px;">
                                <?php
                                    $dataParams = [
                                        "series" => [(
                                                intval(
                                                    str_replace(' ', '', $all_language['all_uzbek'])
                                                ) +
                                                intval(
                                                    str_replace(' ', '', $all_language['all_undefined'])
                                                )
                                            ),
                                            intval(
                                                str_replace(' ', '', $all_language['all_russian'])
                                            )],
                                        "labels" => [
                                            "O'zbek: <b>".( intval(
                                                        str_replace(' ', '', $all_language['all_uzbek'])
                                                    ) + intval(
                                                    str_replace(' ', '', $all_language['all_undefined'])
                                                ) )."</b>",
                                            "Rus: <b>".$all_language['all_russian']."</b>"
                                        ],
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
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-title p-3">
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
                                        "tooltip" => [
                                            "y" => [
                                                "title" => [
                                                    "formatter" => 'function( title ){ return title.replace(/\s+<b>.*<\/b>/g, "") }',
                                                ]
                                            ]
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

                <div class="row">
                    <!-- <div class="col-12 col-md-8">
                        <div class="card">
                            <div class="card-title p-3">
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
                                <div id="vectormap2" data-mapdata="<?php echo $mapdata;?>" style="height: 1000px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-title p-3">
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
                                        $uzbek[] = intval( $value['values']['uzbek'] ) + intval( $value['values']['isnull'] );
                                        $russian[] = $value['values']['russian'];
                                        //$isnull[] = $value['values']['isnull'];
                                        if ( $value['name'] != 'noaniq' ) {
                                            if ( str_word_count( $value['name'] ) ) {
                                                $labels[] = explode( ' ' , $value['name']);
                                            }else{
                                                $labels[] = $value['name'];
                                            }
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
                </div>
            </div>
        </div>
    </div>
</div>