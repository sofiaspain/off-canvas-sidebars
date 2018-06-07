<?php
/**
 * Off-Canvas Sidebars - Class Tab_Shortcode
 *
 * @author  Jory Hogeveen <info@keraweb.nl>
 * @package Off_Canvas_Sidebars
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Off-Canvas Sidebars plugin tab shortcode
 *
 * @author  Jory Hogeveen <info@keraweb.nl>
 * @package Off_Canvas_Sidebars
 * @since   0.5.0
 * @version 0.5.0
 * @uses    \OCS_Off_Canvas_Sidebars_Tab Extends class
 */
final class OCS_Off_Canvas_Sidebars_Tab_Shortcode extends OCS_Off_Canvas_Sidebars_Tab
{
	/**
	 * The single instance of the class.
	 *
	 * @var    \OCS_Off_Canvas_Sidebars_Tab_Shortcode
	 * @since  0.3.0
	 */
	protected static $_instance = null;

	/**
	 * Class constructor.
	 * @since   0.1.0
	 * @since   0.3.0  Private constructor.
	 * @since   0.5.0  Protected constructor. Refactor into separate tab classes and methods.
	 * @access  protected
	 */
	protected function __construct() {
		$this->tab = 'ocs-shortcode';
		$this->name = esc_attr__( 'Shortcode', OCS_DOMAIN );
		parent::__construct();
	}

	/**
	 * Initialize this tab.
	 * @since   1.5.0
	 */
	public function init() {
		add_filter( 'ocs_page_form_do_submit', '__return_false' );
		add_filter( 'ocs_page_form_do_settings_fields', '__return_false' );
		add_filter( 'ocs_page_form_do_sections', '__return_false' );
		add_action( 'ocs_page_form', array( $this, 'tab_content' ) );
	}

	/**
	 * Register settings.
	 * @since   0.1.0
	 * @since   0.5.0  Refactor into separate tab classes and methods
	 */
	public function register_settings() {
		//parent::register_settings();

		do_action( 'off_canvas_sidebar_settings_' . $this->filter );
	}

	/**
	 * Tab content.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 * @todo Refactor to enable above checks?
	 *
	 * @since   0.5.0
	 */
	public function tab_content() {

		?>
		<div id="section_shortcode" class="stuffbox postbox">
			<h3 class="hndle"><span><?php esc_html_e( 'Shortcode', OCS_DOMAIN ); ?>:</span></h3>
			<div class="inside">
				<textarea id="ocs_shortcode" class="widefat">[ocs_trigger id=""]</textarea>
			</div></div>
		<?php

		echo '<div id="section_shortcode_options" class="stuffbox postbox postbox postbox-third first">';

		echo '<h3 class="hndle"><span>' . __( 'Basic options', OCS_DOMAIN ) . ':</span></h3>';

		echo '<div class="inside"><table class="form-table">';
		echo '<tr><td>';

		$sidebar_select = array(
			array(
				'value' => '',
				'label' => '-- ' . __( 'select', OCS_DOMAIN ) . ' --',
			),
		);
		foreach ( (array) off_canvas_sidebars()->get_sidebars() as $sidebar_id => $sidebar_data ) {
			$sidebar_select[] = array(
				'value' => $sidebar_id,
				'label' => $sidebar_data['label'],
			);
		}
		OCS_Off_Canvas_Sidebars_Form::select_option( array(
			'name' => 'id',
			'label' => __( 'Sidebar ID', OCS_DOMAIN ),
			'description' => __( '(Required) The off-canvas sidebar ID', OCS_DOMAIN ),
			'options' => $sidebar_select,
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::text_option( array(
			'name'        => 'text',
			'label'       => __( 'Text', OCS_DOMAIN ),
			'value'       => '',
			'class'       => 'widefat',
			'description' => __( 'Limited HTML allowed', OCS_DOMAIN ),
			'multiline'   => true,
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::text_option( array(
			'name'        => 'icon',
			'label'       => __( 'Icon', OCS_DOMAIN ),
			'value'       => '',
			'class'       => 'widefat',
			// Translators: %s stands for <code>dashicons</code>.
			'description' => __( 'The icon classes.', OCS_DOMAIN ) . ' ' . sprintf( __( 'Do not forget the base icon class like %s', OCS_DOMAIN ), '<code>dashicons</code>' ),
			'multiline'   => false,
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::select_option( array(
			'name' => 'icon_location',
			'label' => __( 'Icon location', OCS_DOMAIN ),
			'options' => array(
				array(
					'label' => __( 'Before', OCS_DOMAIN ) . ' (' . __( 'Default', OCS_DOMAIN ) . ')',
					'value' => '',
				),
				array(
					'label' => __( 'After', OCS_DOMAIN ),
					'value' => 'after',
				),
			),
		) );

		echo '</td></tr>';
		echo '</table></div></div>';

		echo '<div id="section_shortcode_optionaloptions" class="stuffbox postbox postbox postbox-third">';

		echo '<h3 class="hndle"><span>' . __( 'Advanced options', OCS_DOMAIN ) . ':</span></h3>';

		echo '<div class="inside"><table class="form-table">';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::select_option( array(
			'name' => 'action',
			'label' => __( 'Trigger action', OCS_DOMAIN ),
			'options' => array(
				array(
					'label' => __( 'Toggle', OCS_DOMAIN ) . ' (' . __( 'Default', OCS_DOMAIN ) . ')',
					'value' => '',
				),
				array(
					'label' => __( 'Open', OCS_DOMAIN ),
					'value' => 'open',
				),
				array(
					'label' => __( 'Close', OCS_DOMAIN ),
					'value' => 'close',
				),
			),
			//'tooltip' => __( 'The trigger action. Default: toggle', OCS_DOMAIN ),
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		$elements = array( 'button', 'span', 'a', 'b', 'strong', 'i', 'em', 'img', 'div' );
		$element_values = array();
		$element_values[] = array(
			'value' => '',
			'label' => ' - ' . __( 'Select', OCS_DOMAIN ) . ' - ',
		);
		foreach ( $elements as $e ) {
			$element_values[] = array(
				'value' => $e,
				'label' => '' . $e . '',
			);
		}
		OCS_Off_Canvas_Sidebars_Form::select_option( array(
			'name' => 'element',
			'label' => __( 'HTML element', OCS_DOMAIN ),
			'options' => $element_values,
			'description' => __( 'Choose wisely', OCS_DOMAIN ) . '. ' . __( 'Default', OCS_DOMAIN ) . ': <code>button</code>',
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::text_option( array(
			'name' => 'class',
			'label' => __( 'Extra classes', OCS_DOMAIN ),
			'value' => '',
			'class' => 'widefat',
			'description' => __( 'Separate multiple classes with a space', OCS_DOMAIN ),
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::text_option( array(
			'name' => 'attr',
			'label' => __( 'Custom attributes', OCS_DOMAIN ),
			'value' => '',
			'class' => 'widefat',
			'description' => __( 'key : value ; key : value', OCS_DOMAIN ),
			'multiline' => true,
		) );

		echo '</td></tr>';
		echo '<tr><td>';

		OCS_Off_Canvas_Sidebars_Form::checkbox_option( array(
			'name' => 'nested',
			'label' => __( 'Nested shortcode', OCS_DOMAIN ) . '?',
			'value' => '',
			'description' => __( '[ocs_trigger text="Your text"] or [ocs_trigger]Your text[/ocs_trigger]', OCS_DOMAIN ),
		) );

		echo '</td></tr>';

		echo '</table></div></div>';
		?>
		<div id="section_shortcode_preview" class="stuffbox postbox postbox-third">
			<h3 class="hndle"><span><?php esc_html_e( 'Preview', OCS_DOMAIN ); ?>:</span></h3>
			<div class="inside">
				<div id="ocs_shortcode_preview"></div>
			</div>
			<h3 class="hndle"><span>HTML:</span></h3>
			<div class="inside">
				<textarea id="ocs_shortcode_html" class="widefat"></textarea>
			</div></div>
		<?php
	}

	/**
	 * Class Instance.
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @since   0.3.0
	 * @static
	 * @return  \OCS_Off_Canvas_Sidebars_Tab_Shortcode
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

} // End class().