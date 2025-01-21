<?php require 'header.php'; ?>

<div class="heartland-plugin-copy">
    <p>
        <?php esc_attr_e('This management terminal exposes limited functionality to manage transactions through your WordPress installation.', 'heartland-management-terminal') ?>
        <?php esc_attr_e('Current functionality may not be a one-to-one match for Portico Virtual Terminal.', 'heartland-management-terminal') ?>
    </p>
</div>

<div class="heartland-plugins">

    <section class="plugin plugin-woo">

        <a class="square" href="https://wordpress.org/plugins/woocommerce-securesubmit-gateway/" target="_blank">

            <img src="<?php echo esc_attr(plugins_url('/assets/woo.png', __FILE__)) ?>" />

            <strong><?php esc_attr_e('WooCommerce SecureSubmit Gateway', 'heartland-management-terminal') ?></strong>

            <span class="description">
                <?php esc_attr_e('Accept payments with Secure Submit and WooCommerce', 'heartland-management-terminal') ?>
            </span>

        </a>

        <a class="install" href="<?php echo esc_attr(get_admin_url(null, 'plugin-install.php?s=%22WooCommerce+SecureSubmit+Gateway%22&tab=search&type=term')) ?>">
            <?php esc_attr_e('Install Plugin', 'heartland-management-terminal') ?>
        </a>

    </section>

    <section class="plugin plugin-ss">

        <a class="square" href="https://wordpress.org/plugins/securesubmit/" target="_blank">

            <img src="<?php echo esc_attr(plugins_url('/assets/ss.png', __FILE__)) ?>" />

            <strong><?php esc_attr_e('WP SecureSubmit', 'heartland-management-terminal') ?></strong>

            <span class="description">
                <?php esc_attr_e('Create simple forms with WP SecureSubmit to take payments', 'heartland-management-terminal') ?>
            </span>

        </a>

        <a class="install" href="<?php echo esc_attr((get_admin_url(null, 'plugin-install.php?s=%WP+SecureSubmit%22&tab=search&type=term'))) ?>">
            <?php esc_attr_e('Install Plugin', 'heartland-management-terminal') ?>
        </a>

    </section>

    <section class="plugin plugin-gf">

        <a class="square" href="https://wordpress.org/plugins-wp/heartland-secure-submit-addon-for-gravity-forms/" target="_blank">

            <img src="<?php echo esc_attr(plugins_url('/assets/gravityforms.png', __FILE__)) ?>" />

            <strong><?php esc_attr_e('Heartland Secure Submit Addon for Gravity Forms', 'heartland-management-terminal') ?></strong>

            <span class="description">
                <?php esc_attr_e('Leverage Gravity Forms to create forms and accept payments', 'heartland-management-terminal') ?>
            </span>

        </a>

        <a class="install" href="<?php echo esc_attr(get_admin_url(null, 'plugin-install.php?s=%22Heartland+Secure+Submit+Addon+for+Gravity+Forms%22&tab=search&type=term')) ?>">
            <?php esc_attr_e('Install Plugin', 'heartland-management-terminal') ?>
        </a>

    </section>

    <section class="plugin plugin-em">

        <a class="square" href="https://wordpress.org/plugins-wp/events-manager-pro-securesubmit-gateway/" target="_blank">

            <img src="<?php echo esc_attr(plugins_url('/assets/eventsmanager.png', __FILE__)) ?>" />

            <strong><?php esc_attr_e('Events Manager Pro SecureSubmit Gateway', 'heartland-management-terminal') ?></strong>

            <span class="description">
                <?php esc_attr_e('Accept payments with Secure Submit and Events Manager Pro', 'heartland-management-terminal') ?>
            </span>

        </a>

        <a class="install" href="<?php echo esc_attr(get_admin_url(null, 'plugin-install.php?s=%22Events+Manager+Pro+SecureSubmit+Gateway%22&tab=search&type=term')) ?>">
            <?php esc_attr_e('Install Plugin', 'heartland-management-terminal') ?>
        </a>

    </section>

    <section class="plugin plugin-contact">
        <a class="square" href="https://developer.heartlandpaymentsystems.com/support" target="_blank">
            <img src="<?php echo esc_attr(plugins_url('/assets/h.png', __FILE__)) ?>" />
            <strong><?php esc_attr_e('Contact Heartland Payment Systems Support', 'heartland-management-terminal') ?></strong>
            <span class="description">
                <?php esc_attr_e('Please contact support with any questions or issues you have.', 'heartland-management-terminal') ?>
            </span>
        </a>
    </section>

</div>

<?php require 'footer.php'; ?>
