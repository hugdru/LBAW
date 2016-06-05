<?php
include_once('../../config/init.php');
include_once($BASE_DIR . 'database/users.php');
include_once($BASE_DIR . 'functions/users.php');

redirectIfNotLoggedIn($BASE_URL . "pages/users/login.php");

$smarty->assign('currentPage', "create_event");
$smarty->assign('action', $BASE_URL . "action/event/create_event.php");
$smarty->assign('actionVars',
    array(
        "title" => "titulo",
        "file" => "capa",
        "description" => "descricao",
        "location" => "localizacao",
        "date" => "data",
        "duration" => "duracao",
        "public" => "publico",
        "csrf" => "csrf"
    )
);

$smarty->display('event/create_event.tpl');
?>