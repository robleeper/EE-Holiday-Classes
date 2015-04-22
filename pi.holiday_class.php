<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$plugin_info = array(
    'pi_name'         => 'Holiday Class',
    'pi_version'      => '0.2.1',
    'pi_author'       => 'Robert Leeper',
    'pi_author_url'   => 'http://robleeper.com/',
    'pi_description'  => 'Generates short names for holidays. Useful for adding CSS classes on special days.',
    'pi_usage'		  => holiday_class::usage()
);

/* 
 * USE THIS PLUGIN AT YOUR OWN RISK; no warranties are expressed or
 * implied. You may modify the file however you see fit, so long as
 * you retain this header information and any credits to other sources
 * throughout the file. If you make any modifications or improvements,
 * please send them via email to Robert Leeper <robert@robleeper.com>
 * or contribute on Github.
 * ------------------------------------------------------------------------
 * Based in large part on...
 * US Holiday Calculations in PHP
 * Version 1.02
 * by Dan Kaplan <design@abledesign.com>
 * Last Modified: April 15, 2001
 * ------------------------------------------------------------------------
 * The holiday calculations on this page were assembled for
 * use in MyCalendar: http://abledesign.com/programs/MyCalendar/
 * 
 * If you make any modifications or improvements,
 * please send them via email to Dan Kaplan <design@abledesign.com>.
 * ------------------------------------------------------------------------
*/

class holiday_class {

	public $return_data = "";

	public function __construct() {

		function format_date( $year, $month, $day ) {
		//
		//
		// Pad single digit months/days with a leading zero for consistency (aesthetics)
		// and format the date as desired: YYYY-MM-DD by default
		//
		//

			if ( strlen( $month ) == 1 ) {
				$month = "0". $month;
			}
			if ( strlen( $day ) == 1 ) {
				$day = "0". $day;
			}
			$date = $year . $month . $day;
			return $date;
		}


		//
		//
		// the following function get_holiday() is based on the work done by
		// Marcos J. Montes: http://www.smart.net/~mmontes/ushols.html
		//
		// if $week is not passed in, then we are checking for the last week of the month
		//
		//
		function get_holiday( $year, $month, $day_of_week, $week="" ) {
			if ( ( ( $week != "" ) && ( ( $week > 5 ) || ( $week < 1 ) ) ) || ( $day_of_week > 6 ) || ( $day_of_week < 0 ) ) {
				// $day_of_week must be between 0 and 6 (Sun=0, ... Sat=6); $week must be between 1 and 5
				return FALSE;
			} else {
				if ( !$week || ( $week == "" ) ) {
					$lastday = date( "t", mktime( 0, 0, 0, $month, 1, $year ) );
					$temp = ( date( "w", mktime( 0, 0, 0, $month, $lastday, $year ) ) - $day_of_week ) % 7;
				} else {
					$temp = ( $day_of_week - date( "w", mktime( 0, 0, 0, $month, 1, $year ) ) ) % 7;
				}

				if ( $temp < 0 ) {
					$temp += 7;
				}

				if ( !$week || ( $week == "" ) ) {
					$day = $lastday - $temp;
				} else {
					$day = ( 7 * $week ) - 6 + $temp;
				}

				return format_date( $year, $month, $day );
			}
		}

		function calculate_easter( $y ) {
			//
			//
			// In the text below, 'intval($var1/$var2)' represents an integer division neglecting
			// the remainder, while % is division keeping only the remainder. So 30/7=4, and 30%7=2
			//
			// This algorithm is from Practical Astronomy With Your Calculator, 2nd Edition by Peter
			// Duffett-Smith. It was originally from Butcher's Ecclesiastical Calendar, published in
			// 1876. This algorithm has also been published in the 1922 book General Astronomy by
			// Spencer Jones; in The Journal of the British Astronomical Association (Vol.88, page
			// 91, December 1977); and in Astronomical Algorithms (1991) by Jean Meeus.
			//
			//

			$a = $y%19;
			$b = intval( $y/100 );
			$c = $y%100;
			$d = intval( $b/4 );
			$e = $b%4;
			$f = intval( ( $b+8 )/25 );
			$g = intval( ( $b-$f+1 )/3 );
			$h = ( 19*$a+$b-$d-$g+15 )%30;
			$i = intval( $c/4 );
			$k = $c%4;
			$l = ( 32+2*$e+2*$i-$h-$k )%7;
			$m = intval( ( $a+11*$h+22*$l )/451 );
			$p = ( $h+$l-7*$m+114 )%31;
			$EasterMonth = intval( ( $h+$l-7*$m+114 )/31 );  // [3 = March, 4 = April]
			$EasterDay = $p+1;  // (day in Easter Month)

			return format_date( $y, $EasterMonth, $EasterDay );
		}

	
		//
		//
		// First, pull in the 'country' setting (if it exists), then decide which country's holidays to use.
		//
		//

		$country = strtolower(ee()->TMPL->fetch_param('country'));

		if ($country == "canada") { // BEHOLD! Canada!
			$holidaylist = array (
			format_date( date( "y" ), 1, 1 )		=> 'newyear',
			format_date( date( "y" ), 2, 14 )		=> 'valentines',
			get_holiday( date( "y" ), 2, 1, 2 )		=> 'family',
			get_holiday( date( "y" ), 2, 1, 3 )		=> 'family',
			format_date( date( "y" ), 3, 17 )		=> 'stpatricks',
			calculate_easter( date( "y" ) )			=> 'easter',
			format_date( date( "y" ), 5, 4 )		=> 'may4',
			format_date( date( "y" ), 5, 5 ) 		=> 'may5',
			format_date( date( "y" ), 7, 1 ) 		=> 'canada',
			get_holiday( date( "y" ), 8, 1, 1 ) 	=> 'civic',
			get_holiday( date( "y" ), 9, 1, 1 ) 	=> 'labour',
			format_date( date( "y" ), 11, 11 ) 		=> 'remembrance',
			get_holiday( date( "y" ), 11, 4, 4 ) 	=> 'thanksgiving',
			format_date( date( "y" ), 12, 25 ) 		=> 'christmas',
			format_date( date( "y" ), 12, 26 ) 		=> 'boxing'
			);
		} else { // 'murica!
			$holidaylist = array (
			format_date( date( "y" ), 1, 1 )		=> 'newyear',
			get_holiday( date( "y" ), 1, 1, 3 ) 	=> 'mlk',
			format_date( date( "y" ), 2, 14 )		=> 'valentines',
			get_holiday( date( "y" ), 2, 1, 3 ) 	=> 'presidents',
			format_date( date( "y" ), 3, 17 )		=> 'stpatricks',
			calculate_easter( date( "y" ) )			=> 'easter',
			format_date( date( "y" ), 5, 4 )		=> 'may4',
			format_date( date( "y" ), 5, 5 ) 		=> 'may5',
			get_holiday( date( "y" ), 5, 1 ) 		=> 'memorial',
			format_date( date( "y" ), 7, 4 ) 		=> 'independence',
			get_holiday( date( "y" ), 9, 1, 1 ) 	=> 'labor',
			format_date( date( "y" ), 9, 11 ) 		=> 'sept11',
			get_holiday( date( "y" ), 10, 1, 2 ) 	=> 'columbus',
			format_date( date( "y" ), 10, 11 ) 		=> 'veterans',
			format_date( date( "y" ), 10, 31 ) 		=> 'halloween',
			get_holiday( date( "y" ), 11, 4, 4 ) 	=> 'thanksgiving',
			format_date( date( "y" ), 12, 25 ) 		=> 'christmas'
			);
		}


		//
		//
		// Holiday array; dates and the text that will ultimately be generated.
		// The idea cfor this array came from Weedpacket on the phpbuilder.com boards.
		// Original post here: http://board.phpbuilder.com/showthread.php?10262758-check-for-holiday-function
		//
		//

		function check_holiday($holidaylist) {
			// Today's date in YYMMDD format
			$todaysdate = date("ymd");

			if (array_key_exists("$todaysdate", $holidaylist)) {
				return $holidaylist["$todaysdate"];
			} else {
				return "standard";
			}
		}

		$this->return_data = check_holiday($holidaylist);
	}


	//
	//
	// Plugin usage info
	//
	//

	public static function usage() {
		ob_start();  ?>
The Holiday Class Plugin outputs a short string value on holidays. If the day is not currently a holiday, it reads "standard".

Just drop the plugin tag wherever you want the class to show up and away you go.


USAGE
------------------------------------------------------------
{exp:holiday_class}


SETTINGS
------------------------------------------------------------
The plugin has a single setting you can add: country. If left empty or omitted, the plugin defaults to USA.

{exp:holiday_class country=“Canada”}


DEFAULT HOLIDAY/CLASSES
------------------------------------------------------------
	New Years Day								(newyear)
	Martin Luther King Jr. Day					(mlk)
	Valentines Day								(valentines)
	President's Day								(presidents)
	George Washington's Birthday				(washington)
	St. Patrick's Day							(stpatricks)
	Easter										(easter)
	May the 4th Be With You						(may4)
	Cinco de Mayo								(may5)
	Memorial Day								(memorial)
	Independence Day							(independence)
	Labor Day									(labor)
	Nine Eleven									(sept11)
	Columbus Day								(columbus)
	Veterans Day								(veterans)
	Halloween									(halloween)
	Thanksgiving								(thanksgiving)
	Christmas									(christmas)

CANADA HOLIDAY/CLASSES
------------------------------------------------------------
	New Years Day								(newyear)
	Valentines									(valentines)
	Family Day									(family)
	St. Patrick's Day							(stpatricks)
	Easter										(easter)
	August Civic Holiday						(civic)
	Labour Day									(labour)
	Remembrance 								(remembrance)
	Thanksgiving 								(thanksgiving)
	Christmas									(christmas)
	Boxing Day									(boxing)


	<?php
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}
}

/* End of file pi.holiday_class.php */
/* Location: ./system/expressionengine/third_party/holiday_class/pi.holiday_class.php */
