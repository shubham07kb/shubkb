<?php
/**
 * Mail
 *
 * This class is used to send mail.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Init;

/**
 * Mail
 *
 * This class is used to send mail.
 */
class Mail {

	/**
	 * Mail Data
	 *
	 * @var array
	 */
	private $mail = array();

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->set_mail();
	}

	/**
	 * Set Mail
	 *
	 * This function is used to send mail.
	 */
	public function set_mail() {

		// is WS_SMTP defined and true.
		if ( defined( 'WS_MAIL' ) && WS_MAIL ) {
			$this->set_mail_settings_by_constant();
		} else {
			$this->set_mail_settings_by_option();
		}

		// From email and name.
		$this->mail['from_email'] = get_option( 'admin_email' );
		$this->mail['from_name']  = get_bloginfo( 'name' );

		error_log( wp_json_encode( $this->mail, 1, 4 ) );
		add_action( 'phpmailer_init', array( $this, 'apply_smtp' ) );
	}

	/**
	 * Set SMTP Settings By Constant
	 *
	 * This function is used to set the SMTP settings by constant.
	 */
	public function set_mail_settings_by_constant() {

		// Typr of authentication.
		if ( defined( 'WS_MAIL_TYPE' ) && WS_MAIL_TYPE ) {
			$this->mail['type'] = WS_MAIL_TYPE;
		}

		// if auth type is smtp, then set the smtp settings from constant.
		if ( 'smtp' === $this->mail['type'] ) {

			// Set the SMTP settings from constant to variables.
			$this->mail['smtp_host'] = defined( 'WS_SMTP_HOST' ) ? WS_SMTP_HOST : '';
			$this->mail['smtp_port'] = defined( 'WS_SMTP_PORT' ) ? WS_SMTP_PORT : '';
			$this->mail['smtp_auth'] = defined( 'WS_SMTP_AUTH' ) ? WS_SMTP_AUTH : '';
			$this->mail['smtp_secr'] = defined( 'WS_SMTP_SECR' ) ? WS_SMTP_SECR : '';
			if ( $this->mail['smtp_secr'] ) {
				$this->mail['smtp_user'] = defined( 'WS_SMTP_USER' ) ? WS_SMTP_USER : '';
				$this->mail['smtp_pass'] = defined( 'WS_SMTP_PASS' ) ? WS_SMTP_PASS : '';
			}
		}
		// define( 'WS_MAIL_SET', $this->mail['type'] );
	}

	/**
	 * Set SMTP Settings By Option
	 *
	 * This function is used to set the SMTP settings by option.
	 */
	public function set_mail_settings_by_option() {}

	/**
	 * Set SMTP
	 *
	 * This function is used to set the SMTP.
	 *
	 * @param object $phpmailer PHPMailer object.
	 */
	public function apply_smtp( $phpmailer ) {

		error_log( 'apply_smtp' );
		// if auth type is smtp, then set the smtp settings from constant.
		if ( 'smtp' === $this->mail['type'] ) {

			// Set the SMTP settings from constant to variables.
			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->Mailer     = $this->mail['type'];
			$phpmailer->Host       = $this->mail['smtp_host'];
			$phpmailer->Port       = $this->mail['smtp_port'];
			$phpmailer->SMTPAuth   = $this->mail['smtp_auth'];
			$phpmailer->SMTPSecure = $this->mail['smtp_secr'];
			if ( $this->mail['smtp_secr'] ) {
				$phpmailer->Username = $this->mail['smtp_user'];
				$phpmailer->Password = $this->mail['smtp_pass'];
			}
			$phpmailer->From      = $this->mail['from_email'];
			$phpmailer->FromName  = $this->mail['from_name'];
			$phpmailer->SMTPDebug = 0;
			error_log( 'in most php_init: ' . wp_json_encode( $phpmailer, 1, 4 ) );
			// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
	}

	/**
	 * Template
	 *
	 * This function is used to get the template.
	 */
	public function template() {
		
	}
}