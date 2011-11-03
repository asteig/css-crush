<?php
/**
 * HSL shim
 * Converts HSL values into hex code that works in all browsers
 * 
 * @before
 *     color: hsl( 100, 50%, 50% )
 * 
 * @after
 *    color: #6abf40
 */

CssCrush_Hook::add( 'rule_postalias', 'csscrush_hsl' );

function csscrush_hsl ( CssCrush_Rule $rule ) {
	foreach ( $rule as &$declaration ) {
		if ( !empty( $declaration->functions ) and in_array( 'hsl', $declaration->functions ) ) {
			while ( preg_match( '!hsl(___p\d+___)!', $declaration->value, $m ) ) {
				$full_match = $m[0];
				$token = $m[1];
				$hsl = trim( $rule->parens[ $token ], '()' );
				$hsl = array_map( 'trim', explode( ',', $hsl ) );
				$hex = CssCrush_Color::cssHslToHex( $hsl );
				$declaration->value = str_replace( $full_match, $hex, $declaration->value );
			}
		}
	}
}