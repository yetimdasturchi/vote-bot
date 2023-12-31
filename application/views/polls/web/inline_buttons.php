<div data-repeater-item class="row" style="<?php ( isset( $display ) && $display ) ? '' : 'display:none;';?>">
    <div  class="mb-3 col-lg-3">
        <label for="name"><?php echo lang('polls_button_text');?></label>
        <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('polls_button_enter_text');?>" value="<?php if( isset( $name ) ) echo $name;?>" />
    </div>

    <div  class="mb-3 col-lg-3">
        <label for="subject"><?php echo lang('polls_button_value');?></label>
        <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('polls_button_enter_value');?>" value="<?php if( isset( $value ) ) echo $value;?>"/>
    </div>

    <div class="mb-3 col-lg-2">
        <label for="email"><?php echo lang('polls_button_type');?></label>
        <select id="formrow-first_command" name="type" class="form-select">
            <option value="" disabled selected hidden><?php echo lang('command_select');?></option>
            <option value="url" <?php if( isset( $type ) && $type == 'url' ) echo 'selected';?>><?php echo lang('polls_button_type_url');?></option>
        </select>
    </div>

    <div  class="mb-3 col-lg-2">
        <label for="email"><?php echo lang('polls_language');?></label>
        <select id="formrow-language" name="language" class="form-select">
            <option value="" disabled selected hidden><?php echo lang('polls_select');?></option>
            <option value="uzbek" <?php if( isset( $language ) && $language == 'uzbek' ) echo 'selected';?>>O‘zbek</option>
            <option value="uzbek_cyr" <?php if( isset( $language ) && $language == 'uzbek_cyr' ) echo 'selected';?>>Ўзбек</option>
            <option value="russian" <?php if( isset( $language ) && $language == 'russian' ) echo 'selected';?>>Русский</option>
            <option value="english" <?php if( isset( $language ) && $language == 'english' ) echo 'selected';?>>English</option>
        </select>
    </div>
    
    <div class="col-lg-2 align-self-center">
        <div class="d-grid">
            <label for="subject"></label>
            <input data-repeater-delete type="button" class="btn btn-primary" value="<?php echo lang('polls_button_remove');?>"/>
        </div>
    </div>
</div>