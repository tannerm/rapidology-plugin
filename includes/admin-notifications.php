<?php

FLM_Admin_Notifications::get_instance();
class FLM_Admin_Notifications {

	/**
	 * @var
	 */
	protected static $_instance;

	protected static $_key = 'flm_admin_message';

	protected static $_hook = 'flm_get_message';

	/**
	 * Only make one instance of the FLM_Admin_Notifications
	 *
	 * @return FLM_Admin_Notifications
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof FLM_Admin_Notifications ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'admin_notices', array( $this, 'notice' ) );
//		add_action( 'admin_notices', array( $this, 'optin'  ) );
		add_action( 'admin_init',    array( $this, 'schedule_get_message' ) );
		add_action( self::$_hook,    array( $this, 'retrieve_message' ) );
		add_action( 'wp_ajax_flm_training_optin', array( $this, 'opted_in' ) );
	}

	/**
	 * Print the notice if it exists
	 */
	public function notice() {

		if ( empty( $_GET['page'] ) || 'flm_options' != $_GET['page'] ) {
			return;
		}

		if ( ! $message = self::get_message() ) {
			return;
		}

		$allowed_elements = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		?>
		<div class="notice notice-info">
			<p><?php echo wp_kses( stripslashes( $message ), $allowed_elements ); ?></p>
		</div>
		<?php

	}

	public static function get_message() {
		return get_option( self::$_key );
	}

	public function schedule_get_message() {
		if ( ! wp_next_scheduled( self::$_hook ) ) {
			wp_schedule_event( time(), 'daily', self::$_hook );
		}
	}

	/**
	 * Retrieve the message from freelistmachine.com
	 */
	public function retrieve_message() {
		$message = wp_safe_remote_get( 'http://freelistmachine.com/plugin-notification.txt' );

		if ( 200 != wp_remote_retrieve_response_code( $message ) ) {
			$message = '';
		} else {
			$message = wp_remote_retrieve_body( $message );
		}

		update_option( self::$_key, wp_filter_post_kses( $message ), 'no' );
	}

	/**
	 * Show optin for users to sign up for mailing list
	 */
	public function optin() {
		if ( empty( $_GET['page'] ) || 'flm_options' != $_GET['page'] ) {
			return;
		}

		if ( get_option( 'flm_opted_in' ) ) {
			return;
		}

		$user = wp_get_current_user();
		?>
		<div class="notice notice-info">
			<br />
			<h3>FREE TRAINING: 4 Keys For a Profitable Email List</h3>
			<p>Discover the highest converting points on your website, how to build rapport with your prospects, create demand and convert prospects into paying customers.</p>
			<form accept-charset="UTF-8" action="https://xm113.infusionsoft.com/app/form/process/8bc1bc7bd22366286a4b49ad454e0c12" class="infusion-form flm-training-optin" method="POST">
				<input name="inf_form_xid" type="hidden" value="8bc1bc7bd22366286a4b49ad454e0c12" />
				<input name="inf_form_name" type="hidden" value="4 Keys For a Profitable Email List - Training" />
				<input name="infusionsoft_version" type="hidden" value="1.46.0.42" />
				<div class="infusion-field">
					<label for="inf_field_FirstName">First Name: </label>
					<input class="infusion-field-input-container" id="inf_field_FirstName" name="inf_field_FirstName" value="<?php echo esc_attr( $user->first_name ); ?>" type="text" />
					<label for="inf_field_Email"> Email: </label>
					<input class="infusion-field-input-container" id="inf_field_Email" name="inf_field_Email" value="<?php echo esc_attr( $user->user_email ); ?>" type="text" />
					&nbsp;<input type="submit" value="Submit" />
				</div>
			</form>
			<script type="text/javascript" src="https://xm113.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=70cd5be5b0e965cf816f74156ff25359"></script>
			<br />
		</div>
		<?php
	}

	/**
	 * This user has opted in, store it so we don't keep buggin them.
	 */
	public function opted_in() {
		update_option( 'flm_opted_in', true, 'no' );
	}

}