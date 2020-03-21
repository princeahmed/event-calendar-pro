function showRecurChoices(radio) {
	var choices = new Array("daily","weekly","monthly","yearly");
	var div;
	//hide all the recurrence divs
	for(var i = 0; i < choices.length; i++) {
		div = document.getElementById(choices[i] + 'div');
		if (div)
			div.style.display='none';
	}
	//show the div that corresponds to the passed in radio button
	div = document.getElementById(radio.id + 'div');
	if(div && radio.checked) {
		div.style.display = 'block';
	}
}
/**
 * hides the recurring options div
 */
function hideRecurringChoices()
{
	var div=document.getElementById('recurringdiv');
	div.style.display='none';

}

/**
 * displays the recurring options div
 */
function showRecurringChoices()
{
	var div=document.getElementById('recurringdiv');
	div.style.display = 'block';

}

/**
 * On page refresh, detect if any of the recurring
 * radio buttons have been previously selected and
 * restore the state.
 *
 */
function initRecurringChoices() {
	var checkedRecurrence = null;
	var radioButtons = document.getElementsByName("params[eventtype]");
	for (var i = 0, len = radioButtons.length; i < len; i++) {
		if (radioButtons[i].checked == true) {
			checkedRecurrence = radioButtons[i];
			break;
		}
	}
	if (checkedRecurrence == null) {
		checkedRecurrence = radioButtons[0];
		radioButtons[0].checked = true;
	}
	if (checkedRecurrence.id == "recurring") {
		setRecurringChoice();
	} else {
		showRecurChoices(null);
		hideRecurringChoices();
	}
}

/**
 * If the recurring button is checked on page refresh, or if the
 * recurring button is pressed, restore any previously checked
 * sub-radio button and its choices.
 *
 */
function setRecurringChoice() {
	var checkedRecurType = null;
	var radioButtons = document.getElementsByName("params[recurtype]")
	for (i = 0, len = radioButtons.length; i < len; i++) {
		if (radioButtons[i].checked == true) {
			checkedRecurType = radioButtons[i];
			break;
		}
	}
	showRecurringChoices();
	if (checkedRecurType != null) {
		showRecurChoices(checkedRecurType);
	}
}

/**
 * input form validation function.
 * @formname - the name of the form to validate
 * @buttonname - the name of the button that is used to trigger form submission.
 */
function doFormPost(formname,buttonname) {
	var msg='';
	//event datetime validation
	if("save,apply,update,savenew".indexOf(buttonname)>=0) {
		var once = document.getElementById('once');
		var recur = document.getElementById('recurring');
		//check to see if event type (one|recurring) was selected

		if((once != null && recur != null) && ( !once.checked && !recur.checked))
			msg+='You must select either One Time Event or Recurring Event';
		//validate date input fields for one-time events
		if(once && once.checked) {
			msg += _checkdate();
		}

		var  categoriesRequired = document.getElementById('calendarevent_categoriesrequired');
		if (categoriesRequired && 'true' == categoriesRequired.value) {
			msg += _checkAtLeastOneCategorySelected();
		}
		//recurring event validation
		if(recur && recur.checked) {
			var daily = document.getElementById("daily");
			var weekly = document.getElementById("weekly");
			var monthly = document.getElementById("monthly");
			var yearly = document.getElementById("yearly");
			//check if reocurrence type (daily|monthy..) was selected
			if((daily && weekly && monthly && yearly) && (!daily.checked && !weekly.checked && !monthly.checked && !yearly.checked)) {
				msg += "You must select either 'daily', 'weekly', 'monthly', or 'yearly' as a recurring event mode.\n";
				daily.focus();
			}
			if (daily.checked) { //check daily recurrence
				msg += _checkDailyRecurrence();
			} else if (weekly.checked) { //check weekly recurrence
				msg += _checkWeeklyRecurrence();
			} else if (monthly.checked) { //check monthly recurrence
				msg += _checkMonthlyRecurrence();
			} else if(yearly.checked) { //check yearly recurrence
				msg += _checkYearlyRecurrence();
			}
			//lastly, check the recurrence dates
			msg += _checkdate();
		} //end recurring event validation


		msg += _checkTitle();
		//msg += _checkDescription();
		if(msg != '') {
			alert(msg);
			return false;
		}
		return autocheck(document.getElementById(formname));
	} //end event datetime validation
	return true;
}

//------------------------------------------------------------------------------
// internal functions
//------------------------------------------------------------------------------


// @todo: Replace all of this from these specialized functions to generic functions
// @todo: Port over to jQuery and simplify code
/**
 * get the title and trim the result.
 * this does NOT affect the saved result, just what we are testing.
 *
 *
 * @returns {String}
 */
function _checkTitle() {
	if(rjQuery("#ops_calendarevent_title").val().trim().length == 0){
		return "Event Title is required.\n";
	}
	return "";
}

/*
 * @see doFormPost();
 */
function _checkDailyRecurrence() {
	var msg = "";
	var daily_daily = document.getElementById('daily_daily');
	var daily_ndays = document.getElementById('daily_ndays');
	var daily_weekdays = document.getElementById('daily_weekdays');
	if (daily_daily && daily_weekdays && (!daily_daily.checked && !daily_weekdays.checked)) {
		msg += "You must select a daily recurrence pattern.\n";
		daily_daily.focus();
	}
	if (daily_daily && daily_ndays && daily_daily.checked) {
		var daily_ndays_val = daily_ndays.value;
		if (daily_ndays_val) {
			daily_ndays_val = parseInt(daily_ndays_val, 10);
			if (isNaN(daily_ndays_val) || daily_ndays_val < 1) {
				msg += "You must specify a valid interval for daily event recurrence.\n";
				daily_ndays.focus();
			} else {
				daily_ndays.value = daily_ndays_val;
			}
		}
	}
	return msg;
}

/*
 * @see doFormPost();
 */
function _checkWeeklyRecurrence() {
	var msg = "";
	var i = 0;
	var weekly_nweeks = document.getElementById('weekly_nweeks');
	if (weekly_nweeks) {

		var weekly_nweeks_val = parseInt(weekly_nweeks.value, 10);
		if (isNaN(weekly_nweeks_val) || weekly_nweeks_val < 1) {

			msg += "You must specify a valid interval for weekly event recurrence.\n";
			weekly_nweeks.focus();
		} else {
			weekly_nweeks.value = weekly_nweeks_val;
		}
	}
	var weekdays = document.getElementsByName("params[weekly_weekdays][]");
	if (weekdays) {
		var isWeekdayChecked = false;
		for(i = 0; i < weekdays.length; i++) {
			if(weekdays[i].checked) {
				isWeekdayChecked = true;
				break;
			}
		}
		if (!isWeekdayChecked) {
			msg += "You must specify at least one weekday for weekly event recurrence.\n";
			weekdays[0].focus();
		}
	}
	return msg;
}
/*
 * @see doFormPost();
 */
function _checkMonthlyRecurrence() {
	var msg = "";
	var i = 0;
	var monthly_recurdom = document.getElementById("monthly_recurdom");
	var monthly_recurnday = document.getElementById("monthly_recurnday");
	if (monthly_recurdom && monthly_recurnday && (!monthly_recurdom.checked && !monthly_recurnday.checked)) {
		msg += "You must select a monthly recurrence pattern.\n";
		monthly_recurdom.focus();
	}
	if (monthly_recurdom.checked) {
		var monthly_recurdom_day = document.getElementById("monthly_recurdom_day");
		if (monthly_recurdom_day) {
			var monthly_recurdom_day_val = parseInt(monthly_recurdom_day.value,10);
			if (isNaN(monthly_recurdom_day_val) || monthly_recurdom_day_val < 1 || monthly_recurdom_day_val > 31) {
				msg += "You must specify a valid day-of-month for monthly event recurrence.\n";
				monthly_recurdom_day.focus();
			} else {
				monthly_recurdom_day.value = monthly_recurdom_day_val;
			}
		}
		var monthly_recurdom_month = document.getElementById("monthly_recurdom_month");
		if (monthly_recurdom_month) {
			var monthly_recurdom_month_val = parseInt(monthly_recurdom_month.value, 10);
			if (isNaN(monthly_recurdom_month_val) || monthly_recurdom_month_val < 1) {
				msg += "You must specify a valid month value for monthly event recurrence.\n";
				monthly_recurdom_month.focus();
			} else {
				monthly_recurdom_month.value = monthly_recurdom_month_val;
			}
		}
	} else if (monthly_recurnday.checked) {
		var monthly_recurnday_interval  = document.getElementsByName("params[monthly_interval][]");
		if (monthly_recurnday_interval) {
			var isIntervalChecked = false;
			for(i = 0; i < monthly_recurnday_interval.length; i++) {
				if(monthly_recurnday_interval[i].checked) {
					isIntervalChecked = true;
					break;
				}
			}
			if (!isIntervalChecked) {
				msg += "You must select at least one interval (first, second, fourth, ...) for monthly event recurrence.\n";
				monthly_recurnday_interval[0].focus();
			}
		}
		var monthly_recurnday_weekdays  = document.getElementsByName("params[monthly_weekdays][]");
		if (monthly_recurnday_weekdays) {
			var isWeekdayChecked = false;
			for(i = 0; i < monthly_recurnday_weekdays.length; i++) {
				if(monthly_recurnday_weekdays[i].checked) {
					isWeekdayChecked = true;
					break;
				}
			}
			if (!isWeekdayChecked) {
				msg += "You must select at least one week day for monthly event recurrence.\n";
				monthly_recurnday_weekdays[0].focus();
			}
		}
		var monthly_recurnday_months  = document.getElementsByName("params[monthly_months][]");
		if (monthly_recurnday_months) {
			var isMonthChecked = false;
			for(i = 0; i < monthly_recurnday_months.length; i++) {
				if(monthly_recurnday_months[i].checked) {
					isMonthChecked = true;
					break;
				}
			}
			if (!isMonthChecked) {
				msg += "You must select at least one month for monthly event recurrence.\n";
				monthly_recurnday_months[0].focus();
			}
		}
	}
	return msg;
}
/**
 * @see doFormPost();
 */
function _checkYearlyRecurrence() {
	var msg = "";
	var yearly_recurdom = document.getElementById("yearly_recurdom");
	var yearly_recurdoy = document.getElementById("yearly_recurdoy");
	if (yearly_recurdom && yearly_recurdoy && (!yearly_recurdom.checked && !yearly_recurdoy.checked)) {
		msg += "You must select a yearly recurrence pattern.\n";
		yearly_recurdom.focus();
	}
	if (yearly_recurdom.checked){
		var yearly_recurdom_day = document.getElementById("yearly_day");
		if (yearly_recurdom_day) {
			var yearly_recurdom_day_var = parseInt(yearly_recurdom_day.value);
			if (isNaN(yearly_recurdom_day_var) || yearly_recurdom_day_var < 1 || yearly_recurdom_day_var > 31) {
				msg += "You must specify a valid day-of-month for yearly event recurrence.\n";
				yearly_recurdom_day.focus();
			} else {
				yearly_recurdom_day.value = yearly_recurdom_day_var;
			}
		}
		var yearly_recurdom_month = document.getElementById("yearly_dommonth");
		if (!yearly_recurdom_month) {
			msg += "You must select a month for yearly event recurrence.\n";
			yearly_recurdom_month.focus();
		}
	} else if (yearly_recurdoy.checked) {
		var yearly_recurdoy_month = document.getElementById("yearly_doymonth");
		if(yearly_recurdoy_month && yearly_recurdoy_month.selectedIndex <= 0) {
			msg += "You must select a month for yearly event recurrence.\n";
			yearly_recurdoy_month.focus();
		}
	}
	return msg;
}


function _y2k(number) {
	return (number < 1000) ? number + 1900 : number;
}

function _checkdate() {
	var msg = '';
	var startdate = document.getElementById('startdate');
	var enddate = document.getElementById('enddate');
	//check if start date is present and valid
	if (startdate) {
		var startTs = Date.parse(startdate.value.replace(/-/g,'/'));
		if (isNaN(startTs)) {
			return "You must enter full and valid Start Date.\n";
		}
	} else {
		return "You must enter full and valid Start Date.\n";
	}
	//if start date is ok, compare it against a provided end date
	if (enddate && enddate.value) {
		var endTs = Date.parse(enddate.value.replace(/-/g,'/'));
		if (isNaN(endTs)) {
			return "End Date must be valid.\n";
		}
		if (endTs < startTs) {
			return "End Date should not be less than Start Date.\n";
		}
	} else {
		enddate.value=startdate.value;
		// return "You must enter full and valid End Date.\n";
	}
	return msg;
}

function _daysInMonth(iMonth, iYear){
	var days, isleap;
	days = [31,28,31,30,31,30,31,31,30,31,30,31];
	if( iMonth != 2 ) {
		return days[iMonth-1];
	} else {
		isleap =(iYear % 4 == 0 && iYear %100 !=0) || (iYear % 400 ==0) ? 29 : 28;
		return isleap;
	}
}

/**
 * Checks if at least one of the event category checkboxes is selected.
 * @return string - an empty string is validation was successful, otherwise and error msg is returned.
 */
function _checkAtLeastOneCategorySelected()
{
	var msg = "";
	var isChecked = false;
	// get the checkboxes
	var categories = document.getElementsByName("calendarevent_categories[]");
	// must exist and must contrain at least one elem.
	if (categories && categories.length) {
		for (var i =0; i < categories.length; i++) {
			if (categories[i].checked) {
				isChecked = true;
				break;
			}
		}
		if (!isChecked) {
			categories[0].focus();
			msg = "You must select at least one category.\n";

		}
	}
	return msg;
}
