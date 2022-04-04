<?
    session_start();
    if(isset($_GET['utm_source'])) $_SESSION['utm_source'] = $_GET['utm_source'];
    if(isset($_GET['utm_medium'])) $_SESSION['utm_medium'] = $_GET['utm_medium'];
    if(isset($_GET['utm_campaign'])) $_SESSION['utm_campaign'] = $_GET['utm_campaign'];
    if(isset($_GET['utm_content'])) $_SESSION['utm_content'] = $_GET['utm_content'];
    if(isset($_GET['utm_term'])) $_SESSION['utm_term'] = $_GET['utm_term'];

    global $DOCUMENT_ROOT, $domain;

    $DOCUMENT_ROOT = __DIR__;
    $domain = $_SERVER['SERVER_NAME'];

    $creator_name = "Разработано в Quiz24.ru";
    $creator_link = "https://quiz24.ru/";
    $creator_name_mobile = "Сделано Quiz24";

    require 'function.php';
    require 'actions/amocrm.php';
    require 'actions/bitrix24.php';

?>
