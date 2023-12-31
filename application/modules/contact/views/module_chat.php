<div class="page-content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                          <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                              <h4 class="mb-sm-0 font-size-18">
                                <?php echo lang('module_name');?>
                              </h4>

                              <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                  <li class="breadcrumb-item">
                                    <a href="javascript: void(0);"
                                      ><?php echo lang('module_name');?></a
                                    >
                                  </li>
                                  <li class="breadcrumb-item active">
                                    <?php echo lang('chat_messages');?>
                                  </li>
                                </ol>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- end page title -->
                        <div class="d-lg-flex">
                            <div class="chat-leftsidebar me-lg-4">
                                <div class="">
                                    <div class="search-box chat-search-box py-0">
                                        <div class="position-relative">
                                            <input type="text" class="form-control" list="chats-datalist" data-url="<?php echo base_url($module_name . '/get_chats');?>" placeholder="<?php echo lang('chat_search');?>">
                                            <i class="bx bx-search-alt search-icon"></i>
                                            <datalist id="chats-datalist"></datalist>
                                        </div>
                                    </div>

                                    <div class="chat-leftsidebar-nav">
                                        <div class="tab-content py-4">
                                            <div class="tab-pane show active" id="chat">
                                                <div>
                                                    <ul class="list-unstyled chat-list" data-simplebar style="max-height: 600px;">
                                                        <?php
                                                            foreach ($chats as $chat) { 
                                                                $this->load->view('module_single_chat', array_merge($chat, ['module_name' => $module_name]));
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container chat-placeholder d-flex align-items-center justify-content-center">
                                <div>
                                  <p class="text-muted"><?php echo lang('chat_select_placeholder');?></p>
                                </div>
                            </div>
                            <div class="w-100 user-chat chat-window" style="display:none;"></div>
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->