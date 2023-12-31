<div data-repeater-item class="row" style="<?php ( isset( $display ) && $display ) ? '' : 'display:none;';?>">
    <div  class="mb-3 col-lg-4">
        <label for="name">Matn</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Tugma ustida chiquvchi matn" value="<?php if( isset( $name ) ) echo $name;?>" />
    </div>

    <div  class="mb-3 col-lg-4">
        <label for="subject">Qiymat</label>
        <input type="text" id="subject" name="value" class="form-control" placeholder="Tugma vazifasi uchun qiymat" value="<?php if( isset( $value ) ) echo $value;?>"/>
    </div>

    <div class="mb-3 col-lg-2">
        <label for="email">Turi</label>
        <select id="formrow-first_command" name="type" class="form-select">
            <option value="" disabled selected hidden><?php echo lang('command_select');?></option>
            <option value="url" <?php if( isset( $type ) && $type == 'url' ) echo 'selected';?>>Havola</option>
            <option value="callback" <?php if( isset( $type ) && $type == 'callback' ) echo 'selected';?>>Qayta qo'ng'iroq</option>
            <option value="webapp" <?php if( isset( $type ) && $type == 'webapp' ) echo 'selected';?>>Veb ilova</option>
        </select>
    </div>
    
    <div class="col-lg-2 align-self-center">
        <div class="d-grid">
            <label for="subject"></label>
            <input data-repeater-delete type="button" class="btn btn-primary" value="O'chirish"/>
        </div>
    </div>
</div>