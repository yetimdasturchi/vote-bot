<div class="row">
	<div class="col-12">
		<div class="text-center">
			<h4><?php echo lang('module_status_management');?></h4>
			<?php
				if ( $installed ) {
			?>
					<button type="button" class="btn btn-danger waves-effect waves-light" data-ajax-button="<?php echo current_url() . '/uninstall';?>" data-message="<?php echo lang('module_installer_uninstall_confirm');?>"><?php echo lang('module_installer_uninstall');?></button>
			<?php	
				}else{
			?>
					<button type="button" class="btn btn-success waves-effect waves-light" data-ajax-button="<?php echo current_url() . '/install';?>" data-message="<?php echo lang('module_installer_install_confirm');?>"><?php echo lang('module_installer_install');?></button>
			<?php
				}
			?>
		</div>
	</div>
</div>