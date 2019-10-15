<div class="attr-modal attr-fade" id="colorwayhf_headerfooter_modal" tabindex="-1" role="dialog"
	aria-labelledby="colorwayhf_headerfooter_modalLabel">
	<div class="attr-modal-dialog attr-modal-dialog-centered" role="document">
		<form action="" mathod="get" id="colorwayhf-template-modalinput-form" data-open-editor="0"
			data-editor-url="<?php echo get_admin_url(); ?>">
			<input type="hidden" name="post_author" value ="<?php echo get_current_user_id(); ?>">
			<div class="attr-modal-content">
				<div class="attr-modal-header">
					<button type="button" class="attr-close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="attr-modal-title" id="colorwayhf_headerfooter_modalLabel"><?php esc_html_e('Template Settings', 'colorway-hf'); ?></h4>
				</div>
				<div class="attr-modal-body" id="colorwayhf_headerfooter_modal_body">
					<div class="cw-input-group">
						<label class="attr-input-label"><?php esc_html_e('Title:', 'colorway-hf'); ?></label>
						<input required type="text" name="title" class="cw-template-modalinput-title attr-form-control">
					</div>
					<br />
					<div class="cw-input-group">
						<label class="attr-input-label"><?php esc_html_e('Type:', 'colorway-hf'); ?></label>
						<select name="type" class="cw-template-modalinput-type attr-form-control">
							<option value="header"><?php esc_html_e('Header', 'colorway-hf'); ?></option>
							<option value="footer"><?php esc_html_e('Footer', 'colorway-hf'); ?></option>
<!--							<option value="section"><?php //esc_html_e('Section', 'colorway-hf'); ?></option>-->
						</select>
					</div>
					<br />

					<div class="cw-template-headerfooter-option-container">
						<div class="cw-input-group">
							<label class="attr-input-label"><?php esc_html_e('Conditions:', 'colorway-hf'); ?></label>
							<select name="condition_a" class="cw-template-modalinput-condition_a attr-form-control">
								<option value="entire_site"><?php esc_html_e('Entire Site', 'colorway-hf'); ?></option>
								<option value="singular"><?php esc_html_e('Singular', 'colorway-hf'); ?></option>
								<option value="archive"><?php esc_html_e('Archive', 'colorway-hf'); ?></option>
							</select>
						</div>
						<br>

						<div class="cw-template-modalinput-condition_singular-container">
							<div class="cw-input-group">
								<label class="attr-input-label"></label>
								<select name="condition_singular"
									class="cw-template-modalinput-condition_singular attr-form-control">
									<option value="all"><?php esc_html_e('All Singulars', 'colorway-hf'); ?></option>
									<option value="front_page"><?php esc_html_e('Front Page', 'colorway-hf'); ?></option>
									<option value="all_posts"><?php esc_html_e('All Posts', 'colorway-hf'); ?></option>
									<option value="all_pages"><?php esc_html_e('All Pages', 'colorway-hf'); ?></option>
									<option value="selective"><?php esc_html_e('Selective Singular', 'colorway-hf'); ?>
									</option>
									<option value="404page"><?php esc_html_e('404 Page', 'colorway-hf'); ?></option>
								</select>
							</div>
							<br>

							<div class="cw-template-modalinput-condition_singular_id-container cw_multipile_ajax_search_filed">
								<div class="cw-input-group">
									<label class="attr-input-label"></label>
									<select multiple name="condition_singular_id[]" class="cw-template-modalinput-condition_singular_id"></select>
								</div>
								<br />
							</div>
							<br>
						</div>


						<div class="cw-switch-group">
							<label class="attr-input-label"><?php esc_html_e('Activition:', 'colorway-hf'); ?></label>
							<div class="cw-admin-input-switch">
								<input checked="" type="checkbox" value="yes"
									class="cw-admin-control-input cw-template-modalinput-activition"
									name="activation" id="cw_activation_modal_input">
								<label class="cw-admin-control-label" for="cw_activation_modal_input">
									<span class="cw-admin-control-label-switch" data-active="ON"
										data-inactive="OFF"></span>
								</label>
							</div>
						</div>
					</div>
					<br>
				</div>
				<div class="attr-modal-footer">
					<button type="button" class="attr-btn attr-btn-default colorwayhf-template-save-btn-editor"><?php esc_html_e('Edit content', 'colorway-hf'); ?></button>
					<button type="submit" class="attr-btn attr-btn-primary colorwayhf-template-save-btn"><?php esc_html_e('Save changes', 'colorway-hf'); ?></button>
				</div>
				<div class="cw-spinner"></div>
			</div>
		</form>
	</div>
</div>