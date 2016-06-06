<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/event.php');

// TODO Get the event info and then assign it to a smarty variable also support paging

$id_event = $_GET['id'];

$event = getEventById($id_event);
$comments_section = getCommentsSection($id_event);
$albums =getPhotosAlbums($id_event);
$poll = getEventPoll($id_event);
$poll_results = getPollResults($id_event);
$numpart = getParticipantsNumber($id_event);

if(!$event){
    http_response_code(404);
    header('Location: '. $BASE_URL . 'pages/404.php', true, 301);
    die();
}

//var_dump($event); exit;



$smarty->assign('event', $event);
$smarty->assign('comments', $comments_section);
$smarty->assign('media', $albums);
$smarty->assign('poll', $poll);
$smarty->assign('poll_results', $poll_results);
$smarty->assign('number_part', $numpart);
$smarty->assign('currentPage', "view_event");
$smarty->display('event/view_event.tpl');
?>
