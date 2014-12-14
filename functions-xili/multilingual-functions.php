<?php
/*
* Multilingual functions used in most themes
*/
function xili_get_adjacent_post_query_args( $query_args, $args ){
		$current_post = get_post( $args['post'] );
		$curlang = xiliml_get_lang_object_of_post( $current_post->ID ); error_log('prev tests');

		if ( $curlang ) { // only when language is defined !
			if ( isset ( $query_args['tax_query'] )) {
				$query_args['tax_query'][] =
				array(
					'field'		=> 'slug',
					'taxonomy'	=> TAXONAME, // language
					'terms'		=> $curlang->slug,
				);
				$query_args['tax_query']['relation'] = 'AND';

			} else {
				$query_args['tax_query'] = array(
				array(
					'field'		=> 'slug',
					'taxonomy'	=> TAXONAME, // language
					'terms'		=> $curlang->slug,
				),
			);
			}
		}
		return $query_args;
	}

//add_filter( 'get_adjacent_post_query_args', 'xili_get_adjacent_post_query_args', 10, 2 ); // obsolete

function xiliml_adjacent_join_filter( $join, $in_same_cat, $excluded_categories ) {
		global $post, $wpdb;
		$curlang = xiliml_get_lang_object_of_post( $post->ID );

		if ( $curlang ) { // only when language is defined !
			$join .= " LEFT JOIN $wpdb->term_relationships as xtr ON (p.ID = xtr.object_id) LEFT JOIN $wpdb->term_taxonomy as xtt ON (xtr.term_taxonomy_id = xtt.term_taxonomy_id) ";
		}
		return $join;
	}

function xiliml_adjacent_where_filter( $where, $in_same_cat, $excluded_categories ) {
		global $post;
		$curlang = xiliml_get_lang_object_of_post( $post->ID );
		if ( $curlang ) {
			$wherereqtag = $curlang->term_id;
			$where .= " AND xtt.taxonomy = '".TAXONAME."' ";
			$where .= " AND xtt.term_id = $wherereqtag ";
		}
		return $where;
	}

	// other conditions can limit filtering
if ( class_exists( 'xili_language' ) && is_xili_adjacent_filterable() ) {

		add_filter( 'get_next_post_join', 'xiliml_adjacent_join_filter', 10, 3);
		add_filter( 'get_previous_post_join', 'xiliml_adjacent_join_filter', 10, 3);

		add_filter( 'get_next_post_where', 'xiliml_adjacent_where_filter', 10,3);
		add_filter( 'get_previous_post_where', 'xiliml_adjacent_where_filter', 10, 3);

}

?>