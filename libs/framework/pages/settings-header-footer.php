<div class="cw-wid-con">
    <div class="cw_container">
        <form action="" method="POST" id="cw-admin-settings-form">
            <div class="attr-row cw_tab_wraper_group">
                <div class="attr-col-lg-1 attr-col-md-1"></div>
                <div class="attr-col-lg-10 attr-col-md-10">
                    <div class="attr-tab-content" id="v-colorwayhf-tabContent">
                            <div class="attr-tab-pane <?php echo 'attr-active'; ?>" id="v-colorwayhf" role="tabpanel" >
                                <div class="cw-admin-section-header">
                                    <h2 class="cw-admin-section-heaer-title"><img src="<?php echo esc_url(self::get_url() . 'assets/images/cwhf-logo.png'); ?>" alt="colorway logo"></h2>                                   
                                </div>
                                <?php include self::get_dir() . 'pages/settings-modules.php'; ?>
                            </div>
                    </div>
                </div>
                <div class="attr-col-lg-1 attr-col-md-1"></div>
            </div>
        </form>
    </div>
</div>