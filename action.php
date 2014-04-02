<?php
include_once 'library.php';

$logged_in = isLoggedIn();

$title = "Unbekannte Aktion";
$function = "";

$action = url_get_query_parameter(url_full(), "action");
switch ($action) {
	case "create-calendar":
		$title = "Kalender erstellen";
		$function = "drawCreateCalendar";
		break;
}

beginPage($title);
drawHeaderRow($logged_in);
beginContent();

function drawCreateCalendar() {
	if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT) === 'POST') {
		if (filter_has_var(INPUT_POST, 'name')) {
			$name    = filter_input(INPUT_POST, 'name');
			$user_id = $_SESSION['user_id'];
			$link    = getDatabaseLink();
			
			$query = sprintf(
				'SELECT id FROM calendars WHERE name=\'%s\' AND owner_id=\'%s\';',
				mysql_real_escape_string($name),
				mysql_real_escape_string($user_id));
			
			$result = mysql_query($query, $link);
			if (!$result) {
				die('Fehler bei SQL Abfrage: '.mysql_error());
			}
			if (mysql_num_rows($result) == 0) {
				
				$query = sprintf(
					'INSERT INTO calendars (name, owner_id) VALUES (\'%s\',\'%s\')',
					mysql_real_escape_string($name),
					mysql_real_escape_string($user_id));
				$result = mysql_query($query, $link);
				if (!$result) {
					die('Fehler bei SQL Abfrage: '.mysql_error());
				}
				?>
				<div>Eingetragen :)</div>
				<?php
			} else {
				?>
				<div>Kalender mit gleichem Namen existiert bereits</div>
				<?php			
			}
		} else {
			// Fehlermeldung...
		}
	} else {
		?>
		<form action="<?=url_set_query_parameter(url_relative("action.php"), "action", "create-calendar")?>" method="POST">
			<input type="text" name="name" />
			<input type="submit" value="Kalender erstellen" />
		</form>
		<?php
	}
}

if ($logged_in) {
?>
<div class="table">
	<div class="row">
		<div id="sidebar-area" class="cell">
			<div class="section">
				<?php
					render_calendar();
				?>
			</div>
			<div class="section">
				<?php
					render_actions();
				?>
			</div>
		</div>
		<div class="cell main-area">
			<div>
				<?php
					if ($function == "") {
						?>
						Unbekannte Aktion.
						<?php
					} else {
						call_user_func($function);
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
}

endContent();
endPage();

?>