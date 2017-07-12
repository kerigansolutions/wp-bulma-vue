<?php
/**
 * Layout Walker Class
 */
class Layout_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {

	public function walk( $elements, $max_depth, $args = array() ) {
		$output = parent::walk( $elements, $max_depth, $args );
		$output = str_replace(
			array( 'type="checkbox"', "type='checkbox'" ),
			array( 'type="radio"', "type='radio'" ),
			$output
		);
		return $output;
	}

}