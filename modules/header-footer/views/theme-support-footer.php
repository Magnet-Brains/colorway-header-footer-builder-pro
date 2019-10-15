<?php do_action('colorwayhf/template/before_footer'); ?>
<div class="cw-template-content-markup cw-template-content-footer cw-template-content-theme-support">
<?php
	$template = \ColorwayHF\Modules\Header_Footer\Activator::template_ids();
	echo \ColorwayHF\Utils::render_elementor_content($template[1]); 
?>
</div>
<?php do_action('colorwayhf/template/after_footer'); ?>
<?php wp_footer(); ?>

</body>
</html>
