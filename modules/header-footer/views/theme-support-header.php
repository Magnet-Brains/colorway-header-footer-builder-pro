<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title>
			<?php echo wp_get_document_title(); ?>
		</title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action('colorwayhf/template/before_header'); ?>
<div class="cw-template-content-markup cw-template-content-header cw-template-content-theme-support">
<?php
	$template = \ColorwayHF\Modules\Header_Footer\Activator::template_ids();
	echo \ColorwayHF\Utils::render_elementor_content($template[0]); 
?>
</div>
<?php do_action('colorwayhf/template/after_header'); ?>
