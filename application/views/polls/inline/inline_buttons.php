<div data-repeater-item class="row" style="<?php ( isset( $display ) && $display ) ? '' : 'display:none;';?>">
    <div  class="mb-3 col-lg-4">
        <label for="name"><?php echo lang('polls_button_text');?></label>
        <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo lang('polls_button_enter_text');?>" value="<?php if( isset( $name ) ) echo $name;?>" />
    </div>

    <div  class="mb-3 col-lg-4">
        <label for="subject"><?php echo lang('polls_button_value');?></label>
        <input type="text" id="subject" name="value" class="form-control" placeholder="<?php echo lang('polls_button_enter_value');?>" value="<?php if( isset( $value ) ) echo $value;?>"/>
    </div>

    <div class="mb-3 col-lg-2">
        <label for="email"><?php echo lang('polls_button_type');?></label>
        <select id="formrow-first_command" name="type" class="form-select">
            <option value="" disabled selected hidden><?php echo lang('command_select');?></option>
            <option value="url" <?php if( isset( $type ) && $type == 'url' ) echo 'selected';?>><?php echo lang('polls_button_type_url');?></option>
            <option value="callback" <?php if( isset( $type ) && $type == 'callback' ) echo 'selected';?>><?php echo lang('polls_button_type_callback');?></option>
            <option value="webapp" <?php if( isset( $type ) && $type == 'webapp' ) echo 'selected';?>><?php echo lang('polls_button_type_webapp');?></option>
        </select>
    </div>
    
    <div class="col-lg-2 align-self-center">
        <div class="d-grid">
            <label for="subject"></label>
            <input data-repeater-delete type="button" class="btn btn-primary" value="<?php echo lang('polls_button_remove');?>"/>
        </div>
    </div>
</div>