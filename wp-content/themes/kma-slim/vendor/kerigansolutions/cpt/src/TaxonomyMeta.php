<?php
/**
 * Created by PhpStorm.
 * User: bbair
 * Date: 9/20/2017
 * Time: 9:24 PM
 */

namespace KeriganSolutions\CPT;


class TaxonomyMeta {

    private $dir;
    public $taxonomy;
    public $term;
    public $inputLabel;
    public $inputValue;
    private $inputNonce;

    public function __construct( $taxonomy = '' ) {
        $this->dir      = dirname( __FILE__ );
        $this->taxonomy = $taxonomy;
    }

    private function uglify( $text ) {
        return strtolower( str_replace( ' ', '_', $text ) );
    }

    private function sanitizeField( $value ) {
        return sanitize_text_field( $value );
    }

    private function getTaxonomyFieldValue( $termId ) {
        $value = get_term_meta( $termId, $this->inputValue, true );
        //$value = $this->sanitizeField( $value );

        return $value;
    }

    private function createField( $label, $type ) {
        $isMulti     = ( is_array( $type ) ? true : false );
        $fieldIdName = $this->inputValue;
        $termId = $this->termId;
        $data = $this->getTaxonomyFieldValue($termId);

        if ( $isMulti ) {
            $templateFile = $this->dir . '/templates/' . $type['type'] . '.php';
        } else {
            $templateFile = $this->dir . '/templates/' . $type . '.php';
        }

        if ( file_exists( $templateFile ) ) {
            $field = file_get_contents( $templateFile );

            if ( $type != 'wysiwyg' ) {
                $field = str_replace( '{field-name}', $fieldIdName, $field );
                $field = str_replace( '{field-label}', $label, $field );
                $field = str_replace( '{field-value}', $data, $field );
            } else {
                $editor = wp_editor(
                    $data,
                    $fieldIdName,
                    [
                        'quicktags'     => [ 'buttons' => 'em,strong,link' ],
                        'textarea_name' => 'custom_meta[' . $fieldIdName . ']',
                        'tinymce'       => true
                    ]
                );
                $field  = str_replace( '{wysiwyg-editor}', $editor, $field );

            }

            if ( $type == 'boolean' ) {
                $checked = ( $data == 'on' ? 'checked' : '' );
                $field   = str_replace( '{field-checked}', $checked, $field );
            }

            if ( $type == 'date' ) {
                wp_enqueue_style( 'flatpickr-style', 'https://unpkg.com/flatpickr/dist/flatpickr.min.css' );
                wp_enqueue_script( 'flatpickr-script', 'https://unpkg.com/flatpickr', [ 'jquery' ] );
            }

            if ( $isMulti ) {
                $options = '';
                foreach ( $type['data'] as $key => $option ) {
                    $optionField = file_get_contents( $this->dir . '/templates/' . $type['type'] . '-option.php' );
                    $optionField = str_replace( '{field-name}', $fieldIdName, $optionField );
                    $optionField = str_replace( '{field-value}', $option, $optionField );

                    if ( $option == $data ) {
                        $optionField = str_replace( '{field-selected}',
                            ( $type['type'] == 'select' ? 'selected' : 'checked' ), $optionField );
                    }

                    $options .= $optionField;
                }
                $field = str_replace( '{multifields}', $options, $field );
            }

		        echo $field;

        }
    }

    private function addTaxonomyField() {
        wp_nonce_field( basename( __FILE__ ), $this->inputNonce );
        $this->createField( $this->inputLabel, $this->inputType );
    }

    private function saveTaxonomyInput() {

        $postValue = (isset( $_POST['custom_meta'][$this->inputValue] ) ? $_POST['custom_meta'][$this->inputValue] : '');
        update_term_meta( $this->termId, $this->inputValue, $postValue );

    }

    public function createTaxonomyField( $label, $type ) {
        $this->inputLabel = $label;
        $this->inputType = $type;
        $this->termId = (isset($_POST['tag_ID']) ? $_POST['tag_ID'] : (isset($_GET['tag_ID']) ? $_GET['tag_ID'] : ''));
        $this->inputValue = $this->uglify( $this->taxonomy . '_' . $label );
        $this->inputNonce = $this->uglify( $this->inputLabel . '_nonce' );

        add_action( $this->taxonomy . '_add_form_fields', function () {
            $this->addTaxonomyField();
        });
        add_action( $this->taxonomy . '_edit_form_fields', function () {
            $this->addTaxonomyField();
        });

        add_action( 'edit_' . $this->taxonomy, function () {
            $this->saveTaxonomyInput();
        } );
        add_action( 'create_' . $this->taxonomy, function () {
            $this->saveTaxonomyInput();
        } );

    }


}