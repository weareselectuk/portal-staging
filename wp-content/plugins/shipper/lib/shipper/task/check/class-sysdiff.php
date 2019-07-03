<?php
/**
 * Shipper tasks: Systems difference check
 *
 * Checks the other system for possibly offending differences.
 *
 * @package shipper
 */

/**
 * Systems differences check class
 */
class Shipper_Task_Check_Sysdiff extends Shipper_Task_Check {

	const ERR_BLOCKING = 'issue_blocking';
	const ERR_WARNING = 'issue_warning';

	/**
	 * Runs the diff checks suite.
	 *
	 * @param array $remote Other system info, as created by Shipper_Model_System::get_data on the other end.
	 *
	 * @return bool
	 */
	public function apply( $remote = array() ) {
		if ( empty( $remote ) ) {
			$this->add_error(
				self::ERR_BLOCKING,
				__( 'No remote data to process', 'shipper' )
			);
			return false;
		}

		$model = new Shipper_Model_System;
		$local = $model->get_data();

		foreach ( $remote as $section => $info ) {
			if ( ! is_array( $info ) ) {
				$this->add_error(
					self::ERR_BLOCKING,
					sprintf( __( 'Invalid remote data for section %s', 'shipper' ), $section )
				);
				return false;
			}
			if ( ! isset( $local[ $section ] ) ) {
				$this->add_error(
					self::ERR_BLOCKING,
					sprintf( __( 'Invalid local data for section %s', 'shipper' ), $section )
				);
				return false;
			}
			foreach ( $info as $key => $value ) {
				if ( ! isset( $local[ $section ][ $key ] ) ) {
					$this->add_error(
						self::ERR_BLOCKING,
						sprintf(
							__( 'Invalid local data for section %1$s, key %2$s', 'shipper' ),
							$section, $key
						)
					);
					return false;
				}

				$check = strtolower( "{$section}_{$key}" );
				$method = "is_{$check}_diff_acceptable";

				if ( is_callable( array( $this, $method ) ) ) {
					$check = call_user_func(
						array( $this, $method ),
						$value, $local[ $section ][ $key ]
					);
					$this->add_check( $check );
				}

				if ( shipper_has_error( self::ERR_BLOCKING, $this->get_errors() ) ) {
					// We encountered a blocking issue, no need to check further.
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Checks for remote password protection
	 *
	 * @param bool $remote Remote password protection status.
	 * @param bool $local Local password protection status.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_server_access_protected_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Password Protection', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( $remote ) {
			$status = Shipper_Model_Check::STATUS_ERROR;
			$check = $this->set_check_message(
				$check,
				join( ' ', array(
					__( 'Destination is password protected.', 'shipper' ),
					__( 'This can prevent migration from working properly.', 'shipper' ),
					__( 'Please, make sure you disable password protection.', 'shipper' ),
				) )
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks for significant PHP major version differences
	 *
	 * @param int $remote Remote PHP major version.
	 * @param int $local Local PHP major version.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_php_version_major_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'PHP Version', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( $remote !== $local ) {
			$status = Shipper_Model_Check::STATUS_WARNING;
			$check->set( 'title', __( 'PHP Version Difference', 'shipper' ) );
			$check->set(
				'message',
				'<p>' . join(' ', array(
					sprintf( __( 'Your local PHP install major version is %1$d, while your remote setup runs on PHP v%2$d.', 'shipper' ), $local, $remote ),
					__( 'While this might not be significant, having major version differences <i>can</i> cause issues on your destination site.', 'shipper' ),
				)) . '</p>'
			);
		} else {
			$check->set(
				'message',
				'<p>' . 
					__('Major PHP versions on your source and destination servers do not differ significantly.', 'shipper') .
				'</p>'
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks for significant MySQL version differences
	 *
	 * @param string $remote Remote MySQL version string.
	 * @param string $local Local MySQL version string.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_mysql_version_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'MySQL Version', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( version_compare( $this->get_normalized_version( $remote ), $this->get_normalized_version( $local ), 'ne' ) ) {
			$status = Shipper_Model_Check::STATUS_WARNING;
			$check->set( 'title', __( 'MySQL Version Difference', 'shipper' ) );
			$check->set(
				'message',
				'<p>' . join(' ', array(
					sprintf( __( 'Your local MySQL version is %1$s, while your remote system uses %2$s', 'shipper' ), $local, $remote ),
					__( 'While this might not be significant, having big version differences <i>can</i> cause issues on your destination site.', 'shipper' ),
				)) . '</p>'
			);
		} else {
			$check->set(
				'message',
				'<p>' . sprintf(
					__('MySQL version is %s on this server, which is fine to migrate.', 'shipper'),
					$remote
				) . '</p>'
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks for MySQL charset differences
	 *
	 * @param string $remote Remote MySQL charset string.
	 * @param string $local Local MySQL charset string.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_mysql_charset_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Database Charset', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( strtolower( $remote ) !== strtolower( $local ) ) {
			$status = Shipper_Model_Check::STATUS_WARNING;
			$check->set( 'title', __( 'Database Charsets Difference', 'shipper' ) );
			$check->set(
				'message',
				'<p>' . join(' ', array(
					sprintf( __( 'Your local database uses %1$s charset, while your remote system uses %2$s.', 'shipper' ), $local, $remote ),
					__( 'This may cause issues with your migrated content.', 'shipper' ),
				)) . '</p>'
			);
		} else {
			$check->set(
				'message',
				'<p>' . sprintf(
					__('Charset is %s on this server, which is fine to migrate.', 'shipper'),
					$remote
				) . '</p>'
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks for significant server type differences.
	 *
	 * @param string $remote Remote server type.
	 * @param string $local Local server type.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_server_type_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Server', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( $remote !== $local ) {
			$status = Shipper_Model_Check::STATUS_WARNING;
			$check->set( 'title', __( 'Server Type Difference', 'shipper' ) );
			$check->set(
				'message',
				'<p>' . join(' ', array(
					sprintf(
						__( 'Your local server is %1$s, while your remote system uses %2$s.', 'shipper' ),
						Shipper_Model_System_Server::get_type_name( $local ),
						Shipper_Model_System_Server::get_type_name( $remote )
					),
					__( 'This may cause issues with your migrated server setup.', 'shipper' ),
				)) . '</p>'
			);
		} else {
			$check->set(
				'message',
				'<p>' . sprintf(
					__('Remote server is %s, which is fine to migrate.', 'shipper'),
					$remote
				) . '</p>'
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks server operating systems for significant differences
	 *
	 * @param string $remote Remote operating system.
	 * @param string $local Local operating system.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_server_os_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Server OS', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( strtolower( $remote ) !== strtolower( $local ) ) {
			$status = Shipper_Model_Check::STATUS_WARNING;
			$check->set( 'title', __( 'Server Operating System Difference', 'shipper' ) );
			$check->set(
				'message',
				'<p>' . join(' ', array(
					sprintf( __( 'Your local OS is %1$s, but your remote system runs %2$s.', 'shipper' ), $local, $remote ),
					__( 'This may cause issues with hardcoded paths and system libraries.', 'shipper' ),
				)) . '</p>'
			);
		} else {
			$check->set(
				'message',
				'<p>' . sprintf(
					__('Remote operating system is %s, which is fine to migrate.', 'shipper'),
					$remote
				) . '</p>'
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Checks if multisite setups are compatible
	 *
	 * @param bool $remote Whether remote WP is a network install.
	 * @param bool $local Whether local WP is a network install.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_wordpress_multisite_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Multisite', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;

		if ( (int) $remote !== (int) $local ) {
			$status = Shipper_Model_Check::STATUS_ERROR;
			$check->set( 'title', __( 'Multisite Setups Difference', 'shipper' ) );
			$msg = is_multisite()
				? __('Your source is multisite, but the destination is a single site installation.', 'shipper')
				: __('Your source is single site, but the destination is a multisite installation.', 'shipper');
			$msg .= ' ' .
				__('Both should have the same installation type for migration to be succesful.', 'shipper');
			$check = $this->set_check_message( $check, $msg );
		} else {
			$msg = is_multisite()
				? __('Both the source and destination are a multisite installation.', 'shipper')
				: __('Both the source and destination are a single site installation.', 'shipper');
			$check = $this->set_check_message( $check, $msg );
		}

		return $check->complete( $status );
	}

	/**
	 * Checks if multisite subdomain setups are compatible
	 *
	 * @param bool $remote Whether remote WP is a subdomain multisite install.
	 * @param bool $local Whether local WP is a subdomain multisite install.
	 *
	 * @return object Shipper_Model_Check instance
	 */
	public function is_wordpress_subdomain_install_diff_acceptable( $remote, $local ) {
		$check = new Shipper_Model_Check( __( 'Subdomain Setup', 'shipper' ) );
		$status = Shipper_Model_Check::STATUS_OK;
		$model = new Shipper_Model_System_Wp;
		$is_subdomain = Shipper_Model_System_Wp::SUBDOMAIN === $model->get_define( Shipper_Model_System_Wp::SUBDOMAIN );
		$type = $is_subdomain
			? __('Subdomain', 'shipper')
			: __('Subdirectory', 'shipper');

		if ( (int) $remote !== (int) $local ) {
			$status = Shipper_Model_Check::STATUS_ERROR;
			$check->set( 'title', __( 'Multisite Subdomain Setups Difference', 'shipper' ) );
			$other_type = $is_subdomain
				? __('Subdirectory', 'shipper')
				: __('Subdomain', 'shipper');
			$check = $this->set_check_message(
				$check,
				sprintf(
					__('Your source network is using the %1$s addresses while the destination network is using %2$s address pattern. Shipper requires both the networks to use the same address pattern.', 'shipper'),
					$type, $other_type
				)
			);
		} else if ( is_multisite() ) {
			$check = $this->set_check_message(
				$check,
				sprintf(
					__('Both the source and destination have the same pattern for their site address, i.e. %s', 'shipper'),
					$type
				)
			);
		}

		return $check->complete( $status );
	}

	/**
	 * Normalizes version strings to point-separated integers
	 *
	 * @param string $version Raw version to normalize.
	 * @param int    $precision Optional number of point-separated values.
	 *
	 * @return string
	 */
	public function get_normalized_version( $version, $precision = 2 ) {
		$ver = array_map( 'intval', explode( '.', $version, $precision + 1 ) );
		if ( count( $ver ) > $precision ) { array_pop( $ver ); }
		return join( '.', $ver );

	}

}