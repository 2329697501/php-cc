# php_cc

php_cc - RGB-HSV-HSL color converter implemented in PHP.

Formulas are implemented according to the Wikipedia page. 
See the Wikipedia page for details: https://en.wikipedia.org/wiki/HSL_and_HSV


## Usage

* Alias the php_cc class

	use vkucukcakar\php_cc\php_cc as php_cc;

* RGB to HSV, high precision. $r, $g, $b are between 0 and 1.

	$hsv = php_cc::rgb2hsv( $r, $g, $b );

* RGB to HSV, using integers. $r, $g, $b are between 0 and 255.

	$hsv = php_cc::rgb2hsv_int( $r, $g, $b );

* HSV to RGB, high precision. $h is between 0 and 360, $s and $v are between 0 and 1.

	$rgb = php_cc::hsv2rgb( $h, $s, $v );
	
* HSV to RGB, using integers. $h is between 0 and 360, $s and $v are between 0 and 100.

	$rgb = php_cc::hsv2rgb_int( $h, $s, $v );
	
* RGB to HSL, high precision. $r, $g, $b are between 0 and 1.

	$hsl = php_cc::rgb2hsl( $r, $g, $b );
	
* RGB to HSL, using integers. $r, $g, $b are between 0 and 255.

	$hsl = php_cc::rgb2hsl_int( $r, $g, $b );
	
* HSL to RGB, high precision. $h is between 0 and 360, $s and $l are between 0 and 1.

	$rgb = php_cc::hsl2rgb( $h, $s, $l );
	
* HSL to RGB, using integers. $h is between 0 and 360, $s and $l are between 0 and 100.

	$rgb = php_cc::hsl2rgb_int( $h, $s, $l );
