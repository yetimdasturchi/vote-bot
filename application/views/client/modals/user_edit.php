<form class="ajax-modal-form" method="POST" action="<?php echo base_url('client/users/edit/' . $id);?>" autocomplete="off">
    <?php
        if ( !empty( $additionals ) ) {
            foreach ($additionals as $additional) {
                if ( !empty( $additional['source'] ) ) {
                    $data_type = "select";
                    $data_source = htmlspecialchars( json_encode( $additional['source'] ), ENT_QUOTES, 'UTF-8');
                }else{
                    $data_type = "text";
                    $data_source = "";
                }
    ?>
    <div class="mb-3">
        <label for="formrow-firstname-input" class="form-label"><?php echo $additional['name'];?></label>
        <?php
            if ( $data_type == 'select' ) {
        ?>
                <select id="formrow-inputState" class="form-select" name="<?php echo $additional['field'];?>">
                    <option value="">Tanlash</option>
                    <?php
                        foreach ($additional['source'] as $source) {
                            $selected = ( $additional['value'] == $source ) ? 'selected' : '';
                            echo "<option value=\"{$source}\" {$selected}>{$source}</option>";
                        }
                    ?>
                </select>
        <?php
            }else if( $data_type == 'text' ){
        ?>
                <input type="text" class="form-control" name="<?php echo $additional['field'];?>" value="<?php echo $additional['value'];?>" id="formrow-firstname-input" placeholder="">
        <?php
            }
        ?>
    </div>
    <?php
            }
        }
    ?>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-info waves-effect waves-light">Saqlash</button>
    </div>
</form>