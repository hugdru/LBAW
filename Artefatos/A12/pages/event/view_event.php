<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/event.php');
include_once($BASE_DIR . 'functions/users.php');

if (!isset($_GET['id'])) {
    $_SESSION['error_messages'][] = 'No ID. You must explicit the ID of the event in the URL';
    header('Location: '. $BASE_URL . 'pages/404.php');
    exit();
}

$id_event = $_GET['id'];

$event = getEventById($id_event);

if(!$event){
    $_SESSION['error_messages'][] = 'Event ID not found';
    header('Location: '. $BASE_URL . 'pages/404.php');
    exit();
}

if (!$_SESSION['idutilizador'] && $event['publico'] === false) {
    $_SESSION['error_messages'][] = 'Please login to see this private event';
    header('Location: '. $BASE_URL . 'pages/404.php');
    exit();
}

if ($_SESSION['idutilizador'] && !can_view($_SESSION['idutilizador'], $id_event)) {
    $_SESSION['error_messages'][] = 'You can\'t access this private event';
    header('Location: '. $BASE_URL . 'pages/404.php');
    exit();
};

$comments_section = getCommentsSection($id_event);
$albums =getPhotosAlbums($id_event);
$poll = getEventPoll($id_event);
$poll_results = getPollResults($id_event);
$numpart = getParticipantsNumber($id_event);
$participants = getParticipants($id_event);
$hosts = getHosts($id_event);

$is_host = false;
foreach ($hosts as $host){
    if($host['username'] === $_SESSION['username']){
        $is_host = true;
    }
}

$is_participant = false;
foreach ($participants as $participant){
    if($participant['username'] === $_SESSION['username']){
        $is_participant = true;
    }
}

//var_dump($is_participant); exit;

$smarty->assign('event', $event);
$smarty->assign('comments', $comments_section);
$smarty->assign('media', $albums);
$smarty->assign('poll', $poll);
$smarty->assign('poll_results', $poll_results);
$smarty->assign('number_part', $numpart);
$smarty->assign('hosts', $hosts);
$smarty->assign('is_host', $is_host);
$smarty->assign('participants', $participants);
$smarty->assign('is_participant', $is_participant);

$smarty->assign('actionIntention', $BASE_URL . "action/event/setIntention.php");
$smarty->assign('actionIntentionVar', array(
        "idEvent" => "idEvent"
    )
);

$smarty->assign('actionComment', $BASE_URL . "action/event/insertComment.php");
$smarty->assign('actionCommentVars', array(
        "idEvent" => "idEvent",
        "newComment" => "newComment"
    )
);

$smarty->assign('currentPage', "view_event");
$smarty->display('event/view_event.tpl');
?>
