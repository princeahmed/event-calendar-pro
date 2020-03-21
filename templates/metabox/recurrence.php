<?php

global $post;

$eventtype        = ecp_get_event_meta( $post->ID, 'eventtype' );
$recurtype        = ecp_get_event_meta( $post->ID, 'recurtype' );
$dailytype        = ecp_get_event_meta( $post->ID, 'dailytype' );
$daily_ndays      = ecp_get_event_meta( $post->ID, 'daily_ndays' );
$weekly_nweeks    = ecp_get_event_meta( $post->ID, 'weekly_nweeks' );
$weekly_weekdays  = explode( ',', ecp_get_event_meta( $post->ID, 'weekly_weekdays' ) );
$recurmonthly     = ecp_get_event_meta( $post->ID, 'recurmonthly' );
$monthly_dom      = ecp_get_event_meta( $post->ID, 'monthly_dom' );
$monthly_nmonths  = ecp_get_event_meta( $post->ID, 'monthly_nmonths' );
$monthly_interval = explode( ',', ecp_get_event_meta( $post->ID, 'monthly_interval' ) );
$monthly_weekdays = explode( ',', ecp_get_event_meta( $post->ID, 'monthly_weekdays' ) );
$monthly_months   = explode( ',', ecp_get_event_meta( $post->ID, 'monthly_months' ) );
$recuryearly      = ecp_get_event_meta( $post->ID, 'recuryearly' );
$yearly_day       = ecp_get_event_meta( $post->ID, 'yearly_day' );
$yearly_dommonth  = ecp_get_event_meta( $post->ID, 'yearly_dommonth' );
$yearly_interval  = ecp_get_event_meta( $post->ID, 'yearly_interval' );
$yearly_weekday   = ecp_get_event_meta( $post->ID, 'yearly_weekday' );
$yearly_doymonth  = ecp_get_event_meta( $post->ID, 'yearly_doymonth' );

?>
<div class="control-group">
	<label class="control-label">Recurrence:</label>

	<div class="controls recurrence">
		<div class="input-grouping">
			<input type="radio" name="eventtype" label="Type of event" class="required" onclick="javascript:hideRecurringChoices();" id="once" value="once" <?php echo( $eventtype != 'recurring' ? 'checked=""' : '' ); ?> >
			<label class="radio" for="once">One-time</label>
		</div>
		<div class="input-grouping">
			<input type="radio" name="eventtype" label="Type of event" class="required" onclick="javascript:setRecurringChoice();" id="recurring" value="recurring" <?php echo( $eventtype == 'recurring' ? 'checked=""' : '' ); ?>>
			<label class="radio" for="recurring">Recurring</label>
		</div>
	</div>

	<div id="recurringdiv" <?php echo( $eventtype != 'recurring' ? 'style="display: none;"' : '' ); ?>>

		<div class="controls recurring-selection">
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="daily" id="daily" onclick="showRecurChoices(this);" <?php echo( $recurtype == 'daily' ? 'checked' : '' ); ?>>
				<label class="radio" for="daily">Daily</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="weekly" id="weekly" onclick="showRecurChoices(this);" <?php echo( $recurtype == 'weekly' ? 'checked' : '' ); ?>>
				<label class="radio" for="weekly">Weekly</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="monthly" id="monthly" onclick="showRecurChoices(this);" <?php echo( $recurtype == 'monthly' ? 'checked' : '' ); ?>>
				<label class="radio" for="monthly">Monthly</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="yearly" id="yearly" onclick="showRecurChoices(this);" <?php echo( $recurtype == 'yearly' ? 'checked' : '' ); ?>>
				<label class="radio" for="yearly">Yearly</label>
			</div>
		</div>

		<div id="dailydiv" class="controls" <?php echo( $recurtype != 'daily' ? 'style="display:none"' : '' ); ?> >
			<div class="input-grouping">
				<input type="radio" name="dailytype" value="daily" id="daily_everyday" <?php echo( $dailytype != 'weekdays' ? 'checked=""' : '' ); ?>>
				<label class="radio" for="daily_everyday">Every</label>
				<input type="text" size="4" maxlength="2" name="daily_ndays" value="<?php echo( $daily_ndays ? $daily_ndays : '1' ); ?>"> day(s)
			</div>

<!--			<div class="input-grouping">-->
<!--				<input type="radio" name="dailytype" value="weekdays" id="daily_weekdays" --><?php //echo( $dailytype == 'weekdays' ? 'checked=""' : '' ); ?><!-->
<!--				<label class="radio" for="daily_weekdays">Every weekday</label>-->
<!--			</div>-->

		</div>

		<div id="weeklydiv" class="controls" <?php echo( $recurtype != 'weekly' ? 'style="display:none"' : '' ); ?> >

			<div class="row-entry">
				Recur every
				<input type="text" size="4" id="weekly_nweeks" name="weekly_nweeks" maxlength="2" value="<?php echo( $weekly_nweeks ? $weekly_nweeks : '1' ); ?>"> week(s) on:
			</div>

			<div class="row-entry weeklydays">
				<div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Sunday" value="Sunday" <?php echo( in_array( 'Sunday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Sunday">Sunday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Monday" value="Monday" <?php echo( in_array( 'Monday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Monday">Monday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Tuesday" value="Tuesday" <?php echo( in_array( 'Tuesday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Tuesday">Tuesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Wednesday" value="Wednesday" <?php echo( in_array( 'Wednesday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Wednesday">Wednesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Thursday" value="Thursday" <?php echo( in_array( 'Thursday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Thursday">Thursday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Friday" value="Friday" <?php echo( in_array( 'Friday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Friday">Friday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Saturday" value="Saturday" <?php echo( in_array( 'Saturday', $weekly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="weekly_weekdays_Saturday">Saturday</label>
					</div>
				</div>
			</div>
		</div>

		<div id="monthlydiv" class="controls" <?php echo( $recurtype != 'monthly' ? 'style="display:none"' : '' ); ?> >
			<div class="row-entry monthly_recurdom">
				<div class="input-grouping">
					<input type="radio" name="recurmonthly" id="monthly_recurdom" value="dom" <?php echo $recurmonthly == 'dom' ? 'checked' : ''; ?>>
					<label class="radio" for="monthly_recurdom">Day</label>
					<input name="monthly_dom" type="text" size="4" maxlength="2" value="<?php echo $monthly_dom ? $monthly_dom : '1'; ?>">
				</div>
				<div class="input-grouping">
					of every
					<input type="text" size="4" maxlength="2" name="monthly_nmonths" value="<?php echo $monthly_nmonths ? $monthly_nmonths : '1'; ?>"> month(s)
				</div>
			</div>

			<div class="row-entry monthly_recurnday">
				<div class="input-grouping">
					<input type="radio" name="recurmonthly" id="monthly_recurnday" value="nday" <?php echo $recurmonthly == 'nday' ? 'checked' : ''; ?>>
					<label class="radio" for="monthly_recurnday">The</label>
				</div>
				<div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_first" value="first" <?php echo( in_array( 'first', $monthly_interval ) ? 'checked' : '' ); ?>>
						<label class="checkbox" for="monthly_interval_first">first</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_second" value="second" <?php echo( in_array( 'second', $monthly_interval ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_interval_second">second</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_third" value="third" <?php echo( in_array( 'third', $monthly_interval ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_interval_third">third</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_fourth" value="fourth" <?php echo( in_array( 'fourth', $monthly_interval ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_interval_fourth">fourth</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_last" value="last" <?php echo( in_array( 'last', $monthly_interval ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_interval_last">last</label>
					</div>
				</div>
			</div>

			<div class="row-entry monthly_weekdays">
				<div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Sunday" value="Sunday" <?php echo( in_array( 'Sunday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Sunday">Sunday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Monday" value="Monday" <?php echo( in_array( 'Monday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Monday">Monday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Tuesday" value="Tuesday" <?php echo( in_array( 'Tuesday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Tuesday">Tuesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Wednesday" value="Wednesday" <?php echo( in_array( 'Wednesday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Wednesday">Wednesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Thursday" value="Thursday" <?php echo( in_array( 'Thursday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Thursday">Thursday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Friday" value="Friday" <?php echo( in_array( 'Friday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Friday">Friday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Saturday" value="Saturday" <?php echo( in_array( 'Saturday', $monthly_weekdays ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_weekdays_Saturday">Saturday</label>
					</div>
				</div>
			</div>
			<p>of</p>
			<div class="row-entry monthly_months">
				<div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Jan" value="1" <?php echo( in_array( '1', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Jan">Jan</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Feb" value="2" <?php echo( in_array( '2', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Feb">Feb</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Mar" value="3" <?php echo( in_array( '3', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Mar">Mar</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Apr" value="4" <?php echo( in_array( '4', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Apr">Apr</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_May" value="5" <?php echo( in_array( '5', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_May">May</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Jun" value="6" <?php echo( in_array( '6', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Jun">Jun</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Jul" value="7" <?php echo( in_array( '7', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Jul">Jul</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Aug" value="8" <?php echo( in_array( '8', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Aug">Aug</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Sep" value="9" <?php echo( in_array( '9', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Sep">Sep</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Oct" value="10" <?php echo( in_array( '10', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Oct">Oct</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Nov" value="11" <?php echo( in_array( '11', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Nov">Nov</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_Dec" value="12" <?php echo( in_array( '12', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_Dec">Dec</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_every" value="13" <?php echo( in_array( '13', $monthly_months ) ? 'checked' : '' ); ?>><label class="checkbox" for="monthly_months_every">Every Month</label>
					</div>
				</div>
			</div>
		</div>

		<div id="yearlydiv" class="controls" <?php echo( $recurtype != 'yearly' ? 'style="display:none"' : '' ); ?> >
			<div class="row-entry">

				<div class="input-grouping">
					<input type="radio" name="recuryearly" id="yearly_recurdom" value="nday" <?php echo( $recuryearly != 'doy' ? 'checked' : '' ); ?>>
					<label class="radio" for="yearly_recurdom">Every</label>
					<select name="yearly_day" id="yearly_day" start="1" end="31">
						<option value="0" <?php echo( $yearly_day == '0' ? 'selected' : '' ); ?>>Select...</option>
						<option value="1" <?php echo( $yearly_day == '1' ? 'selected' : '' ); ?>>1</option>
						<option value="2" <?php echo( $yearly_day == '2' ? 'selected' : '' ); ?>>2</option>
						<option value="3" <?php echo( $yearly_day == '3' ? 'selected' : '' ); ?>>3</option>
						<option value="4" <?php echo( $yearly_day == '4' ? 'selected' : '' ); ?>>4</option>
						<option value="5" <?php echo( $yearly_day == '5' ? 'selected' : '' ); ?>>5</option>
						<option value="6" <?php echo( $yearly_day == '6' ? 'selected' : '' ); ?>>6</option>
						<option value="7" <?php echo( $yearly_day == '7' ? 'selected' : '' ); ?>>7</option>
						<option value="8" <?php echo( $yearly_day == '8' ? 'selected' : '' ); ?>>8</option>
						<option value="9" <?php echo( $yearly_day == '9' ? 'selected' : '' ); ?>>9</option>
						<option value="10" <?php echo( $yearly_day == '10' ? 'selected' : '' ); ?>>10</option>
						<option value="11" <?php echo( $yearly_day == '11' ? 'selected' : '' ); ?>>11</option>
						<option value="12" <?php echo( $yearly_day == '12' ? 'selected' : '' ); ?>>12</option>
						<option value="13" <?php echo( $yearly_day == '13' ? 'selected' : '' ); ?>>13</option>
						<option value="14" <?php echo( $yearly_day == '14' ? 'selected' : '' ); ?>>14</option>
						<option value="15" <?php echo( $yearly_day == '15' ? 'selected' : '' ); ?>>15</option>
						<option value="16" <?php echo( $yearly_day == '16' ? 'selected' : '' ); ?>>16</option>
						<option value="17" <?php echo( $yearly_day == '17' ? 'selected' : '' ); ?>>17</option>
						<option value="18" <?php echo( $yearly_day == '18' ? 'selected' : '' ); ?>>18</option>
						<option value="19" <?php echo( $yearly_day == '19' ? 'selected' : '' ); ?>>19</option>
						<option value="20" <?php echo( $yearly_day == '20' ? 'selected' : '' ); ?>>20</option>
						<option value="21" <?php echo( $yearly_day == '21' ? 'selected' : '' ); ?>>21</option>
						<option value="22" <?php echo( $yearly_day == '22' ? 'selected' : '' ); ?>>22</option>
						<option value="23" <?php echo( $yearly_day == '23' ? 'selected' : '' ); ?>>23</option>
						<option value="24" <?php echo( $yearly_day == '24' ? 'selected' : '' ); ?>>24</option>
						<option value="25" <?php echo( $yearly_day == '25' ? 'selected' : '' ); ?>>25</option>
						<option value="26" <?php echo( $yearly_day == '26' ? 'selected' : '' ); ?>>26</option>
						<option value="27" <?php echo( $yearly_day == '27' ? 'selected' : '' ); ?>>27</option>
						<option value="28" <?php echo( $yearly_day == '28' ? 'selected' : '' ); ?>>28</option>
						<option value="29" <?php echo( $yearly_day == '29' ? 'selected' : '' ); ?>>29</option>
						<option value="30" <?php echo( $yearly_day == '30' ? 'selected' : '' ); ?>>30</option>
						<option value="31" <?php echo( $yearly_day == '31' ? 'selected' : '' ); ?>>31</option>
					</select> day
				</div>

				<div class="input-grouping">
					of <select name="yearly_dommonth" id="yearly_dommonth">
						<option value="0" <?php echo( $yearly_dommonth == '0' ? 'selected' : '' ); ?>>Select month...</option>
						<option value="1" <?php echo( $yearly_dommonth == '1' ? 'selected' : '' ); ?>>January</option>
						<option value="2" <?php echo( $yearly_dommonth == '2' ? 'selected' : '' ); ?>>February</option>
						<option value="3" <?php echo( $yearly_dommonth == '3' ? 'selected' : '' ); ?>>March</option>
						<option value="4" <?php echo( $yearly_dommonth == '4' ? 'selected' : '' ); ?>>April</option>
						<option value="5" <?php echo( $yearly_dommonth == '5' ? 'selected' : '' ); ?>>May</option>
						<option value="6" <?php echo( $yearly_dommonth == '6' ? 'selected' : '' ); ?>>June</option>
						<option value="7" <?php echo( $yearly_dommonth == '7' ? 'selected' : '' ); ?>>July</option>
						<option value="8" <?php echo( $yearly_dommonth == '8' ? 'selected' : '' ); ?>>August</option>
						<option value="9" <?php echo( $yearly_dommonth == '9' ? 'selected' : '' ); ?>> September</option>
						<option value="10" <?php echo( $yearly_dommonth == '10' ? 'selected' : '' ); ?>> October</option>
						<option value="11" <?php echo( $yearly_dommonth == '11' ? 'selected' : '' ); ?>>November</option>
						<option value="12" <?php echo( $yearly_dommonth == '12' ? 'selected' : '' ); ?>>December</option>
						<option value="13" <?php echo( $yearly_dommonth == '13' ? 'selected' : '' ); ?>>Every Month</option>
					</select>
				</div>

			</div>
			<div class="row-entry">

				<div class="input-grouping">
					<input type="radio" name="recuryearly" id="yearly_recurdoy" value="doy" <?php echo( $recuryearly == 'doy' ? 'checked' : '' ); ?>>
					<label class="radio" for="yearly_recurdoy">The</label>
					<select name="yearly_interval" id="yearly_interval">
						<option value="first" <?php echo( $yearly_interval == 'first' ? 'selected' : '' ); ?>>first</option>
						<option value="second" <?php echo( $yearly_interval == 'second' ? 'selected' : '' ); ?>>second</option>
						<option value="third" <?php echo( $yearly_interval == 'third' ? 'selected' : '' ); ?>>third</option>
						<option value="fourth" <?php echo( $yearly_interval == 'fourth' ? 'selected' : '' ); ?>>fourth</option>
						<option value="last" <?php echo( $yearly_interval == 'last' ? 'selected' : '' ); ?>>last</option>
					</select>
				</div>

				<div class="input-grouping">
					<select name="yearly_weekday" id="yearly_weekday">
						<option value="Sunday" <?php echo( $yearly_weekday == 'Sunday' ? 'selected' : '' ); ?>>Sunday</option>
						<option value="Monday" <?php echo( $yearly_weekday == 'Monday' ? 'selected' : '' ); ?>>Monday</option>
						<option value="Tuesday" <?php echo( $yearly_weekday == 'Tuesday' ? 'selected' : '' ); ?>>Tuesday</option>
						<option value="Wednesday" <?php echo( $yearly_weekday == 'Wednesday' ? 'selected' : '' ); ?>>Wednesday</option>
						<option value="Thursday" <?php echo( $yearly_weekday == 'Thursday' ? 'selected' : '' ); ?>>Thursday</option>
						<option value="Friday" <?php echo( $yearly_weekday == 'Friday' ? 'selected' : '' ); ?>>Friday</option>
						<option value="Saturday" <?php echo( $yearly_weekday == 'Saturday' ? 'selected' : '' ); ?>>Saturday</option>
						<option value="Sunday" <?php echo( $yearly_weekday == 'Sunday' ? 'selected' : '' ); ?>>Sunday</option>
					</select>
				</div>

				<div class="input-grouping">
					of <select name="yearly_doymonth" id="yearly_doymonth">
						<option value="0" <?php echo( $yearly_doymonth == '0' ? 'selected' : '' ); ?>>Select month...</option>
						<option value="1" <?php echo( $yearly_doymonth == '1' ? 'selected' : '' ); ?>>January</option>
						<option value="2" <?php echo( $yearly_doymonth == '2' ? 'selected' : '' ); ?>>February</option>
						<option value="3" <?php echo( $yearly_doymonth == '3' ? 'selected' : '' ); ?>>March</option>
						<option value="4" <?php echo( $yearly_doymonth == '4' ? 'selected' : '' ); ?>>April</option>
						<option value="5" <?php echo( $yearly_doymonth == '5' ? 'selected' : '' ); ?>>May</option>
						<option value="6" <?php echo( $yearly_doymonth == '6' ? 'selected' : '' ); ?>>June</option>
						<option value="7" <?php echo( $yearly_doymonth == '7' ? 'selected' : '' ); ?>>July</option>
						<option value="8" <?php echo( $yearly_doymonth == '8' ? 'selected' : '' ); ?>>August</option>
						<option value="9" <?php echo( $yearly_doymonth == '9' ? 'selected' : '' ); ?>>September</option>
						<option value="10" <?php echo( $yearly_doymonth == '10' ? 'selected' : '' ); ?>>October</option>
						<option value="10" <?php echo( $yearly_doymonth == '10' ? 'selected' : '' ); ?>>November</option>
						<option value="12" <?php echo( $yearly_doymonth == '12' ? 'selected' : '' ); ?>>December</option>
						<option value="13" <?php echo( $yearly_doymonth == '13' ? 'selected' : '' ); ?>>Every Month</option>
					</select>
				</div>

			</div>
		</div>
	</div>
</div>
