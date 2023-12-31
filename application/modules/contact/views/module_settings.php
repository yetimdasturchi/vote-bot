<form class="ajax-form" method="POST" action="<?php echo current_url();?>" autocomplete="off">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="formrow-min-input" class="form-label"><?php echo lang('settings_min_symbols');?></label>
                <input type="number" name="min" min="0" class="form-control" id="formrow-min-input" value="<?php echo $min;?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="formrow-max-input" class="form-label"><?php echo lang('settings_man_symbols');?></label>
                <input type="number" name="max" min="0" class="form-control" id="formrow-max-input" value="<?php echo $max;?>">
            </div>
        </div>
    </div>
    <div class="d-grid gap-2 mt-3">
        <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo lang('settings_save');?></button>
    </div>
</form>