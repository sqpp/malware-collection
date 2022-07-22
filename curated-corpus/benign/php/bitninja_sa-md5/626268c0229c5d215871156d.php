<?php

namespace ADP\BaseVersion\Includes\External\AdminPage\Interfaces;

use ADP\BaseVersion\Includes\Context;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

interface AdminTabInterface {
	/**
	 * AdminTabInterface constructor.
	 *
	 * @param Context $context
	 */
	public function __construct( $context );

	public function handle_submit_action();
	public function register_ajax();
	public function enqueue_scripts();

	/**
	 * @return array
	 */
	public function get_view_variables();

	/**
	 * Display priority in the header
	 *
	 * @return integer
	 */
	public static function get_header_display_priority();

	/**
	 * @return string
	 */
	public static function get_relative_view_path();

	/**
	 * Unique tab key
	 *
	 * @return string
	 */
	public static function get_key();

	/**
	 * Localized title
	 *
	 * @return string
	 */
	public static function get_title();
}
