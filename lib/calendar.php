<?php

include_once 'tools/time.php';
include_once 'tools/url.php';

function render_calendar($url=NULL, $date=NULL, $selectedDate=NULL) {
	
	$DATE_PARAM = 'cdate';
	
	if (is_null($date)) {
		$date = new DateTime();
	}
	if (is_null($url)) {
		$url = url_full();
	}
	$dateString = url_get_query_parameter($url, $DATE_PARAM);
	if (!is_null($dateString)) {
		$date = new DateTime($dateString);
	}
	
	$timestamp = $date->getTimestamp();
	$day   = $date->format("j");
	$month = $date->format("n");
	$year  = $date->format("Y");
	$month_string = strftime("%B", $timestamp);
	$year_string  = strftime("%Y", $timestamp);
	$first_day_in_month = time_first_day_in_month($date);
	$first_day          = time_first_day_of_week($first_day_in_month);
	
	$month_year_string = $month_string . " " . $year_string;
	$previousMonth = time_first_day_in_month($date, -1);
	$previousMonthUrl = url_set_query_parameter($url, $DATE_PARAM, $previousMonth->format('Y-m-d'));
	$nextMonth = time_first_day_in_month($date, 1);
	$nextMonthUrl = url_set_query_parameter($url, $DATE_PARAM, $nextMonth->format('Y-m-d'));
	
?>
<div class="calendar block">
	<div class="table header">
		<div class="row calendar-header-row">
			<div class="cell calendar-previous">
				<a href="<?=$previousMonthUrl?>">&ltrif;&ltrif;</a>
			</div>
			<div class="cell calendar-monthyear">
				<?=$month_year_string?>
			</div>
			<div class="cell calendar-next">
				<a href="<?=$nextMonthUrl?>">&rtrif;&rtrif;</a>
			</div>
		</div>
	</div>
	<div class="table calendar-body">
	<?php
	for ($w=0;$w<6;$w++) {
		?>
		<div class="row calendar-week">
		<?php
		for ($d=0;$d<7;$d++) {
			?>
			<div class="cell calendar-day">
			<a href="<?=url_set_query_parameter($url, $DATE_PARAM, $first_day->format('Y-m-d'));?>">
				<?=$first_day->format("j");?>
			</a>
			<?php
			$first_day->modify("1 days");
			?>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}
	?>
	</div>
</div>
<?php
}
?>
