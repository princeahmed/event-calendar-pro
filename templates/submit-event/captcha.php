<?php

$digit1    = rand( 1, 10 );
$digit2    = rand( 1, 10 );
$operators = array(
	"+",
	"-",
	"*",
);
$operator  = $operators[ array_rand( $operators ) ];

?>

<div class="ec-event-captcha">
	<div class="form-group">
		<label class="form-label" for="captcha">Capcha <?php echo "$digit1 $operator $digit2 = ?"; ?>
			<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
		<input type="text" name="captcha" id="captcha" class="form-control" value="" required>
	</div>
	<input type="hidden" name="math" value="<?php echo md5( ecp_do_math( $digit1, $digit2, $operator ) . 'secret-code' ); ?>">
</div>
