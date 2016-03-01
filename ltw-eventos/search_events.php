<?
session_start();

include_once('templates/header.php');
include_once('database/connection.php');
include_once('database/events.php');
include_once('status_messaging.php');
include_once('templates/search_events.php');
if(isset($_GET['page'])){
	//prevents reflected XSS
	if(!is_numeric($_GET['page'])){
		add_error_message('Page is not a numeric value.');
		header('Location: search_events.php');
	}
	include_once('templates/list_events.php');
}
include_once('templates/footer.php');
?>
