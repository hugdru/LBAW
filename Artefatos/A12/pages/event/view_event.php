<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'functions/users.php');

$id_event = $_GET['id'];

$smarty->assign("idevent", $id_event);

$event = getEventById($id_event);

if(!$event){
    $_SESSION['error_messages'][] = 'Event ID not found!';
    header('Location: '. $BASE_URL . 'pages/404.php', true, 301);
    exit();
}



$comments_section = getCommentsSection($id_event);
$albums =getPhotosAlbums($id_event);
$poll = getEventPoll($id_event);
$poll_results = getPollResults($id_event);
$numpart = getParticipantsNumber($id_event);
$hosts = getHosts($id_event);


//var_dump($hosts); exit;

$smarty->assign('actionComment', $BASE_URL . "action/users/insertComment.php");
$smarty->assign('actionCommentVars', array(
        "idEvent" => "idEvent",
        "newComment" => "newComment"
    )
);

$smarty->assign('commentReply', $_GET["commentReply"]);


$smarty->assign('event', $event);
$smarty->assign('comments', $comments_section);
$smarty->assign('media', $albums);
$smarty->assign('poll', $poll);
$smarty->assign('poll_results', $poll_results);
$smarty->assign('number_part', $numpart);
$smarty->assign('hosts', $hosts);
$smarty->assign('currentPage', "view_event");
$smarty->display('event/view_event.tpl');
?>
