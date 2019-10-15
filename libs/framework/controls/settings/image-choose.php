<div class="attr-input attr-input-image-choose <?php echo esc_attr($class); ?>">
<?php
    // $options['large_img'] $options['icon'] $options['small_img'] self::strify($name) $label $value
    // $options['checked'] true / false
?>
    <div class="cw-admin-input-switch cw-admin-card-shadow attr-card-body">
        <input <?php echo esc_attr($options['checked'] === true ? 'checked' : ''); ?> 
            type="checkbox" value="<?php echo esc_attr($value); ?>" 
            class="cw-admin-control-input" 
            name="<?php echo esc_attr($name); ?>" 
            id="cw-admin-switch__<?php echo esc_attr(self::strify($name) . $value); ?>"
        >

        <label class="cw-admin-control-label"  for="cw-admin-switch__<?php echo esc_attr(self::strify($name) . $value); ?>">
        <?php echo esc_html($label); ?>
            <span class="cw-admin-control-label-switch" data-active="ON" data-inactive="OFF"></span>
        </label>
    </div>

</div>