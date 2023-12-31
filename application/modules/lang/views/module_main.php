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
                <?php echo $section;?>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- end page title -->

    <div class="d-xl-flex">
      <div class="w-100">
        <div class="d-md-flex">
          <div class="card filemanager-sidebar me-md-2">
            <div class="card-body">
              <div class="d-flex flex-column h-100">
                <div class="mb-3">
                  <ul class="list-unstyled categories-list">
                    
                    <li>
                      <a href="<?php echo base_url( 'modules/' . $module_name . '/system' );?>" class="text-body d-flex align-items-center">
                        <i class="mdi mdi-animation text-muted font-size-16 me-2"></i>
                        <span class="me-auto"><?php echo lang('module_system');?></span>
                      </a>
                    </li>

                    <li>
                      <a href="<?php echo base_url( 'modules/' . $module_name . '/module' );?>" class="text-body d-flex align-items-center">
                        <i class="mdi mdi-layers text-muted font-size-16 me-2"></i>
                        <span class="me-auto"><?php echo lang('module_modules');?></span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- filemanager-leftsidebar -->

          <div class="w-100">
            <div class="card">
              <div class="card-body">
                <div class="mt-0">
                  <?php
                    $this->load->view($content, ( isset( $content_data ) ? $content_data : [] ) );
                  ?>
                </div>
              </div>
            </div>
            <!-- end card -->
          </div>
          <!-- end w-100 -->
        </div>
      </div>
    </div>
    <!-- end row -->
  </div>
  <!-- container-fluid -->
</div>
<!-- End Page-content -->
