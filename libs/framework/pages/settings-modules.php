<?php
$modules_all = \ColorwayHF::default_modules();
$modules_active = $this->utils->get_option('module_list', $modules_all);
$modules_free = \ColorwayHF::default_modules('free');
?>
<div class="cw-admin-settings-box">
    <div class="cw-admin-fields-container">
        <div class="cw-settings-block-container">
            <div class="cw-heading-wrap">
                <h2 class="cw-admin-header-title"><?php esc_html_e('Switch ON/OFF Module', 'colorway-hf'); ?></h2>
                <span class="cw-admin-fields-container-description"><?php esc_html_e('You can switch off the required modules if you are not using them on your website.', 'colorway-hf'); ?></span>
            </div>
            <div class="cw-admin-input-switch save-bttn">
                <button class="attr-btn-primary attr-btn cw-admin-settings-form-submit"><div class="cw-spinner"></div><?php esc_html_e('Save Changes', 'colorway-hf'); ?></button>
            </div>
        </div>
        <div class="cw-admin-fields-container-fieldset">
            <div class="attr-hidden" id="colorwayhf-template-admin-menu">
                <li><a href="edit.php?post_type=colorwayhf_template"><?php esc_html_e('My Templates', 'colorway-hf'); ?></a></li>
            </div>
            <div class="attr-row">
                <?php foreach ($modules_all as $module): ?>
                    <div class="attr-col-md-6 attr-col-lg-6">
                        <?php
                        $this->utils->input([
                            'type' => 'switch',
                            'name' => 'module_list[]',
                            'value' => $module,
                            'class' => ((in_array($module, $modules_free)) ? 'cw-content-type-free' : 'cw-content-type-pro'),
                            'label' => ucwords(str_replace('-', ' ', $module)),
                            'options' => [
                                'checked' => ((in_array($module, $modules_active)) ? true : false),
                            ]
                        ]);
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>      
    </div>
</div>