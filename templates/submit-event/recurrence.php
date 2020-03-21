<div class="control-group">
	<label class="control-label">Recurrence:</label>

	<div class="controls recurrence">
		<div class="input-grouping">
			<input type="radio" name="eventtype" label="Type of event" class="required" onclick="javascript:hideRecurringChoices();" id="once" value="once" checked/>
			<label class="radio" for="once">One-time</label>
		</div>
		<div class="input-grouping">
			<input type="radio" name="eventtype" label="Type of event" class="required" onclick="javascript:setRecurringChoice();" id="recurring" value="recurring">
			<label class="radio" for="recurring">Recurring</label>
		</div>
	</div>

	<div id="recurringdiv" style="display: none;">

		<div class="controls recurring-selection">
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="daily" id="daily" onclick="showRecurChoices(this);" checked>
				<label class="radio" for="daily">Daily</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="weekly" id="weekly" onclick="showRecurChoices(this);">
				<label class="radio" for="weekly">Weekly</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="monthly" id="monthly" onclick="showRecurChoices(this);">
				<label class="radio" for="monthly">Monthly</label>
			</div>
			<div class="input-grouping">
				<input type="radio" name="recurtype" value="yearly" id="yearly" onclick="showRecurChoices(this);">
				<label class="radio" for="yearly">Yearly</label>
			</div>
		</div>
		<!--Daily recurrence-->
		<div id="dailydiv" class="controls">
			<div class="input-grouping">
				<input type="radio" name="dailytype" value="daily" id="daily_everyday" checked>
				<label class="radio" for="daily_everyday">Every</label>
				<input type="text" size="4" maxlength="2" name="daily_ndays" value="1"> day(s)
			</div>
		</div>

		<!--Weekly recurrence-->
		<div id="weeklydiv" class="controls" style="display:none;">

			<div class="row-entry">
				Recur every
				<input type="text" size="4" id="weekly_nweeks" name="weekly_nweeks" maxlength="2" value="1"> week(s) on:
			</div>

			<div class="row-entry weeklydays">
				<div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Sunday" value="Sunday"><label class="checkbox" for="weekly_weekdays_Sunday">Sunday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Monday" value="Monday"><label class="checkbox" for="weekly_weekdays_Monday">Monday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Tuesday" value="Tuesday"><label class="checkbox" for="weekly_weekdays_Tuesday">Tuesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Wednesday" value="Wednesday"><label class="checkbox" for="weekly_weekdays_Wednesday">Wednesday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Thursday" value="Thursday"><label class="checkbox" for="weekly_weekdays_Thursday">Thursday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Friday" value="Friday"><label class="checkbox" for="weekly_weekdays_Friday">Friday</label>
					</div>
					<div class="input-grouping">
						<input type="checkbox" name="weekly_weekdays[]" class="inline" id="weekly_weekdays_Saturday" value="Saturday"><label class="checkbox" for="weekly_weekdays_Saturday">Saturday</label>
					</div>
				</div>
			</div>
		</div>

		<!--Monthly recurrence-->
		<div id="monthlydiv" class="controls" style="display:none">
			<div class="row-entry monthly_recurdom">
				<div class="input-grouping">
					<input type="radio" name="recurmonthly" id="monthly_recurdom" value="dom" checked>
					<label class="radio" for="monthly_recurdom">Day</label>
					<input name="monthly_dom" type="text" size="4" maxlength="2" value="1">
				</div>
				<div class="input-grouping">
					of every <input type="text" size="4" maxlength="2" name="monthly_nmonths" value="1"> month(s)
				</div>
			</div>

			<div class="row-entry monthly_recurnday">

				<div class="input-grouping">
					<input type="radio" name="recurmonthly" id="monthly_recurnday" value="nday">
					<label class="radio" for="monthly_recurnday">The</label>
				</div>

				<?php

				$monthly_intervals = array( 'first', 'second', 'third', 'fourth', 'last' );

				foreach ( $monthly_intervals as $interval ) {
					echo sprintf( '<div class="input-grouping">
						<input type="checkbox" name="monthly_interval[]" class="inline" id="monthly_interval_%1$s" value="%1$s">
						<label class="checkbox" for="monthly_interval_%1$s">%1$s</label>
					</div>', $interval );
				}

				?>

			</div>

			<div class="row-entry monthly_weekdays">

				<?php
				$monthly_weekdays = array(
					'Sunday',
					'Monday',
					'Tuesday',
					'Wednesday',
					'Thursday',
					'Friday',
					'Saturday'
				);

				foreach ( $monthly_weekdays as $day ) {
					echo sprintf( '<div class="input-grouping">
					<input type="checkbox" name="monthly_weekdays[]" class="inline" id="monthly_weekdays_Sunday" value="%1$s" >
					<label class="checkbox" for="monthly_weekdays_%1$s">%1$s</label>
				</div>', $day );
				}

				?>

			</div>
			<p>of</p>
			<div class="row-entry monthly_months">

				<?php
				$monthly_months = array(
					'1'  => 'Jan',
					'2'  => 'Feb',
					'3'  => 'Mar',
					'4'  => 'Apr',
					'5'  => 'May',
					'6'  => 'Jun',
					'7'  => 'Jul',
					'8'  => 'Aug',
					'9'  => 'Sep',
					'10' => 'Oct',
					'11' => 'Nov',
					'12' => 'Dec',
					'13' => 'Every Month',
				);

				foreach ( $monthly_months as $num => $month ) {
					echo sprintf( '<div class="input-grouping">
						<input type="checkbox" name="monthly_months[]" class="inline" id="monthly_months_%2$s" value="%1$s"><label class="checkbox" for="monthly_months_%2$s">%2$s</label>
					</div>', $num, $month );
				}

				?>

			</div>
		</div>

		<!--Yearly recurrence-->
		<div id="yearlydiv" class="controls" style="display:none">
			<div class="row-entry">

				<div class="input-grouping">
					<input type="radio" name="recuryearly" id="yearly_recurdom" value="nday" checked>
					<label class="radio" for="yearly_recurdom">Every</label>
					<select name="yearly_day" id="yearly_day" start="1" end="31">
						<option value="0">Select...</option>
						<?php
						for ( $i = 1; $i <= 31; $i ++ ) {
							echo sprintf( '<option value="%s">%1$s</option>', $i );
						}
						?>
					</select> day
				</div>

				<div class="input-grouping">
					of <select name="yearly_dommonth" id="yearly_dommonth">
						<option value="0">Select month...</option>
						<?php
						$yearly_dommonth = array(
							'1'  => 'January',
							'2'  => 'February',
							'3'  => 'March',
							'4'  => 'April',
							'5'  => 'May',
							'6'  => 'June',
							'7'  => 'July',
							'8'  => 'August',
							'9'  => 'September',
							'10' => 'October',
							'11' => 'November',
							'12' => 'December',
							'13' => 'Every Month',
						);

						foreach ( $yearly_dommonth as $num => $month ) {
							echo sprintf( '<option value="%1$s">%2$s</option>', $num, $month );
						}

						?>

					</select>
				</div>

			</div>
			<div class="row-entry">

				<div class="input-grouping">
					<input type="radio" name="recuryearly" id="yearly_recurdoy" value="doy">
					<label class="radio" for="yearly_recurdoy">The</label>
					<select name="yearly_interval" id="yearly_interval">
						<?php

						$yearly_intervals = array( 'first', 'second', 'third', 'fourth', 'last' );

						foreach ( $yearly_intervals as $interval ) {
							echo sprintf( '<option value="%s">%1$s</option>', $interval );
						}

						?>
					</select>
				</div>

				<div class="input-grouping">
					<select name="yearly_weekday" id="yearly_weekday">

						<?php

						$yearly_weekdays = array(
							'Sunday',
							'Monday',
							'Tuesday',
							'Wednesday',
							'Thursday',
							'Friday',
							'Saturday'
						);

						foreach ( $yearly_weekdays as $day ) {
							echo sprintf( '<option value="%1$s">%1$s</option>', $day );
						}

						?>

					</select>
				</div>

				<div class="input-grouping">
					of <select name="yearly_doymonth" id="yearly_doymonth">
						<option value="0">Select month...</option>
						<?php
						$yearly_doymonth = array(
							'1'  => 'January',
							'2'  => 'February',
							'3'  => 'March',
							'4'  => 'April',
							'5'  => 'May',
							'6'  => 'June',
							'7'  => 'July',
							'8'  => 'August',
							'9'  => 'September',
							'10' => 'October',
							'11' => 'November',
							'12' => 'December',
							'13' => 'Every Month',
						);

						foreach ( $yearly_doymonth as $num => $month ) {
							echo sprintf( '<option value="%1$s">%2$s</option>', $num, $month );
						}

						?>

					</select>
				</div>

			</div>
		</div>
	</div>
</div>
