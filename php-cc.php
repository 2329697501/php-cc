<?php
/**
* php-cc - RGB-HSV-HSL color converter implemented in PHP.
*
* Formulas are implemented according to the Wikipedia page.
* See the Wikipedia page for details: https://en.wikipedia.org/wiki/HSL_and_HSV
*
* @package		vkucukcakar/php-cc
* @author		Volkan Kucukcakar
* @copyright	2018 Volkan Kucukcakar
* @license		https://opensource.org/licenses/BSD-2-Clause The 2-Clause BSD License
* @version		Release: 1.0.0
* @link			https://github.com/vkucukcakar/php-cc
*
*
* php-cc - RGB-HSV-HSL color converter implemented in PHP.
* Copyright (c) 2018, Volkan Kucukcakar
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* 1. Redistributions of source code must retain the above copyright notice, this
*    list of conditions and the following disclaimer.
* 2. Redistributions in binary form must reproduce the above copyright notice,
*    this list of conditions and the following disclaimer in the documentation
*    and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
* ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace vkucukcakar\php_cc;

class php_cc {

	/**
	* RGB to HSV, high precision
	*
	* @param float $r Red [0-1]
	* @param float $g Green [0-1]
	* @param float $b Blue [0-1]
	* @return array of float HSV [0-360, 0-1, 0-1]
	*/
	static function rgb2hsv( $r, $g, $b ){
		// Validation
		$r = ( $r < 0 ) ? 0 : ( ( $r > 1 ) ? 1 : $r );
		$g = ( $g < 0 ) ? 0 : ( ( $g > 1 ) ? 1 : $g );
		$b = ( $b < 0 ) ? 0 : ( ( $b > 1 ) ? 1 : $b );
		// Formula
		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		switch ( $max ){
			case ( $min ):
				$h = 0;
				break;
			case ( $r ):
				$h = 60 * ( ( $g - $b ) / ( $max - $min ) );
				break;
			case ( $g ):
				$h = 60 * ( 2 + ( $b - $r ) / ( $max - $min ) );
				break;
			case ( $b ):
				$h = 60 * ( 4 + ( $r - $g ) / ( $max - $min ) );
				break;
		}
		if ( $h < 0 ){
			$h += 360;
		}
		$s = ( $max == 0 && $r == 0 && $g == 0 && $b == 0 ) ? 0 : ( $max - $min ) / $max;
		$hsv = array( $h, $s, $max );
		return $hsv;
	}// function

	/**
	* RGB to HSV, using integers
	*
	* @param int $r Red [0-255]
	* @param int $g Green [0-255]
	* @param int $b Blue [0-255]
	* @return array of int HSV [0-360, 0-255, 0-255]
	*/
	static function rgb2hsv_int( $r, $g, $b ){
		$result = self::rgb2hsv( (int) $r / 255, (int) $g / 255, (int) $b / 255 );
		return array( (int) round( $result[0] ), (int) round( $result[1] * 100 ), (int) round( $result[2] * 100 ) );	
	}// function

	/**
	* HSV to RGB, high precision
	*
	* @param float $h Hue [0-360]
	* @param float $s Saturation [0-1]
	* @param float $v Value [0-1]
	* @return array of float RGB [0-1, 0-1, 0-1]
	*/
	static function hsv2rgb( $h, $s, $v ){
		// Validation
		$h = ( $h < 0 ) ? 0 : ( ( $h > 360 ) ? 360 : $h );
		$s = ( $s < 0 ) ? 0 : ( ( $s > 1 ) ? 1 : $s );
		$v = ( $v < 0 ) ? 0 : ( ( $v > 1 ) ? 1 : $v );
		// Formula
		$c = $v * $s;
		$hp = (float) $h / 60;
		$x = $c * ( 1 - abs( fmod( $hp, 2 ) - 1 ) );
		$m = $v - $c;
		switch ( true ){
			case ( $hp <= 1 ):
				$rgb = array( $c + $m, $x + $m, $m );
				break;
			case ( $hp <= 2 ):
				$rgb = array( $x + $m, $c + $m, $m );
				break;
			case ( $hp <= 3 ):
				$rgb = array( $m, $c + $m, $x + $m );
				break;
			case ( $hp <= 4 ):
				$rgb = array( $m, $x + $m, $c + $m );
				break;
			case ( $hp <= 5 ):
				$rgb = array( $x + $m, $m, $c + $m );
				break;
			case ( $hp <= 6 ):
				$rgb = array( $c + $m, $m, $x + $m );
				break;
		}
		return $rgb;
	}// function

	/**
	* HSV to RGB, using integers
	*
	* @param int $h Hue [0-360]
	* @param int $s Saturation [0-100]
	* @param int $v Value [0-100]
	* @return array of int RGB [0-255, 0-255, 0-255]
	*/
	static function hsv2rgb_int( $h, $s, $v ){
		$result = self::hsv2rgb( (int) $h, (int) $s / 100, (int) $v / 100 );
		return array( (int) round( $result[0] * 255 ), (int) round( $result[1] * 255 ), (int) round( $result[2] * 255 ) );	
	}// function

	
	/**
	* RGB to HSL, high precision
	*
	* @param float $r Red (0-1)
	* @param float $g Green (0-1)
	* @param float $b Blue (0-1)
	* @return array of float HSL (0-360, 0-1, 0-1)
	*/
	static function rgb2hsl( $r, $g, $b ){
		// Validation
		$r = ( $r < 0 ) ? 0 : ( ( $r > 1 ) ? 1 : $r );
		$g = ( $g < 0 ) ? 0 : ( ( $g > 1 ) ? 1 : $g );
		$b = ( $b < 0 ) ? 0 : ( ( $b > 1 ) ? 1 : $b );
		// Formula
		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		switch ( $max ){
			case ( $min ):
				$h = 0;
				break;
			case ( $r ):
				$h = 60 * ( ( $g - $b ) / ( $max - $min ) );
				break;
			case ( $g ):
				$h = 60 * ( 2 + ( $b - $r ) / ( $max - $min ) );
				break;
			case ( $b ):
				$h = 60 * ( 4 + ( $r - $g ) / ( $max - $min ) );
				break;
		}
		if ( $h < 0 ){
			$h += 360;
		}
		$s = ( ( $max == 0 && $r == 0 && $g == 0 && $b == 0 ) || ( $max == 1 && $r == 1 && $g == 1 && $b == 1 ) ) ? 0 : ( $max - $min ) / ( 1 - abs( $max + $min - 1 ) );
		$l = ( $max + $min ) / 2;
		$hsl = array( $h, $s, $l );
		return $hsl;
	}// function

	/**
	* RGB to HSL, using integers
	*
	* @param int $r Red (0-255)
	* @param int $g Green (0-255)
	* @param int $b Blue (0-255)
	* @return array of int HSL (0-360, 0-100, 0-100)
	*/
	static function rgb2hsl_int( $r, $g, $b ){
		$result = self::rgb2hsl( (int) $r / 255, (int) $g / 255, (int) $b / 255 );
		return array( (int) round( $result[0] ), (int) round( $result[1] * 100 ), (int) round( $result[2] * 100 ) );			
	}// function
	
	/**
	* HSL to RGB, high precision
	*
	* @param float $h Hue (0-360)
	* @param float $s Saturation (0-1)
	* @param float $l Lightness (0-1)
	* @return array of int RGB (0-1, 0-1, 0-1)
	*/
	static function hsl2rgb( $h, $s, $l ){
		// Validation
		$h = ( $h < 0 ) ? 0 : ( ( $h > 360 ) ? 360 : $h );
		$s = ( $s < 0 ) ? 0 : ( ( $s > 1 ) ? 1 : $s );
		$v = ( $l < 0 ) ? 0 : ( ( $l > 1 ) ? 1 : $l );
		// Formula
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$hp = (float) $h / 60;
		$x = $c * ( 1 - abs( fmod( $hp, 2 ) - 1 ) );
		$m = $l - $c / 2;
		switch ( true ){
			case ( $hp <= 1 ):
				$rgb = array( $c + $m, $x + $m, $m );
				break;
			case ( $hp <= 2 ):
				$rgb = array( $x + $m , $c + $m, $m );
				break;
			case ( $hp <= 3 ):
				$rgb = array( $m, $c + $m, $x + $m );
				break;
			case ( $hp <= 4 ):
				$rgb = array( $m, $x + $m, $c + $m );
				break;
			case ( $hp <= 5 ):
				$rgb = array( $x + $m, $m, $c + $m );
				break;
			case ( $hp <= 6 ):
				$rgb = array( $c + $m, $m, $x + $m );
				break;
		}
		return $rgb;
	}// function

	/**
	* HSL to RGB, using integers
	*
	* @param int $h Hue (0-360)
	* @param int $s Saturation (0-100)
	* @param int $l Lightness (0-100)
	* @return array of int RGB (0-255, 0-255, 0-255)
	*/
	static function hsl2rgb_int( $h, $s, $l ){
		$result = self::hsl2rgb( (int) $h, (int) $s / 100, (int) $l / 100 );
		return array( (int) round( $result[0] * 255 ), (int) round( $result[1] * 255 ), (int) round( $result[2] * 255 ) );	
	}// function

}// class
