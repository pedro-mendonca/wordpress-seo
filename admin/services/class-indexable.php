<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Admin\Services
 */

/**
 * Represents the indexable service.
 */
class WPSEO_Indexable_Service {

	/**
	 * Retrieves an indexable.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_indexable( WP_REST_Request $request ) {

		$object_type = $request->get_param( 'object_type' );
		$provider = $this->get_provider( $object_type );

		if ( $provider === null ) {
			return new WP_REST_Response( 'Unknown type ' . $object_type, 404 );
		}

		$object_id = $request->get_param( 'object_id' );
		if ( ! $provider->is_indexable( $object_id ) ) {
			return new WP_REST_Response( 'Object with id ' . $object_id . ' not found', 404 );

		}

		return new WP_REST_Response( $provider->get( $object_id ) );
	}

	/**
	 * Returns a provider based on the given object type.
	 *
	 * @param string $object_type The object type to get the provider for.
	 *
	 * @return null|WPSEO_Indexable_Service_Provider Instance of the service provider.
	 */
	protected function get_provider( $object_type ) {
		if ( $object_type === 'post' ) {
			return new WPSEO_Indexable_Service_Post_Provider();
		}

		if ( $object_type === 'term' ) {
			return new WPSEO_Indexable_Service_Term_Provider();

		}

		return null;
	}
}
