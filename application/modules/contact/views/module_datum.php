<div class="row">
	<div class="col-12">
		<div class="text-center">
			<h4><?php echo lang('module_data_state_management');?></h4>
			<button type="button" class="btn btn-danger waves-effect waves-light" data-ajax-button="<?php echo current_url() . '/clear';?>" data-message="<?php echo lang('module_do_you_really_want_clear_data');?>" <?php if( !$installed ) echo "disabled";?>><?php echo lang('module_clear_data');?></button>
		</div>
	</div>
</div>