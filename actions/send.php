<?
    require '../config.php';

    $utm_source    = $_SESSION['utm_source'];
    $utm_medium    = $_SESSION['utm_medium'];
    $utm_campaign  = $_SESSION['utm_campaign'];
    $utm_content   = $_SESSION['utm_content'];
    $utm_term      = $_SESSION['utm_term'];
 

    $post = securityForm($_POST);
    $action = $post['action'];
    $time = $post['time'];
    $phone = preg_replace("/[^0-9]/", '', $post['phone']);
    $email = $post['email'] ? $post['email'] : "";
    $file_name = "Файл";
    $phone_last = "";


    if($action == "test"){
        $subject = "Пройден текст";
    }else if($action == "consut"){
        $subject = strip_tags(get_content("forms", "header", "form_title"));
        $file_name = "Проект";
    }else if($action == "updatePhone"){
        $subject = "Запрос на изменения номера";
        $phone_last = $_SESSION['phone'];
    }
    if(!$subject) return false;

    $subject .= " - Квиз {$domain}";
    $_SESSION['phone'] = $phone;

    # TEXT
    $message = "";
    $message_crm = "";
    $test_steps = get_content("test", "test_block", "test");
    if($action == "test") $message_crm = "Пройден тест!\n";
    foreach ($test_steps as $numstep => $step) {
        $val = "";
        if($post['test'][$numstep]){
            $val = $post['test'][$numstep];
            if(is_array($val)) $val = implode(", ", $val);
            if($val == "checkbox-text"){
                $val = "Другое. {$post['test']["{$numstep}-text"]}";
            }
        }
        if($val) {
            $step['title'] = trim($step['title']);
            $message .= "{$numstep}) {$step['title']}<br>{$val}<br><br>";
            $message_crm .= "{$numstep}) {$step['title']}: {$val}\n";
        }
    }

    # не отправлять если есть чужая ссылка
    if(stristr($message, "http") || stristr($message, "www")) exit();
    # не отправлять если нет ни одного ответа
    if($action == "test" && !$message) exit();

    if($post['call']) {
        $message .= "<br>Способ связи: {$post['call']}<br>";
        $message_crm .= "\nСпособ связи: {$post['call']}\n";
    }
    if($email) {
        $message .= "<br>Почта: {$email}<br>";
        $message_crm .= "\nПочта: {$email}\n";
    }
    if($time) {
        $message .= "<br>Время для звонка: {$time}<br>";
        $message_crm .= "\nВремя для звонка: {$time}\n";
    }
    if($phone) {
        $message .= "Телефон: {$phone}<br>";
        $message_crm .= "Телефон: {$phone}\n";
    }
    if($phone_last) {
        $message .= "Прошлый телефон: {$phone_last}<br>";
        $message_crm .= "Прошлый телефон: {$phone_last}\n";
    }

    // если есть файл для прикрепления
    if($_FILES){
        $uploaddir = '/uploads/';
        checkAndCreateDir($uploaddir);
        foreach($_FILES as $file){
            $extension = strtolower( end( explode('.', $file['name'][0]) ) );
            $deny = array(
            	'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp',
            	'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html',
            	'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi'
            );
            if(in_array($extension, $deny)) exit(1);
            $filename_new = date("y.m.d") . '__' . date("H\-i\-s") . '.' . $extension;

            if(move_uploaded_file($file['tmp_name'][0], $DOCUMENT_ROOT.$uploaddir . $filename_new)){
                $link_file = "https://{$domain}/uploads/{$filename_new}";
                $message .= "<b>{$file_name}:</b> <a href='{$link_file}'>{$link_file}</a><br>";
                $message_crm .= "{$file_name}: {$link_file}\n";
            }
        }
    }

    # отправка email
    $headers = "from:info@{$domain}\nContent-Type: text/html; charset=UTF-8";
    $email_to = get_content("main", "main", "emails");
    if($email_to){
        if(mail($email_to, $subject, $message, $headers)){
            echo 'send';
        }else{
            echo 'failed';
        }
    }

    $message_crm = trim($message_crm);
    # AMO
    $amo = new Amocrm($subject, $phone, $message_crm, $email, $action);
    if($amo->checkData()) $amo->actionLead();

    # Bitrix
    $bitrix = new Bitrix24($subject, $phone, $message_crm, $email, $action);
    if($bitrix->checkData()) $bitrix->actionAdd();

?>
