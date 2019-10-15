<div class="form-group cw-admin-input-text cw-admin-input-text-<?php echo esc_attr(self::strify($name)); ?>">
    <label for="cw-admin-option-text<?php echo esc_attr(self::strify($name)); ?>"><?php echo esc_html($label); ?></label>
    <input
        type="text"
        class="attr-form-control"
        id="cw-admin-option-text<?php echo esc_attr(self::strify($name)); ?>"
        aria-describedby="cw-admin-option-text-help<?php echo esc_attr(self::strify($name)); ?>"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        name="<?php echo esc_attr($name); ?>"
        value="<?php echo esc_attr($value); ?>"
    >
    <small id="cw-admin-option-text-help<?php echo esc_attr(self::strify($name)); ?>" class="form-text text-muted"><?php echo esc_html($info); ?></small>
</div>