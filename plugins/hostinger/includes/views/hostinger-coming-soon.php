<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php _e( 'Coming Soon', 'hostinger' ) ?></title>
	<?php if( ( get_option( 'hostinger_maintenance_mode', 0 ) ) ) : ?>
		<style>
            html *:not(body):not(.hsr-coming-soon-body > *) {
                display: none;
            }

            .hsr-coming-soon-body {
                display: flex !important;
            }
		</style>
	<?php endif; ?>
</head>
<body class="hostinger">
<div class="hsr-coming-soon-body">
    <img alt="logo" class="hsr-logo"
         src="<?php echo esc_url( HOSTINGER_PLUGIN_URL . 'assets/images/logo-black.svg' ); ?>">
    <img alt="illustration" class="hsr-coming-soon-illustration"
         src="<?php echo esc_url( HOSTINGER_PLUGIN_URL . 'assets/images/illustration.png' ); ?>">
    <h3><?php _e( 'Coming Soon', 'hostinger' ) ?></h3>
    <p><?php _e( 'New WordPress website is being built and will be published soon', 'hostinger' ) ?></p>
</div>

<?php wp_footer(); ?>
</body>
</html>
