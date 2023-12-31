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
                  ><?php echo lang('module_name');?></a>
              </li>
              <li class="breadcrumb-item active">
                <?php echo $section;?>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
          <div class="card">
              <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <select class="form-select mb-3" onchange="window.location.href = '<?php echo base_url('modules/'.$module_name.'/module/');?>' + this.value">
                        <option value=""><?php echo lang('module_modules');?></option>
                        <?php
                          $language = $this->config->item('language');
                          foreach ($items as $k => $v) {
                            $module_config = $this->module->load_config( $k );
                        ?>
                          <option value="<?php echo $k;?>" <?php if( !empty( $args[1] ) && $args[1] == $k ) echo "selected";?> ><?php echo $module_config['name'][$language];?></option>
                        <?php
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <?php
                    if ( !empty( $args[1] ) && array_key_exists($args[1], $items) ) {
                  ?>
                  <form class="ajax-form" method="POST" action="<?php echo base_url('modules/' . $module_name . '/module/' . $args[1] );?>" autocomplete="off">
                    <div>
                        <?php
                          foreach ( $items[$args[1]]['uzbek'] as $kk => $vv ) {
                            foreach ($vv as $kkk => $vvv) {
                        ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <div>
                                    <label class="form-label">English</label>
                                    <textarea class="form-control" placeholder="" name="<?php echo $args[1].'[english]['. $kk .']['. $kkk .']';?>"><?php echo $items[$args[1]]['english'][$kk][$kkk];?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div>
                                    <label class="form-label">Russian</label>
                                    <textarea class="form-control" placeholder="" name="<?php echo $args[1].'[russian]['. $kk .']['. $kkk .']';?>"><?php echo $items[$args[1]]['russian'][$kk][$kkk];?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div>
                                    <label class="form-label">Uzbek</label>
                                    <textarea class="form-control" placeholder="" disabled name="<?php echo $args[1].'[uzbek]['. $kk .']['. $kkk .']';?>"><?php echo $vvv;?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div>
                                    <label class="form-label">Uzbek cyrillic</label>
                                    <textarea class="form-control" placeholder="" name="<?php echo $args[1].'[uzbek_cyr]['. $kk .']['. $kkk .']';?>"><?php echo $items[$args[1]]['uzbek_cyr'][$kk][$kkk];?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- "<?php echo $kkk;?>" -->
                        <?php
                            }
                          }
                        ?>
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-info waves-effect waves-light fixed-button"><?php echo lang('systemm_save');?></button>
                        </div>
                    </div>
                  </form>
                  <?php
                    }
                  ?>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>