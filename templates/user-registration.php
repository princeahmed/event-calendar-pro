<?php
/**
 * User Authentication check during event submit
 */
$digit1    = rand( 1, 10 );
$digit2    = rand( 1, 10 );
$operators = array(
	"+",
	"-",
	"*",
);
$operator  = $operators[ array_rand( $operators ) ];

get_header();

?>

<div class="ecp-login-form">

	<div class="form-heading">
		<?php include WPECP_TEMPLATES_DIR . '/submit-event/message.php'; ?>
		<h3>Login</h3>
		<span>Please login to access your account</span>
	</div>
	<!-- New -->
	<?php

	$submit_page_url = ecp_get_page_url( 'event_submit_page' );
	wp_login_form( array( 'remember'       => false,
	                      'redirect'       => apply_filters( 'ecp_login_redirect', $submit_page_url ),
	                      'label_username' => 'Email Address'
	) );
	echo apply_filters( 'ecp_user_reset_pass_text', '<p><a class="reset-password-link" href="' . wp_lostpassword_url() . '">Click here to reset password</a></p>' );
	?>

</div>

<div class="ecp-login-form">

	<div class="form-heading">
		<h3>Create New Account</h3>
		<span>If you do not have an account, create one by completing the form below. Required fields are marked with an asterisk.<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></span>
	</div>

	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
		<?php wp_nonce_field( 'ecp-user-registration', '_nonce' ) ?>
		<input type="hidden" name="action" value="ecp-user-registration">

		<div class="form-group">
			<label>Title</label> <select name="title" id="title" class="form-control">
				<option value=""></option>
				<option selected="selected" value="">[None]</option>
				<option value="Mr.">Mr.</option>
				<option value="Mrs.">Mrs.</option>
				<option value="Ms.">Ms.</option>
				<option value="Miss">Miss</option>
				<option value="Master">Master</option>
				<option value="Dr.">Dr.</option>
				<option value="Rev.">Rev.</option>
			</select>
		</div>

		<div class="form-group">
			<label for="">First Name:
				<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
			<input type="text" id="firstname" label="First name" name="firstname" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="">Last Name:
				<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
			<input type="text" id="lastname" label="Last name" name="lastname" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="">Email Address: <span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
			<input type="text" id="email" name="email" size="35" class="form-control" label="Email Address" required>
		</div>

		<div class="form-group">
			<label for="">Password:
				<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
			<input type="password" id="password" name="password" label="Password" class="form-control" required>
		</div>

		<div class="form-group">
			<label for="">Screen Name:</label>
			<input type="text" id="screenname" name="screenname" class="form-control">
		</div>

		<div class="form-group">
			<label for="">Company:</label>
			<input type="text" id="company" label="Company" name="company" class="form-control">
		</div>

		<div class="form-group">
			<label for="">Phone:</label> <input type="text" id="phone" name="phone" class="form-control">
		</div>

		<div class="form-group">
			<label for="">Address:</label> <input type="text" id="address" name="address" class="form-control">
		</div>

		<div class="form-group">
			<label for="">City:</label> <input type="text" id="city" name="city" class="form-control">
		</div>

		<div class="form-group">
			<label for="">Zip Code:</label> <input type="text" id="zipcode" name="zipcode" class="form-control">
		</div>

		<div class="form-group">
			<label>State/ Provience:</label> <select name="state" id="state" class="form-control">
				<option value="AL">Alabama</option>
				<option value="AK">Alaska</option>
				<option value="AS">American Somoa</option>
				<option value="AZ">Arizona</option>
				<option value="AR">Arkansas</option>
				<option value="CA">California</option>
				<option value="CO">Colorado</option>
				<option value="CT">Connecticut</option>
				<option value="DE">Delaware</option>
				<option value="DC">District Of Columbia</option>
				<option value="FM">Federated States Of Micronesia</option>
				<option value="FL">Florida</option>
				<option value="GA">Georgia</option>
				<option value="GU">Guam</option>
				<option value="HI">Hawaii</option>
				<option value="ID">Idaho</option>
				<option value="IL">Illinois</option>
				<option value="IN">Indiana</option>
				<option value="IA">Iowa</option>
				<option value="KS">Kansas</option>
				<option value="KY">Kentucky</option>
				<option value="LA">Louisiana</option>
				<option value="ME">Maine</option>
				<option value="MH">Marshall Islands</option>
				<option value="MD">Maryland</option>
				<option value="MA">Massachusetts</option>
				<option value="MI">Michigan</option>
				<option value="MN">Minnesota</option>
				<option value="MS">Mississippi</option>
				<option value="MO">Missouri</option>
				<option value="MT">Montana</option>
				<option value="NE">Nebraska</option>
				<option value="NV">Nevada</option>
				<option value="NH">New Hampshire</option>
				<option value="NJ">New Jersey</option>
				<option value="NM">New Mexico</option>
				<option value="NY">New York</option>
				<option value="NC">North Carolina</option>
				<option value="ND">North Dakota</option>
				<option value="MP">Northern Mariana Islands</option>
				<option value="OH">Ohio</option>
				<option value="OK">Oklahoma</option>
				<option value="OR">Oregon</option>
				<option value="PW">Palau</option>
				<option value="PA">Pennsylvania</option>
				<option value="PR">Puerto Rico</option>
				<option value="RI">Rhode Island</option>
				<option value="SC">South Carolina</option>
				<option value="SD">South Dakota</option>
				<option value="TN">Tennessee</option>
				<option value="TX">Texas</option>
				<option value="UT">Utah</option>
				<option value="VT">Vermont</option>
				<option value="VA">Virginia</option>
				<option value="VI">Virgin Islands</option>
				<option value="WA">Washington</option>
				<option value="WV">West Virginia</option>
				<option value="WI">Wisconsin</option>
				<option value="WY">Wyoming</option>
			</select>
		</div>

		<div class="form-group">
			<label>Country:</label> <select name="country" id="country" class="form-control">
				<option selected="selected" value="US">United States</option>
			</select>
		</div>

		<div class="form-group">
			<label for="">Opt In:</label><br> <input type="checkbox" name="optin" id="optin" value="t">
			<label for="">I would like to receive emails about special offers and events.</label>
		</div>

		<div class="ec-event-captcha">
			<div class="form-group">
				<label class="form-label" for="captcha">Captcha <?php echo "$digit1 $operator $digit2 = ?"; ?>
					<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span></label>
				<input type="text" name="captcha" id="captcha" class="form-control" placeholder="Enter the right captcha" value="" required>
			</div>
			<input type="hidden" name="math" value="<?php echo md5( ecp_do_math( $digit1, $digit2, $operator ) . 'secret-code' ); ?>">
		</div>

		<div class="form-group">
			<button class="btn btn-primary">Create account</button>
		</div>

	</form>

</div>

