<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Tahrirlash</h4>
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
                <div class="card">
                    <div class="card-body">
                        <form class="ajax-form" method="POST" action="<?php echo base_url('client/notifications/edit/' . $id );?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-firstname-input" class="form-label">Andoza nomi</label>
                                        <input type="text" name="name" class="form-control" id="formrow-firstname-input" placeholder="Andoza nomini kiriting..." value="<?php echo $name;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-message" class="form-label">Xabar</label>
                                        <textarea type="number" name="message" class="form-control" id="formrow-message" placeholder="Xabarni kiriting..." rows="5"><?php echo $message;?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <?php
                                            $dataParams = [
                                                'messages' => [
                                                    'default' => "Faylni bu yerga sudrab tashlang yoki tanlash uchun bosing",
                                                    'replace' => "Faylni o'zgartirish uchun sudrab olib tashlang yoki bosing",
                                                    'remove' => "Tozalash",
                                                    'error' => "Xatolik yuz berdi.",
                                                ],
                                                'error' => [
                                                    'fileSize' => "Fayl hajmi juda katta ({{ value }} max).",
                                                    'minWidth' => "Rasm kengligi juda kichik ({{ value }}}px min).",
                                                    'maxWidth' => "Rasm kengligi juda katta ({{ value }}}px max).",
                                                    'minHeight' => "Rasm balandligi juda kichik ({{ value }}}px min).",
                                                    'maxHeight' => "Rasm balandligi juda katta ({{ value }}px max).",
                                                    'imageFormat' => "Ruxsat etilmagan tasvir turi (Ruxsat etilganlar: {{ value }}).",
                                                    'fileExtension' => "Ruxsat etilmagan fayl (Ruxsat etilganlar: {{ value }})"
                                                ]
                                            ];
                                            $dataParams = htmlspecialchars(  json_encode($dataParams), ENT_QUOTES, 'UTF-8' );
                                        ?>
                                        <input type="file" name="file" class="dropify" title=" " data-show-remove="true" data-params="<?php echo $dataParams; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 repeater" data-sorting="true" data-init="<?php echo !empty( $inline_keyboard ) ? 'false' : 'true';?>">
                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mb-3 mt-lg-0" value="Tugma qo'shish"/>
                                    <div data-repeater-list="buttons">
                                        <?php
                                            if ( !empty( $inline_keyboard ) ) {
                                                $inline_keyboard = json_decode( $inline_keyboard, TRUE );
                                                foreach ($inline_keyboard as $item) {
                                                    $item['display'] = true;
                                                    $this->load->view('client/notifications/inline_buttons', $item);
                                                }
                                            }else{
                                                $this->load->view('client/notifications/inline_buttons');
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Saqlash</button>
                            </div>
                        </form>
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