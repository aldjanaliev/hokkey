<?  error_reporting(0);
    require 'config.php';

    # MAIN 
    $title = get_content("main", "main", "title");
    $favicon = get_content("main", "main", "favicon");

    # header
    $logo = get_content("index", "header", "logo");
    $podskazka = get_content("index", "header", "podskazka");
    $phone = get_content("index", "header", "phone");
    $callback_name = get_content("index", "header", "callback_name");
    $callback_color = get_content("index", "header", "callback_color");

    # LEFT
    $h1 = get_content("index", "left", "h1");
    $h1_sub = get_content("index", "left", "h1_sub");

    $advantage_items = get_content("index", "left", "advantage");
    $advantage = "";
    $avs = array();
    foreach ($advantage_items as $i => $adv) {
        $key = $i%2 == 0 ? $i : $i-1;
        $avs[$key] .= "<div class='av'>
                            ".($adv['image']['value']['path'] ? "<div class='av-img'><img src='{$adv['image']['value']['path']}'></div>" : "")."
                            <div class='av-text'>{$adv['text']['value']}</div>
                        </div>";
    }
    foreach ($avs as $av) $advantage .= "<div class='avs-item'>{$av}</div>";
    if($advantage) $advantage = "<div class='avs'>{$advantage}</div>";

    $flash_text = get_content("index", "left", "flash_text");
    $flash_text_color = get_content("index", "left", "flash_text_color");
    $btn_top = get_content("index", "left", "btn_top");
    $btn = get_content("index", "left", "btn");
    $btn_bottom = get_content("index", "left", "btn_bottom");

    $akps_items = get_content("index", "left", "slidedown");
    $akps = "";
    foreach ($akps_items as $akp) {
        $akps .= "<div class='akp ".($akp['text']['value'] ? "akp-text-have" : "")."'>
                        <div class='akp-wrapper'>
                            <div class='akp-img'><img src='{$akp['image']['value']['path']}'></div>
                        </div>
                        <div class='akp-name'>
                            <span>
                                {$akp['title']['value']}
                                ".($akp['file']['value']['path'] ? "<span class='akp-download editor text none'><a href='{$akp['file']['value']['path']}' target='_blank'>скачать</a></span>" : "")."
                            </span>
                            ".($akp['text']['value'] ? "<svg width='16' height='10' viewBox='0 0 16 10' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' clip-rule='evenodd' d='M1.81248 0L7.68 5.99933L13.5475 0L15.36 1.84354L7.68 9.65518L0 1.84354L1.81248 0Z' fill='black'/></svg>" : "")."
                        </div>
                        ".($akp['text']['value'] ? "<div class='akp-text'><div class='akp-text-wrap'><div class='akp-text-content'>{$akp['text']['value']}</div></div></div>" : "")."
                    </div>";
    }
    $akps = "<div class='akps-main'><div class='akps'>{$akps}</div></div>";

    # RIGHT
    $slider_items = get_content("index", "right", "slider");
    $slider_item = "";
    foreach ($slider_items as $sldr) {
        if($sldr['image']['value']['path']) $slider_item .= "<div class='swiper-slide'><img src='{$sldr['image']['value']['path']}'></div>";
    }
    $slider_right = "<div class='swiper-container'>
                        <div class='swiper-wrapper'>{$slider_item}</div>
                        ".(count($slider_items) > 1 ?
                        "<div class='swiper-pagination'></div>
                        <div class='swiper-button'>
                            <div class='swiper-button-prev'><svg width='9' height='15' viewBox='0 0 9 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.224664 7.49996C0.224664 7.23113 0.327309 6.96233 0.532169 6.75737L6.9819 0.307712C7.39218 -0.102571 8.05738 -0.102571 8.4675 0.307712C8.87761 0.717829 8.87761 1.3829 8.4675 1.79321L2.76042 7.49996L8.4673 13.2067C8.87741 13.617 8.87741 14.282 8.4673 14.6921C8.05718 15.1026 7.39198 15.1026 6.9817 14.6921L0.531969 8.24254C0.327077 8.03749 0.224664 7.76869 0.224664 7.49996Z' fill='#1E92FD'/></svg></div>
                            <div class='swiper-button-next'><svg width='9' height='15' viewBox='0 0 9 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.224664 7.49996C0.224664 7.23113 0.327309 6.96233 0.532169 6.75737L6.9819 0.307712C7.39218 -0.102571 8.05738 -0.102571 8.4675 0.307712C8.87761 0.717829 8.87761 1.3829 8.4675 1.79321L2.76042 7.49996L8.4673 13.2067C8.87741 13.617 8.87741 14.282 8.4673 14.6921C8.05718 15.1026 7.39198 15.1026 6.9817 14.6921L0.531969 8.24254C0.327077 8.03749 0.224664 7.76869 0.224664 7.49996Z' fill='#1E92FD'/></svg></div>
                        </div>" :
                        "")."
                    </div>";
    # BOTTOM
    $slider_check = get_content("index", "bottom", "slider_check");
    $slider_title_1 = get_content("index", "bottom", "slider_title_1");
    $slider_title_2 = get_content("index", "bottom", "slider_title_2");
    $slider_title_3 = get_content("index", "bottom", "slider_title_3");
    $slider = get_content("index", "bottom", "slider");
    $bottom_slider = array(
        'title' => $slider_title_1,
        'second' => $slider_title_2,
        'mini' => $slider_title_3,
        'items' => $slider
    );
    $bottom_slider = slider($bottom_slider);

    # TEST
    $test_title = get_content("test", "test_block", "title");
    $step_color = get_content("test", "test_block", "step_color");
    $test = get_test();
    $test_blk_background = get_content("test", "block_main", "background");
    $test_blk_color1 = get_content("test", "block_main", "color_1");
    $test_blk_color2 = get_content("test", "block_main", "color_2");

    # UR
    $client_name = get_content("main", "ur", "client_name");
    $rekvizity = get_content("main", "ur", "rekvizity");
    $politika_link = get_content("main", "ur", "politika");

    # OTHER
    $video_link = get_content("main", "other", "video");
    $meta = get_content("main", "other", "meta", 1);
    $background_link = get_content("main", "design", "background");
    $background_color = get_content("main", "design", "background_color");

    # RESULT
    $title_result = get_content("result", "main", "title");

    # FORM CALLBACK
    $form_text_title = get_content("forms", "header", "title");
    $form_title_list = get_content("forms", "header", "title_list");
    $form_lists = get_content("forms", "header", "list");
    $form_list = "";
    foreach ($form_lists as $lst) {
        if($lst['text']['value']) $form_list .= "<span><svg width='10' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M9.707 2.07L4.432 7.345c-.391.39-1.025.39-1.416 0L.293 4.62a1 1 0 011.415-1.415l2.016 2.016L8.292.654A1 1 0 019.707 2.07z' fill='#093445'/></svg>{$lst['text']['value']}</span>";
    }
    $form_form_title = get_content("forms", "header", "form_title");
    $form_form_subtitle = get_content("forms", "header", "form_subtitle");
    $form_form_text = get_content("forms", "header", "form_text");
    $form_form_file = get_content("forms", "header", "form_file");
    $form_form_btn = get_content("forms", "header", "form_btn");
    $form_image = get_content("forms", "header", "form_image");
    $form_callback = "<div class='blk-form'>
                        ".($form_image ? "<div class='blk-form-background'><img src='{$form_image}'></div>" : "")."
                        <div class='blk-form-wrapper'>
                            <div class='blk-left'>
                                <svg width='155' height='171' viewBox='0 0 155 171' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M35.3714 27.1353C27.8243 57.7774 32.9147 91.4582 57.0997 113.592C81.4309 135.92 114.327 135.075 139.725 116.067C139.725 116.067 139.529 116.213 139.383 116.018C133.721 116.598 127.424 117.958 122.058 115.268C119.131 113.801 120.941 109.703 124.112 110.682C128.796 112.054 133.043 111.619 137.631 111.233C140.316 110.748 144.66 109.63 146.952 111.877C149.244 114.124 147.435 117.002 146.408 119.295C143.913 124.514 141.419 129.734 138.437 134.709C137.263 136.807 134.141 135.486 134.827 133.144C136.1 129.143 138.056 125.241 139.817 121.484C140.061 120.997 140.306 120.509 140.55 120.021C119.2 142.401 81.7688 139.63 58.4094 121.451C29.3931 98.9707 22.0136 60.6022 30.2476 26.398C30.8853 23.1775 36.2045 23.7685 35.3714 27.1353Z' fill='#1E92FD'/></svg>
                                ".($form_text_title ? "<div class='blk-left-title'>{$form_text_title}</div>" : "")."
                                ".($form_title_list ? "<div class='blk-left-second'>{$form_title_list}</div>" : "")."
                                ".($form_list ? "<div class='blk-left-list'>{$form_list}</div>" : "")."
                            </div>
                            <div class='blk-right'>
                                <form class='form ajax' method='post' action='/actions/send.php'>
                                    ".inputDefault("consut")."
                                    ".($form_form_title ? "<div class='form-ttl'>{$form_form_title}</div>" : "")."
                                    ".($form_form_subtitle ? "<div class='form-phone'>{$form_form_subtitle}</div>" : "")."
                                    <div class='form-input'>
                                        <input type='text' name='phone' value='' placeholder='+7 (905) 111-22-33'>
                                    </div>
                                    <div class='form-input'>
                                        <input type='text' name='time' value='' placeholder='Укажите удобное время для звонка'>
                                    </div>
                                    ".($form_form_text ? "<div class='form-second'>{$form_form_text}</div>" : "")."
                                    ".($form_form_file ? "<div class='form-input form-file'>
                                        <span class='file-text' data-clear='".str_replace("'", "", $form_form_file)."<br><span>необязательное поле</span>'>{$form_form_file}<br><span>необязательное поле</span></span>
                                        <span class='btn-span'>выбрать</span>
                                        <input type='file' id='file' name='file[]'>
                                    </div>" : "")."
                                    <div class='form-input form-btn'>
                                        <a href='#' class='btn btn-ajax'  onclick=\"yMetrika('consultationform_sent')\"><span>{$form_form_btn}</span></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>";
    $modal_title = get_content("forms", "modal", "title");
    $modal_text = get_content("forms", "modal", "text");
    $modal_btn_name = get_content("forms", "modal", "btn_name");
    $modal_btn_link = get_content("forms", "modal", "btn_link");


    # COLORS
    $color_items = get_content("main", "design", "items");
    $color_text = get_content("main", "design", "text");
    $color_a = get_content("main", "design", "a");

    # SIZES TEXT
    $types = array("index", "main", "test_end", "forms");
    $sizes = array();
    foreach ($types as $type) {
        $content = orderArray(file_get_contents($DOCUMENT_ROOT."/data/{$type}.json"), 1);
        foreach ($content as $zone_key => $blocks) {
            foreach ($blocks as $block_key => $block) {
                if($block['type'] == "textarea_size"){
                	$class = "";
                	if($block['style']){
                		foreach ($block['style'] as $cls) $class .= ($class ? " " : "").$cls;
                	}

                    $k = "{$type}_{$zone_key}_{$block_key}";
                    $vals = $block['value'];
                    foreach ($vals as $key =>$val) {
                        $vals[$key]['value'] = get_string($vals[$key]['value'], $class);
                        $vals[$key]['value'] = str_replace("'", "\'", $vals[$key]['value']);
                        $vals[$key]['value'] = str_replace('"', "\"", $vals[$key]['value']);
                    }
                    $sizes[$k] = $vals;
                }
            }
        }
    }

    $css = "";
    if($color_items){
        $css .= "
            a.btn.btn-inverse { border-color: {$color_items}; }
            a.btn.btn-inverse span { color: {$color_items}; }
            a.btn.btn-inverse:hover { background: {$color_items}; }
            .add-kp-text:before, .add-kp-text:after { background: {$color_items}; }
            .color { color: {$color_items}; }
            a.btn { background: {$color_items}; }
            a.btn:hover { box-shadow: 0px 7px 25px {$color_items}; }
            .swiper-pagination-bullet-active { background: {$color_items}; }
            span.btn-span { background: {$color_items}; }
            .swiper-button-next svg [fill],
            .swiper-button-prev svg [fill] { fill: {$color_items}; }
            .blk-left > svg [fill] { fill: {$color_items}; }
            .window:not(.can-next) .next-step, form:not(.can-send) .btn-ajax,
            form:not(.can-send) .text-blk-btn input,
            .inputs-bottom:not(.can-send) .btn,
            #test:not(.can-next) .test-btns .next-step { background: rgba(".hexToRgb($color_items, ".3").") }
            .form-input input,
            .form-file { border-color: rgba(".hexToRgb($color_items, ".3")."); }
            .input-icon { border-color: {$color_items}; }
            .input-icon:before { background: {$color_items}; }
            .input-icon svg [fill],
            .kp-text-inline svg [fill] { fill: {$color_items}; }
            .radio-bot,
            .input-bot input { border-color: {$color_items}; }
            .progressbar-text { color: {$color_items} !important;}
            @keyframes arrow-color {
                0% { fill-opacity: 1; fill: {$color_items} }
                20% { fill-opacity: 0.5; fill: #7A7676; }
                40% { fill-opacity: 1; fill: {$color_items}; }
                to { fill-opacity: 1; fill: {$color_items}; }
            }
            .test-clcl span { background: {$color_items}; }
            .radio-bot:before { background: {$color_items}; }
            .text-blk-input input[type='submit'] { background: {$color_items}; }
            .text-blk-input [name='phone'] { border-color: {$color_items}; }
            .sc-list span { color: {$color_items}; }
            .good-icon svg [fill],
            .mobile-btn svg [fill] { fill: {$color_items}; }
        ";
    }
    if($color_text){
        $css .= "
            body { color: {$color_text}; }
            a { color: {$color_text}; border-bottom-color: {$color_text};}
            span.file-text { color: rgba(".hexToRgb($color_text, ".5").");}
            .phone-text > span {border-color: rgba(".hexToRgb($color_text, ".5")."); }
            .swiper-itme:after {box-shadow: 0px 0 0px 2px {$color_text} inset; }
            .input-default input { color: {$color_text}; }
            .step-blk { background: {$color_text}; }
            #page .slider-mini { color: {$color_text}; }
            .checkbox textarea::placeholder,
            .checkbox-text textarea { color: {$color_text}; }
            input::placeholder{ color: rgba(".hexToRgb($color_text, ".3")."); }
            .page-title-icon [stroke] { stroke: {$color_text}; }
            #footer-mobile { background: {$color_text}; }
            .blk-left-list > span svg [fill] { fill: {$color_text}; }
            .akp-name svg [fill] { fill: {$color_text}; }
        ";
    }
    if($color_a){
        $css .= "span.editor a { color: {$color_a};}";
    }

    $test_blk = "
        .test-info { background: {$test_blk_background}; }
        .ti-num { color: {$test_blk_background}; }
        .ti-title, .ti-bottom { color: {$test_blk_color1}; }
        .ti-second, .ti-txt { color: {$test_blk_color2}; }
        .ti-num { background: {$test_blk_color2}; }
        .test-data svg [fill='#1E92FD'] { fill: {$test_blk_background}; }
        .test-data svg [fill='#093445'] { fill: {$test_blk_color1}; }
        .test-data svg [fill='#fff'] { fill: {$test_blk_color2}; }
    ";


    $result_page = get_result();
?>
<!DOCTYPE html>
<html lang="ru" <?=($_GET['editor'] ? "class='editor'" : "")?>>
<head>
    <title><?=$title?></title>
    <meta name='Description' content='<?=$keywords?>'>
    <meta name='Keywords' content='<?=$description?>'>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?=$meta?>
    <link rel="icon" href="<?=$favicon?>" type="image/x-icon" async>
    <?=get_font()?>
    <link rel="stylesheet" async href="/static/css/standart.css?v=<?=filectime("{$DOCUMENT_ROOT}/static/css/standart.css")?>">
    <link rel="stylesheet" async href="/static/css/style.css?v=<?=filectime("{$DOCUMENT_ROOT}/static/css/style.css")?>">
    <style>
        <?=$test_blk?>
        <?=$css?>
        <?=($background_color ? "#interactive:before { background-color: {$background_color} }": "")?>
        <?=($background_link ? "#interactive:before { background-image: url({$background_link}); }" : "")?>
        <?=($flash_text_color ? "@keyframes shadow {
            0% { text-shadow: 0px 0px 15px {$flash_text_color}; }
            50% { text-shadow: 0px 0px 0px {$flash_text_color}50; }
            to { text-shadow: 0px 0px 15px {$flash_text_color}; }
        }" : "")?>
        <?=($step_color ? ".step.active span { background: {$step_color}; } .step.active:before { background: rgba(".hexToRgb($step_color, ".4")."); }" : "")?>
        <?=($callback_color ? ".phone-text:before, a.phone-mobile:before { background: radial-gradient(50% 50% at 50% 50%, {$callback_color}47 0%, {$callback_color} 100%); } " : "")?>
    </style>
</head>
<body class='close'>
    <div id="site">

        <header id="header">
            <div class="container">
                <div class="logo">
                    <?=($logo ? "<img src='{$logo}' alt='{$title}' />" : "")?>
                    <?=($podskazka ? "<div class='logo-text' data-size='index_header_podskazka'>{$podskazka}</div>" : "")?>
                </div>
                <div class="header-right">
                    <a href='tel:+79507574096' class='phone'>+7 950 757-40-96</a>
                    <?=($callback_name ? "<span href='#' class='phone-text' onclick=\"yMetrika('click_consultation')\" data-micromodal-open='modal-1'><span>{$callback_name}</span></span>" : "")?>
                </div>
            </div>
        </header>

        <div class='mobile-btn'>
            <svg width="30" height="18" viewBox="0 0 30 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19 15.876L25.5243 9L19 2.124L21.0049 2.62905e-07L29.5 9L21.0049 18L19 15.876Z" fill="#1E92FD"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 15.876L16.0243 9L9.5 2.124L11.5049 2.62905e-07L20 9L11.5049 18L9.5 15.876Z" fill="#1E92FD"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.78529e-07 15.876L6.52427 9L2.08189e-06 2.124L2.00486 2.62905e-07L10.5 9L2.00485 18L2.78529e-07 15.876Z" fill="#1E92FD"/>
            </svg>
        </div>

        <div id="header-mobile">
            <div class="logo-mobile">
                <?=($logo ? "<img src='{$logo}' alt='{$title}' />" : "")?>
                <?=($podskazka ? "<div class='logo-text-mobile' data-size='index_header_podskazka'>{$podskazka}</div>" : "")?>
                <div class="logo-btn-mobile">
                    <svg width="23" height="17" viewBox="0 0 23 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.2002 2.22898L3.63282 8.55174L9.20019 14.8745L7.48939 16.8276L0.240194 8.55174L7.48939 0.275879L9.2002 2.22898Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.0264 2.22898L10.459 8.55174L16.0264 14.8745L14.3156 16.8276L7.06636 8.55174L14.3156 0.275879L16.0264 2.22898Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.8525 2.22898L17.2852 8.55174L22.8525 14.8745L21.1417 16.8276L13.8925 8.55174L21.1417 0.275879L22.8525 2.22898Z" fill="black"/>
                    </svg>
                </div>
            </div>
            <div class="header-mobile-bottom">
                <?=($callback_name ? "<span href='#' class='phone-text-mobile' onclick=\"yMetrika('click_consultation')\" data-micromodal-open='modal-1'><span>{$callback_name}</span></span>" : "")?>
                <a href='tel:+79507574096' class='phone-mobile'>+7 950 757-40-96</a>
            </div>
        </div>

        <div id="main">
            <div class="container">
                <div class="main-left">
                    <?=($h1 ? "<h1 data-size='index_left_h1'>{$h1}</h1>" : "")?>
                    <?=($h1_sub ? "<div class='h1-sub' data-size='index_left_h1_sub'>{$h1_sub}</div>" : "")?>

                    <div class="swiper-main swiper-main-mob"><?=$slider_right?></div>
                    <?=$advantage?>
                    <?=($flash_text ? "<div class='text-flash' data-size='index_left_flash_text'>{$flash_text}</div>" : "")?>
                    <div class="t-wpr">
                        <?=($btn_top ? "<div class='kp-text'><div class='kp-text-inline'>{$btn_top}<svg width='93' height='92' fill='none' xmlns='http://www.w3.org/2000/svg'><g clip-path='url(#clip0)'><path d='M60.019.916c10.7 29.125 1.558 61.575-21.698 81.34.854.933 1.658 1.895 2.51 2.83.375.432.45.905.294 1.316.592.244.392 1.26-.312 1.272-2.168-.06-4.412-.14-6.635-.297-1.01-.072-2.761.148-3.58-.612-.82-.76-.54-2.208-.515-3.189.053-2.415.084-4.753.137-7.168.026-.981 1.353-1.605 2.046-.838 1.388 1.533 2.747 3.018 4.085 4.58 22.896-18.916 31.93-50.53 22.153-78.826-.35-.961 1.192-1.32 1.515-.408z' fill='#1E92FD'/></g><defs><clipPath id='clip0'><path fill='#fff' transform='scale(-1 1) rotate(-60.44 3.8 108.672)' d='M0 0h67v68H0z'/></clipPath></defs></svg></div></div>" : "")?>
                        <div class="t-btn">
                            <a class='btn' href="#" data-href='#test'>
                                <span><?=$btn?></span>
                            </a>
                            <div class='btn-arrow' data-href='#test'>
                                <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.81248 0L7.68 5.99933L13.5475 0L15.36 1.84354L7.68 9.65518L0 1.84354L1.81248 0Z" fill="black"></path></svg>
                            </div>
                        </div>
                        <?=($btn_bottom ? "<div class='add-kp-text'>{$btn_bottom}</div>" : "")?>
                        <?=$akps?>
                    </div>
                    <?=($advantage ? "<div class='avs-bottom'>{$advantage}</div>" : "")?>
                </div>
                <div class="main-right">
                    <div class="swiper-main"><?=$slider_right?></div>
                </div>
            </div>
        </div>
        <?=($slider_check && $bottom_slider ? "<div id='main-slider'><div class='container'>{$bottom_slider}</div></div>" : "")?>
        <div id="interactive">
            <div id="test" data-step='1'>
                <div class="container">
                    <div class="test-title"><?=$test_title?></div>
                    <div class="steps"><?=$test['tabs']?></div>
                    <div class="windows" data-step='1'>
                        <form method="post" action="/actions/send.php">
                            <?=inputDefault("test")?>
                            <?=$test['content']?>
                            <?=get_end_test()?>
                        </form>
                    </div>
                    <div class="creator">
                        <div class="creator-left">
                            <div class="creator-text"><?=$client_name?><br><?=$rekvizity?></div>
                            <a href='<?=$politika_link?>' target='_blank'>Политика конфиденциальности</a>
                        </div>
                        <div class="creator-right">
                            <a href='<?=$creator_link?>' target='_blank'><?=$creator_name?></a>
                        </div>
                    </div>
                </div>
                <div class='test-btns'>
                    <div class="test-btns-left">
                        <div class='test-data test-data-main'>
                            <svg width='60' height='75' fill='none' xmlns='http://www.w3.org/2000/svg'><rect width='60' height='75' rx='1.367' fill='#1E92FD'/><path d='M6.466 63.484v-1.626h.984v1.626h.311V61.57H6.154v1.913h.312zm1.97-1.107v-.205h-.26v1.913h.293v-.77c.09.103.243.204.472.204.394 0 .65-.314.65-.691 0-.377-.256-.692-.65-.692a.626.626 0 00-.505.241zm.858.45c0 .258-.17.436-.407.436a.46.46 0 01-.418-.276v-.32a.462.462 0 01.418-.274c.238 0 .407.178.407.435zm.5 0c0 .455.345.692.711.692.369 0 .71-.237.71-.691 0-.454-.341-.692-.71-.692-.366 0-.71.238-.71.692zm1.124 0c0 .258-.17.425-.413.425-.246 0-.416-.167-.416-.424s.17-.424.416-.424c.243 0 .413.167.413.424zm.708 1.01v-.353h1.055v.352h.276v-.593h-.194v-1.071h-1.025c.005.456-.05.79-.208 1.071h-.18v.593h.276zm.385-1.425h.456v.831h-.63c.108-.232.163-.478.174-.83zm1.104.416c0 .454.344.691.71.691.37 0 .71-.237.71-.691 0-.454-.34-.692-.71-.692-.366 0-.71.238-.71.692zm1.123 0c0 .257-.17.424-.413.424-.246 0-.415-.167-.415-.424s.17-.424.415-.424c.244 0 .413.167.413.424zm1.077-.407h.443v1.063h.292v-1.312h-1.011c-.025.65-.085 1.088-.306 1.088a.245.245 0 01-.085-.017v.23a.513.513 0 00.18.035c.35 0 .449-.453.487-1.087zm3.01 1.063l-.488-.659.437-.653h-.317l-.358.541h-.167v-.541h-.292v.541h-.164l-.358-.541h-.32l.44.653-.49.659h.32l.391-.522h.18v.522h.293v-.522h.183l.391.522h.32zm1.306-.303v-.47c0-.397-.235-.575-.607-.575-.21 0-.399.052-.502.088v.268c.117-.052.278-.101.472-.101.224 0 .345.114.345.278v.047a1.742 1.742 0 00-.342-.038c-.273 0-.585.104-.585.44 0 .27.222.401.46.401a.762.762 0 00.494-.169c.047.107.142.153.262.153a.51.51 0 00.186-.033v-.21a.31.31 0 01-.087.016c-.052 0-.096-.022-.096-.096zm-.576-.298c.092 0 .204.016.284.032v.222a.534.534 0 01-.38.156c-.131 0-.246-.066-.246-.194 0-.164.175-.216.341-.216zm2.002-1.006v-.238a.704.704 0 01-.4.115.704.704 0 01-.398-.115v.238a.68.68 0 00.399.112.68.68 0 00.399-.112zm-.77 1.607l.702-.858v.858h.292v-1.312h-.251l-.703.858v-.858h-.292v1.312h.251zm1.97 0v-1.055h.45v-.257H21.51v.257h.454v1.055h.292zm.562-.656c0 .462.342.691.744.691.183 0 .35-.032.47-.09v-.251a.962.962 0 01-.416.093c-.24 0-.47-.099-.503-.334h.987c.085-.38-.096-.8-.618-.8a.657.657 0 00-.664.69zm1.02-.11h-.727c.01-.22.188-.338.385-.338.287 0 .358.224.342.339zm1.118.11c0 .454.345.691.71.691.37 0 .711-.237.711-.691 0-.454-.341-.692-.71-.692-.366 0-.71.238-.71.692zm1.123 0c0 .257-.169.424-.412.424-.246 0-.416-.167-.416-.424s.17-.424.416-.424c.243 0 .413.167.413.424zm1.157.656v-1.055h.45v-.257h-1.196v.257h.453v1.055h.293zm1.45 0c.303 0 .432-.159.432-.36a.307.307 0 00-.224-.304.309.309 0 00.186-.298c0-.205-.15-.35-.416-.35h-.749v1.312h.771zm-.478-.558h.413c.1 0 .202.036.202.17 0 .126-.088.167-.208.167h-.407v-.337zm0-.533h.39c.09 0 .186.039.186.167 0 .118-.082.159-.185.159h-.391v-.326zm1.123.435c0 .462.341.691.743.691.183 0 .35-.032.47-.09v-.251a.96.96 0 01-.415.093c-.24 0-.47-.099-.503-.334h.986c.085-.38-.095-.8-.617-.8a.658.658 0 00-.664.69zm1.02-.11h-.728c.011-.22.189-.338.386-.338.287 0 .358.224.341.339zm1.642.766v-1.312H31.7v.634a.76.76 0 01-.282.05c-.183 0-.3-.058-.3-.25v-.434h-.293v.46c0 .33.216.48.509.48a.997.997 0 00.366-.063v.435h.293zm1.484-.303v-.47c0-.397-.235-.575-.607-.575-.21 0-.399.052-.503.088v.268c.118-.052.28-.101.473-.101.224 0 .345.114.345.278v.047a1.74 1.74 0 00-.342-.038c-.273 0-.585.104-.585.44 0 .27.222.401.46.401a.762.762 0 00.494-.169c.047.107.142.153.262.153a.51.51 0 00.186-.033v-.21a.31.31 0 01-.087.016c-.052 0-.096-.022-.096-.096zm-.577-.298c.093 0 .205.016.285.032v.222a.533.533 0 01-.38.156c-.131 0-.246-.066-.246-.194 0-.164.175-.216.341-.216zm1.537.6V62.43h.45v-.257h-1.196v.257h.453v1.055h.293zm1.33 0c.33 0 .52-.19.52-.436 0-.25-.19-.438-.52-.438h-.358v-.437h-.293v1.312h.65zm-.358-.644h.3c.173 0 .28.07.28.208 0 .134-.107.205-.28.205h-.3v-.413zm2.071.645v-.528h.656v.528h.292v-1.312h-.292v.528h-.656v-.528h-.292v1.312h.292zm2.432-.303v-.47c0-.397-.235-.575-.606-.575-.211 0-.4.052-.503.088v.268c.117-.052.278-.101.472-.101.224 0 .345.114.345.278v.047a1.742 1.742 0 00-.342-.038c-.273 0-.585.104-.585.44 0 .27.222.401.46.401a.762.762 0 00.494-.169c.047.107.142.153.262.153a.51.51 0 00.186-.033v-.21a.31.31 0 01-.087.016c-.052 0-.096-.022-.096-.096zm-.576-.298c.092 0 .204.016.284.032v.222a.534.534 0 01-.38.156c-.131 0-.246-.066-.246-.194 0-.164.175-.216.342-.216zm2.396.6c.304 0 .432-.158.432-.36a.307.307 0 00-.224-.303.309.309 0 00.186-.298c0-.205-.15-.35-.416-.35h-.748v1.312h.77zm-.478-.557h.413c.1 0 .202.036.202.17 0 .126-.087.167-.208.167h-.407v-.337zm0-.533h.39c.091 0 .187.039.187.167 0 .118-.082.159-.186.159h-.39v-.326zm1.123.435c0 .454.344.691.71.691.37 0 .711-.237.711-.691 0-.454-.341-.692-.71-.692-.367 0-.711.238-.711.692zm1.123 0c0 .257-.17.424-.412.424-.246 0-.416-.167-.416-.424s.17-.424.416-.424c.243 0 .412.167.412.424zm.874.656v-1.055h.656v1.055h.293v-1.312h-1.241v1.312h.292zm1.574-1.107v-.205h-.26v1.913h.293v-.77c.09.103.243.204.473.204.393 0 .65-.314.65-.691 0-.377-.257-.692-.65-.692a.626.626 0 00-.506.241zm.858.45c0 .258-.17.436-.407.436a.46.46 0 01-.418-.276v-.32a.461.461 0 01.418-.274c.238 0 .407.178.407.435zm.5 0c0 .455.345.692.711.692.37 0 .71-.237.71-.691 0-.454-.34-.692-.71-.692-.366 0-.71.238-.71.692zm1.124 0c0 .258-.17.425-.413.425-.246 0-.415-.167-.415-.424s.17-.424.415-.424c.243 0 .413.167.413.424zm.5.012c0 .467.358.68.707.68.167 0 .31-.03.435-.09v-.27a.787.787 0 01-.383.1c-.235 0-.462-.133-.462-.431 0-.309.235-.432.468-.432.134 0 .268.041.363.11v-.268a.794.794 0 00-.404-.102c-.35 0-.724.214-.724.703zm2.085.645c.33 0 .52-.191.52-.437 0-.25-.19-.438-.52-.438h-.358v-.437h-.292v1.312h.65zm-.358-.645h.3c.173 0 .28.07.28.208 0 .134-.107.205-.28.205h-.3v-.413zm1.405.645v-1.312h-.293v1.312h.293zM8.12 10.348c0 .779.508 1.492 1.524 1.492 1.017 0 1.525-.713 1.525-1.492s-.508-1.492-1.525-1.492c-1.016 0-1.525.713-1.525 1.492zm2.418 0c0 .504-.3.922-.894.922-.598 0-.897-.418-.897-.922s.299-.922.897-.922c.595 0 .894.418.894.922zm.912.48c0 .7.537 1.012 1.074 1.012.25 0 .471-.049.664-.13v-.505a1.107 1.107 0 01-.553.143c-.308 0-.603-.172-.603-.549 0-.398.3-.55.61-.55.19 0 .386.058.526.148v-.5a1.3 1.3 0 00-.607-.143c-.545 0-1.111.32-1.111 1.074zm3.149.955v-1.488h.627v-.48h-1.82v.48h.627v1.488h.566zm2.664-.508v-.628c0-.647-.377-.89-.968-.89a2.57 2.57 0 00-.762.132v.492c.184-.074.426-.148.697-.148.307 0 .463.136.463.332v.07a2.146 2.146 0 00-.467-.053c-.394 0-.865.155-.865.656 0 .39.32.602.688.602.291 0 .517-.09.71-.242.081.148.233.222.434.222a.96.96 0 00.316-.058v-.373a.338.338 0 01-.107.017c-.074 0-.14-.029-.14-.131zm-.906-.353c.119 0 .25.025.336.045v.308a.65.65 0 01-.443.16c-.172 0-.307-.095-.307-.242 0-.201.205-.271.414-.271zm2.33-.656h.505v1.517h.57V9.815h-1.6c-.036.882-.106 1.525-.418 1.525a.325.325 0 01-.119-.024v.45a.935.935 0 00.312.058c.541 0 .693-.672.75-1.558zm1.447.533c0 .689.516 1.041 1.086 1.041.574 0 1.086-.352 1.086-1.041s-.512-1.041-1.086-1.041c-.57 0-1.086.352-1.086 1.041zm1.594 0c0 .303-.18.533-.508.533-.324 0-.504-.23-.504-.533 0-.303.18-.533.504-.533.328 0 .508.23.508.533zm.84.029c0 .7.537 1.012 1.074 1.012.25 0 .471-.049.664-.13v-.505a1.107 1.107 0 01-.553.143c-.308 0-.603-.172-.603-.549 0-.398.3-.55.61-.55.19 0 .386.058.525.148v-.5a1.3 1.3 0 00-.606-.143c-.545 0-1.111.32-1.111 1.074zm3.157.955c.517 0 .795-.3.795-.676 0-.378-.278-.677-.795-.677h-.455v-.615h-.57v1.968h1.025zm-.459-.943h.32c.225 0 .348.098.348.267 0 .168-.123.266-.348.266h-.32v-.533zm2.62-1.849l-.098 1.62c.241-.087.5-.12.7-.12.447 0 .681.136.681.41 0 .271-.27.402-.635.402-.28 0-.603-.078-.828-.221v.57c.245.119.573.188.897.188.615 0 1.181-.283 1.181-.93 0-.64-.463-.89-1.02-.89-.116 0-.243.017-.361.041l.036-.545 1.218.004v-.529h-1.771zm4.405 2.792c.496 0 .66-.254.66-.533 0-.238-.135-.394-.348-.463a.448.448 0 00.303-.451c0-.267-.18-.52-.648-.52h-1.172v1.967h1.205zm-.635-.816h.467c.14 0 .263.05.263.217 0 .156-.12.21-.263.21h-.467v-.427zm-.004-.762h.43c.131 0 .25.049.25.213 0 .152-.11.205-.246.205h-.434v-.418zm1.573.594c0 .689.517 1.041 1.087 1.041.574 0 1.086-.352 1.086-1.041s-.512-1.041-1.086-1.041c-.57 0-1.087.352-1.087 1.041zm1.595 0c0 .303-.18.533-.508.533-.324 0-.505-.23-.505-.533 0-.303.18-.533.505-.533.328 0 .508.23.508.533zm1.52.984v-1.488h.795v1.488h.57V9.815H35.77v1.968h.57zm2.352-1.68v-.288h-.504v2.87h.57v-1.111c.127.14.336.266.648.266.623 0 .96-.504.96-1.041 0-.537-.337-1.041-.96-1.041-.365 0-.59.168-.713.344zm1.091.696c0 .308-.193.562-.517.562a.561.561 0 01-.512-.328v-.468a.561.561 0 01.512-.328c.324 0 .517.255.517.562zm.845 0c0 .689.516 1.041 1.086 1.041.574 0 1.087-.352 1.087-1.041s-.513-1.041-1.087-1.041c-.57 0-1.086.352-1.086 1.041zm1.594 0c0 .303-.18.533-.508.533-.324 0-.504-.23-.504-.533 0-.303.18-.533.504-.533.328 0 .508.23.508.533zm.84.029c0 .7.537 1.012 1.074 1.012.25 0 .471-.049.664-.13v-.505a1.107 1.107 0 01-.553.143c-.308 0-.603-.172-.603-.549 0-.398.3-.55.61-.55.19 0 .386.058.526.148v-.5a1.3 1.3 0 00-.607-.143c-.545 0-1.111.32-1.111 1.074zm1.998-.029c0 .689.516 1.041 1.086 1.041.574 0 1.087-.352 1.087-1.041s-.513-1.041-1.087-1.041c-.57 0-1.086.352-1.086 1.041zm1.594 0c0 .303-.18.533-.508.533-.324 0-.504-.23-.504-.533 0-.303.18-.533.504-.533.328 0 .508.23.508.533zm2.156.984c.496 0 .66-.254.66-.533 0-.238-.135-.394-.349-.463a.448.448 0 00.304-.451c0-.267-.18-.52-.648-.52h-1.172v1.967h1.205zm-.636-.816h.468c.14 0 .262.05.262.217 0 .156-.119.21-.262.21h-.468v-.427zm-.004-.762h.43c.132 0 .25.049.25.213 0 .152-.11.205-.245.205h-.435v-.418z' fill='#093445'/><g><path d='M18.075 14.926v2.55h1.086c.405 0 .97-.127.97-.706 0-.397-.274-.572-.503-.638a.56.56 0 00.4-.561c0-.434-.342-.645-.874-.645h-1.079zm1.633 1.8c0 .354-.302.386-.587.386h-.63v-.776h.689c.23 0 .528.051.528.39zm-.102-1.09c0 .248-.197.35-.437.35l-.678-.003v-.696h.62c.305 0 .495.094.495.35zm1.77 1.84c.44 0 .692-.254.692-.582 0-.332-.252-.583-.693-.583h-.477v-.583h-.39v1.749h.867zm-.478-.86h.4c.23 0 .373.096.373.278 0 .178-.143.273-.372.273h-.401v-.55zm1.873.86v-1.748h-.39v1.749h.39zm1.739 0V16.07h.875v1.407h.39v-1.75H24.12v1.75h.39zm1.643-.874c0 .605.46.922.948.922.492 0 .947-.317.947-.922s-.455-.922-.947-.922c-.489 0-.948.317-.948.922zm1.498 0c0 .343-.226.565-.55.565-.328 0-.554-.222-.554-.565 0-.342.226-.565.554-.565.324 0 .55.223.55.565zm1.436-.543h.59v1.418h.39v-1.75h-1.349c-.032.868-.112 1.451-.408 1.451a.326.326 0 01-.113-.022v.306a.685.685 0 00.24.048c.467 0 .599-.605.65-1.45zm2.254.988l-.59-1.32h-.415l.83 1.764-.036.088c-.102.273-.244.408-.434.408a.605.605 0 01-.178-.025v.331c.076.018.153.03.23.03.265 0 .513-.154.703-.61l.82-1.985h-.409l-.52 1.319zm2.67.43v-1.75h-.39v.846c-.116.044-.229.066-.375.066-.244 0-.4-.077-.4-.332v-.58h-.39v.613c0 .44.288.641.677.641.197 0 .34-.029.489-.084v.58h.39zm.825 0l.937-1.144v1.144h.39v-1.75h-.336l-.936 1.145v-1.144h-.39v1.749h.335zm2.627 0V16.07h.601v-.342h-1.596v.342h.605v1.407h.39zm.75-.875c0 .616.456.922.992.922a1.5 1.5 0 00.627-.12v-.335c-.15.073-.343.124-.554.124-.321 0-.627-.132-.67-.445h1.315c.113-.507-.128-1.068-.824-1.068a.877.877 0 00-.885.922zm1.36-.146h-.97c.015-.295.252-.451.514-.451.383 0 .478.298.456.451zm1.206-.371c0-.16-.113-.237-.248-.237-.128 0-.244.076-.244.237 0 .16.116.237.244.237.135 0 .248-.077.248-.237zm0 1.191c0-.156-.113-.233-.248-.233-.128 0-.244.077-.244.233 0 .164.116.24.244.24.135 0 .248-.076.248-.24z' fill='#fff'/></g><g><path d='M12.94 30.093v-1.156h.59l.835 1.155h.35l-.944-1.308.867-1.242h-.35l-.768 1.104h-.58v-1.104h-.306v2.55h.306zm1.81-.875c0 .598.46.918.934.918.477 0 .932-.32.932-.918 0-.598-.455-.918-.932-.918-.474 0-.933.32-.933.918zm1.575 0c0 .404-.284.641-.641.641-.357 0-.642-.237-.642-.641 0-.405.284-.641.642-.641.357 0 .641.236.641.64zm.987.875v-1.32l.568.864h.219l.568-.864v1.32h.277v-1.75h-.317l-.638.97-.637-.97h-.317v1.75h.277zm2.448 0v-1.32l.568.864h.22l.568-.864v1.32h.277v-1.75h-.317l-.638.97-.638-.97h-.317v1.75h.277zm2.051-.875c0 .623.452.918.97.918.23 0 .459-.043.62-.127v-.285c-.15.084-.362.143-.577.143-.353 0-.685-.154-.721-.521h1.392c.11-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.425-.128h-1.133c.022-.342.299-.52.597-.52.445 0 .562.334.536.52zm.947-.455v-.292h-.262v2.551h.291V29.83c.128.15.343.306.678.306.5 0 .875-.393.875-.918s-.376-.918-.875-.918a.897.897 0 00-.707.335zm1.29.583c0 .4-.277.649-.616.649a.708.708 0 01-.645-.43v-.438a.708.708 0 01.645-.43c.34 0 .616.248.616.649zm2.078.875v-1.75h-.292v.886a1.073 1.073 0 01-.433.076c-.292 0-.47-.083-.47-.379v-.583h-.292v.605c0 .43.273.627.674.627.219 0 .36-.03.521-.091v.608h.292zm.42-.875c0 .623.452.918.97.918.23 0 .459-.043.62-.127v-.285c-.15.084-.362.143-.577.143-.353 0-.685-.154-.721-.521h1.392c.11-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.425-.128h-1.133c.022-.342.299-.52.597-.52.445 0 .562.334.536.52zm.565.135c0 .623.477.911.936.911.219 0 .405-.04.569-.12v-.291a1.06 1.06 0 01-.529.142c-.342 0-.685-.2-.685-.65 0-.458.354-.648.693-.648.186 0 .371.055.503.157v-.284a.986.986 0 00-.54-.142c-.455 0-.947.287-.947.925zm2.223.867v-.75h.342l.642.75h.342l-.743-.878.663-.87h-.339l-.554.728h-.353v-.729h-.292v1.75h.292zm1.407-.874c0 .598.459.918.932.918.478 0 .933-.32.933-.918 0-.598-.455-.918-.933-.918-.473 0-.932.32-.932.918zm1.574 0c0 .404-.284.641-.642.641-.357 0-.64-.237-.64-.641 0-.405.283-.641.64-.641.358 0 .642.236.642.64zm.59 0c0 .623.451.918.969.918.23 0 .459-.043.62-.127v-.285c-.15.084-.362.143-.577.143-.353 0-.685-.154-.721-.521h1.392c.11-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.424-.128h-1.133c.022-.342.299-.52.598-.52.444 0 .56.334.535.52zM12.86 34.093v-1.48h1.013v1.48h.292v-1.75h-1.596v1.75h.291zm2.106-1.458v-.292h-.262v2.551h.291V33.83a.86.86 0 00.678.306c.5 0 .875-.393.875-.918s-.376-.918-.875-.918a.897.897 0 00-.707.335zm1.29.583c0 .4-.277.649-.616.649a.708.708 0 01-.645-.43v-.438a.708.708 0 01.645-.43c.34 0 .616.248.616.649zm.59 0c0 .623.453.918.97.918.23 0 .46-.044.62-.127v-.285c-.15.084-.361.143-.576.143-.354 0-.685-.153-.722-.521h1.392c.11-.496-.138-1.046-.812-1.046-.416 0-.871.306-.871.918zm1.426-.128h-1.134c.022-.342.3-.52.598-.52.445 0 .561.334.536.52zm.765 1.476v-.474h1.524v.474h.284v-.736h-.255v-1.487h-1.305c.007.653-.08 1.1-.299 1.487h-.233v.736h.284zm.529-1.96h.732v1.224h-.995c.16-.335.248-.7.263-1.224zm2.288.007h.71v1.48h.292v-1.75h-1.287c-.033.94-.135 1.53-.444 1.53a.345.345 0 01-.117-.021v.23c.077.029.146.04.211.04.452 0 .576-.612.634-1.509zm1.42.605c0 .598.459.918.933.918.477 0 .933-.32.933-.918 0-.598-.456-.918-.933-.918-.474 0-.933.32-.933.918zm1.574 0c0 .404-.284.641-.641.641-.357 0-.642-.237-.642-.641 0-.405.285-.641.642-.641.357 0 .641.236.641.64zm3.077.874l-.696-.878.616-.87h-.325l-.532.746h-.266v-.747h-.291v.747h-.266l-.532-.747h-.325l.616.871-.696.879h.328l.594-.733h.28v.733h.292v-.733h.28l.595.733h.328zm.09-.874c0 .623.45.918.968.918.23 0 .46-.044.62-.127v-.285c-.15.084-.36.143-.576.143-.353 0-.685-.153-.721-.521h1.392c.109-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.424-.128h-1.133c.021-.342.298-.52.597-.52.445 0 .561.334.536.52zm.976 1.002v-.74h1.013v.74h.292v-1.749h-.292v.74h-1.013v-.74h-.291v1.75h.291zm2.092 0l1.071-1.279v1.28h.292v-1.75h-.248l-1.072 1.28v-1.28h-.291v1.75h.248zm1.781-.874c0 .623.452.918.97.918.229 0 .459-.044.619-.127v-.285c-.15.084-.36.143-.576.143-.353 0-.685-.153-.721-.521h1.392c.109-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.425-.128H34.58c.022-.342.298-.52.597-.52.445 0 .562.334.536.52z' fill='#fff'/></g><g><path d='M12.35 48.04v.291h.896v2.26h.306v-2.26h.897v-.291h-2.1zm1.881 1.676c0 .623.452.919.97.919.23 0 .459-.044.619-.128v-.284c-.15.084-.36.142-.576.142-.353 0-.685-.153-.721-.521h1.392c.11-.496-.139-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.425-.127h-1.133c.022-.343.299-.521.597-.521.445 0 .562.335.536.52zm1.75-.747l-.474.645-.477-.645h-.336l.645.849-.681.9h.335l.514-.693.51.693h.335l-.678-.9.642-.85h-.335zm.977 1.749v-.74h1.013v.74h.292v-1.75h-.292v.74h-1.013v-.74h-.291v1.75h.291zm2.092 0l1.071-1.28v1.28h.292v-1.75h-.248l-1.072 1.28v-1.28h-.291v1.75h.248zm3.268 0v-1.75h-.292v.886a1.073 1.073 0 01-.433.077c-.292 0-.47-.084-.47-.38v-.582h-.292v.605c0 .43.273.626.674.626.219 0 .361-.029.521-.09v.608h.292zm.42-.875c0 .623.452.919.97.919.23 0 .459-.044.62-.128v-.284c-.15.084-.361.142-.576.142-.354 0-.686-.153-.722-.521h1.392c.11-.496-.138-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.425-.127h-1.133c.022-.343.299-.521.598-.521.444 0 .56.335.535.52zm.565.135c0 .623.477.91.936.91.22 0 .405-.04.569-.12v-.291a1.06 1.06 0 01-.529.142c-.342 0-.685-.2-.685-.649 0-.459.354-.648.693-.648.186 0 .372.054.503.156v-.284a.987.987 0 00-.54-.142c-.455 0-.947.288-.947.925zm2.223.867v-.75h.343l.641.75h.343l-.744-.878.663-.871h-.339l-.553.729h-.354v-.73h-.292v1.75h.292zm1.407-.875c0 .598.459.919.933.919.477 0 .933-.321.933-.919 0-.597-.456-.918-.933-.918-.474 0-.933.32-.933.918zm1.574 0c0 .405-.284.642-.641.642-.358 0-.642-.237-.642-.642 0-.404.284-.641.642-.641.357 0 .641.237.641.641zm.59 0c0 .623.451.919.969.919.23 0 .459-.044.62-.128v-.284c-.15.084-.361.142-.577.142-.353 0-.685-.153-.721-.521h1.392c.11-.496-.138-1.046-.813-1.046-.415 0-.87.306-.87.918zm1.424-.127h-1.133c.022-.343.299-.521.598-.521.444 0 .56.335.535.52zm2.9.532c0-.226-.163-.376-.36-.427.197-.043.324-.182.324-.393 0-.306-.251-.507-.74-.507-.233 0-.451.055-.626.135v.273c.16-.08.386-.138.594-.138.273 0 .48.084.48.277 0 .167-.152.226-.35.226h-.382v.27h.383c.175 0 .386.036.386.25 0 .227-.255.278-.532.278-.211 0-.459-.062-.608-.142v.277c.164.08.397.135.645.135.484 0 .787-.183.787-.514zm1.85.105v-.685c0-.488-.295-.743-.761-.743-.288 0-.529.07-.667.116v.285c.153-.066.368-.131.638-.131.32 0 .499.175.499.437v.062a2.379 2.379 0 00-.485-.055c-.375 0-.79.139-.79.59 0 .369.306.533.615.533.285 0 .507-.088.682-.234.05.153.178.212.32.212.073 0 .15-.015.219-.04v-.237a.44.44 0 01-.135.025c-.076 0-.135-.029-.135-.135zm-1.268-.138c0-.255.277-.332.532-.332.142 0 .32.026.445.051v.321a.824.824 0 01-.598.255c-.193 0-.379-.091-.379-.295zm1.96.977v-.474h1.523v.474h.285v-.737h-.256v-1.486h-1.304c.007.652-.08 1.1-.299 1.486h-.233v.737h.284zm.528-1.961h.733v1.224h-.995c.16-.335.248-.7.262-1.224zm3.09 1.122v-.685c0-.488-.295-.743-.762-.743-.287 0-.528.07-.666.116v.285c.153-.066.367-.131.637-.131.32 0 .5.175.5.437v.062a2.38 2.38 0 00-.485-.055c-.376 0-.791.139-.791.59 0 .369.306.533.616.533.284 0 .506-.088.681-.234.051.153.179.212.321.212.073 0 .15-.015.219-.04v-.237a.441.441 0 01-.135.025c-.077 0-.135-.029-.135-.135zm-1.268-.138c0-.255.277-.332.532-.332.142 0 .32.026.444.051v.321a.824.824 0 01-.597.255c-.193 0-.38-.091-.38-.295zm2.142.503v-.74h1.013v.74h.291v-1.75h-.291v.74h-1.013v-.74h-.292v1.75h.292zm2.091 0l1.072-1.28v1.28h.291v-1.75h-.248l-1.07 1.28v-1.28h-.292v1.75h.247zm1.782-.875c0 .623.452.919.97.919.229 0 .458-.044.619-.128v-.284c-.15.084-.361.142-.576.142-.354 0-.685-.153-.722-.521h1.392c.11-.496-.138-1.046-.812-1.046-.416 0-.871.306-.871.918zm1.425-.127h-1.134c.022-.343.3-.521.598-.521.444 0 .561.335.536.52z' fill='#fff'/></g><g><path d='M12.634 38.93v2.55h1.075c.372 0 .94-.12.94-.7 0-.415-.298-.582-.51-.64.2-.059.408-.241.408-.565 0-.43-.346-.645-.867-.645h-1.046zm1.705 1.818c0 .42-.371.44-.692.44h-.707v-.88h.791c.244 0 .608.043.608.44zm-.101-1.13c0 .292-.234.398-.5.398h-.798v-.795h.714c.361 0 .584.095.584.397zm1.08 1.862l1.071-1.279v1.28h.292v-1.75h-.248l-1.071 1.28v-1.28h-.292v1.75h.248zm3.21-.47c0-.226-.164-.375-.36-.426.196-.044.323-.182.323-.394 0-.306-.25-.506-.74-.506-.232 0-.451.055-.626.135v.273c.16-.08.386-.138.594-.138.273 0 .481.083.481.276 0 .168-.153.227-.35.227h-.383v.27h.383c.175 0 .386.036.386.25 0 .227-.255.278-.532.278-.211 0-.459-.062-.608-.143v.277c.164.08.397.135.645.135.485 0 .787-.182.787-.514zm1.115.139l-.66-1.418h-.31l.831 1.753-.065.16c-.113.285-.256.427-.452.427a.557.557 0 01-.153-.022v.251a.697.697 0 00.19.026c.254 0 .477-.16.659-.583l.86-2.012h-.31l-.59 1.418zm2.592-.033v-.685c0-.488-.295-.743-.761-.743-.288 0-.529.069-.667.116v.284c.153-.065.368-.13.638-.13.32 0 .499.174.499.437v.061a2.379 2.379 0 00-.485-.054c-.375 0-.79.138-.79.59 0 .368.305.532.615.532.284 0 .507-.087.682-.233.05.153.178.211.32.211.073 0 .15-.014.219-.04v-.237a.44.44 0 01-.135.026c-.076 0-.135-.03-.135-.135zm-1.268-.138c0-.256.277-.332.532-.332.142 0 .32.026.445.051v.32a.824.824 0 01-.598.256c-.193 0-.379-.091-.379-.295zm2.47-.977h.71v1.48h.292v-1.75h-1.286c-.033.94-.135 1.53-.445 1.53a.347.347 0 01-.116-.021v.23c.076.029.145.04.211.04.452 0 .576-.613.634-1.509zm1.788 1.48l1.072-1.28v1.28h.291v-1.75h-.247l-1.072 1.28v-1.28h-.291v1.75h.247zm3.21-.47c0-.227-.163-.376-.36-.427.197-.044.324-.182.324-.394 0-.306-.251-.506-.74-.506-.233 0-.452.055-.627.135v.273c.16-.08.387-.138.595-.138.273 0 .48.083.48.276 0 .168-.153.227-.35.227h-.382v.27h.383c.175 0 .386.036.386.25 0 .227-.255.278-.532.278-.211 0-.46-.062-.609-.143v.277c.164.08.398.135.645.135.485 0 .788-.182.788-.514zm1.85.105v-.685c0-.488-.295-.743-.762-.743-.287 0-.528.069-.666.116v.284c.153-.065.368-.13.637-.13.321 0 .5.174.5.437v.061a2.379 2.379 0 00-.485-.054c-.375 0-.79.138-.79.59 0 .368.305.532.615.532.284 0 .506-.087.681-.233.051.153.179.211.321.211.073 0 .15-.014.219-.04v-.237a.44.44 0 01-.135.026c-.077 0-.135-.03-.135-.135zm-1.268-.138c0-.256.277-.332.532-.332.142 0 .32.026.445.051v.32a.824.824 0 01-.598.256c-.193 0-.38-.091-.38-.295zm3.65.976v-.743h-.258v-1.48h-.292v1.48h-.958v-1.48h-.292v1.75h1.516v.473h.285zm.637-.474l1.071-1.279v1.28h.292v-1.75h-.248l-1.071 1.28v-1.28h-.292v1.75h.248zm4.481-.874c0-.598-.455-.918-.932-.918-.438 0-.864.276-.926.783h-.43v-.74h-.291v1.75h.291v-.74h.43c.062.506.488.783.926.783.477 0 .932-.32.932-.918zm-1.574 0c0-.405.285-.642.642-.642.357 0 .641.237.641.642 0 .404-.284.641-.641.641-.358 0-.642-.237-.642-.641zm3.194.874v-1.479h1.013v1.48h.291v-1.75h-1.596v1.75h.291zm2.106-1.457v-.292h-.263v2.551h.292v-1.064c.127.15.342.306.678.306.499 0 .874-.393.874-.918s-.375-.918-.874-.918a.898.898 0 00-.707.335zm1.29.583c0 .4-.277.648-.616.648a.708.708 0 01-.645-.43v-.437a.708.708 0 01.645-.43c.339 0 .616.248.616.649zm.59 0c0 .597.46.918.933.918.478 0 .933-.32.933-.918 0-.598-.455-.918-.933-.918-.474 0-.933.32-.933.918zm1.575 0c0 .404-.285.641-.642.641-.357 0-.641-.237-.641-.641 0-.405.284-.642.641-.642.357 0 .642.237.642.642zm.589 0c0 .623.452.918.97.918.229 0 .459-.044.619-.127v-.285c-.15.084-.36.142-.576.142-.353 0-.685-.152-.721-.52h1.392c.109-.496-.139-1.046-.813-1.046-.416 0-.871.306-.871.918zm1.425-.128h-1.133c.021-.342.298-.52.597-.52.445 0 .561.334.536.52zm.976 1.002v-.75h.343l.641.75h.343l-.744-.878.663-.87h-.338l-.554.728h-.354v-.729h-.291v1.75h.291zm2.345 0v-1.479h.641v-.27h-1.578v.27h.645v1.48h.292zm2.375-.364v-.685c0-.488-.295-.743-.761-.743-.288 0-.529.069-.667.116v.284c.153-.065.368-.13.638-.13.32 0 .499.174.499.437v.061a2.38 2.38 0 00-.485-.054c-.375 0-.79.138-.79.59 0 .368.306.532.615.532.285 0 .507-.087.682-.233.05.153.178.211.32.211.073 0 .15-.014.22-.04v-.237a.44.44 0 01-.136.026c-.076 0-.135-.03-.135-.135zm-1.268-.138c0-.256.277-.332.532-.332.143 0 .321.026.445.051v.32a.824.824 0 01-.598.256c-.193 0-.379-.091-.379-.295z' fill='#fff'/></g><circle cx='7.402' cy='30.623' r='3.53' fill='#fff'/><circle cx='7.402' cy='40.416' r='3.53' fill='#fff'/><circle cx='7.402' cy='49.527' r='3.53' fill='#fff'/><path d='M7.262 28.899l-.676.258v.554l.525-.197v1.644h-.525v.529h1.59v-.53H7.71V28.9h-.447zM6.635 38.93v.574c.295-.225.623-.332.902-.332.283 0 .463.107.463.312 0 .266-.25.377-.54.545-.41.242-.92.504-.92 1.177v.274h2.092v-.528l-1.394.004c.004-.18.287-.332.619-.53.365-.216.754-.495.754-.967 0-.57-.455-.824-1.016-.824-.34 0-.673.09-.96.296zM8.537 48.516c0-.508-.43-.77-1.008-.77-.344 0-.66.082-.91.217v.57a1.63 1.63 0 01.824-.25c.336 0 .512.119.512.32 0 .192-.168.295-.442.295l-.386-.004-.004.524h.46c.278 0 .483.086.483.324 0 .263-.262.365-.578.365a1.714 1.714 0 01-.918-.217v.57c.315.13.606.188.967.188.574 0 1.115-.234 1.115-.82 0-.41-.254-.627-.537-.713a.607.607 0 00.422-.599z' fill='#1E92FD'/></svg>
                        </div>
                        <div class='test-data test-data-second'>
                            <div class='test-clcl'>
                                <span>1</span>
                                <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0)">
                                <path d="M27.1666 52.9994C22.6861 53.0339 19.0138 49.4177 18.9793 44.9372C18.9724 44.0402 19.6948 43.3067 20.5917 43.2997C21.4887 43.2928 22.2223 44.0152 22.2292 44.9122C22.2499 47.6011 24.4527 49.7702 27.1416 49.7495C29.8301 49.7288 31.9996 47.5261 31.9789 44.8371C31.972 43.9401 32.6944 43.2066 33.5914 43.1997C34.4883 43.1927 35.2219 43.9151 35.2288 44.8121C35.2633 49.2926 31.6471 52.9649 27.1666 52.9994Z" fill="#093445"/>
                                <path d="M44.9914 46.3621L9.24248 46.6374C7.15139 46.6535 5.43757 44.9658 5.42147 42.8751C5.41293 41.7655 5.88804 40.7113 6.72551 39.9832C9.9992 37.1737 11.8465 33.1123 11.8135 28.8246L11.767 22.7838C11.7026 14.421 18.4535 7.5653 26.8167 7.5009C35.1795 7.43652 42.0351 14.1874 42.0995 22.5502L42.146 28.591C42.1791 32.8787 44.0887 36.9112 47.3837 39.6551C48.2538 40.3851 48.7451 41.4319 48.7537 42.5415C48.7698 44.6322 47.0821 46.346 44.9914 46.3621ZM26.8417 10.7508C20.2701 10.8014 14.9663 16.1875 15.0169 22.7587L15.0634 28.7995C15.1038 34.0445 12.8433 39.0152 8.86336 42.4325C8.78808 42.4981 8.66971 42.6335 8.67137 42.8501C8.67364 43.1445 8.9227 43.3897 9.21746 43.3875L44.9664 43.1122C45.2608 43.11 45.506 42.8609 45.5037 42.5665C45.5021 42.3499 45.382 42.2164 45.3098 42.1562C41.2732 38.7964 38.9365 33.861 38.8961 28.616L38.8496 22.5752C38.799 16.004 33.4129 10.7002 26.8417 10.7508Z" fill="#093445"/>
                                <path d="M26.8416 10.7506C25.9446 10.7575 25.2111 10.0352 25.2042 9.13819L25.1541 2.63838C25.1472 1.7414 25.8696 1.00782 26.7666 1.00092C27.6635 0.99401 28.3971 1.71638 28.404 2.61336L28.4541 9.11316C28.461 10.0101 27.7386 10.7437 26.8416 10.7506Z" fill="#093445"/>
                                </g>
                                <defs>
                                <clipPath id="clip0">
                                <rect width="52" height="52" fill="white" transform="translate(0.800781 1.20093) rotate(-0.441141)"/>
                                </clipPath>
                                </defs>
                                </svg>

                            </div>
                        </div>
                        <div class='test-data-open'>
                            <svg width="18" height="30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            	<path fill-rule="evenodd" clip-rule="evenodd" d="M15.876 10.5L9 3.976 2.124 10.5 0 8.495 9 0l9 8.495-2.124 2.005z" fill="#1E92FD"/>
                            	<path fill-rule="evenodd" clip-rule="evenodd" d="M15.876 20L9 13.476 2.124 20 0 17.995 9 9.5l9 8.495L15.876 20z" fill="#1E92FD"/>
                            	<path fill-rule="evenodd" clip-rule="evenodd" d="M15.876 29.5L9 22.976 2.124 29.5 0 27.495 9 19l9 8.495-2.124 2.005z" fill="#1E92FD"/>
                            </svg>
                        </div>
                    </div>
                    <div class="test-btns-right">
                        <a class='btn prev-step' href='#' onclick='wndw.prev(); return false;'><span><svg width='39' height='31' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M.684 14.086a2 2 0 000 2.828l12.728 12.728a2 2 0 102.828-2.828L4.927 15.5 16.24 4.186a2 2 0 00-2.828-2.828L.684 14.086zm37.91-.586H2.097v4h36.495v-4z' fill='#535B56' fill-opacity='.5'/></svg></span></a>
                        <a class='btn next-step' href="#" onclick='wndw.next(); return false;'><span>ДАЛЬШЕ</span></a>
                    </div>
                </div>
            </div>
            <div id="page">
                <div class="container">
                    <div class="page-title">
                        <div class="page-title-wrapper">
                            <span class="page-title-icon">
                                <div class='pti-open'><svg width='46' height='64' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M8 12.143C13.34 2.71 24.194-1.328 32.138 3.17c7.944 4.498 10.065 15.882 4.724 25.314' stroke='#093445' stroke-width='2' stroke-miterlimit='10' stroke-linecap='round' stroke-linejoin='round'/><rect x='1' y='29' width='44' height='34' rx='7' fill='transparent' stroke='#093445' stroke-width='2'/><rect x='20' y='38' width='6' height='13' rx='3' fill='transparent' stroke='#093445' stroke-width='2'/></svg></div>
                                <div class='pti-close'><svg width='44' height='54' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M38 20.113V14.5C38 6.505 30.822 0 22 0S6 6.505 6 14.5v5.613c-3.401.586-6 3.55-6 7.117v19.542C0 50.757 3.243 54 7.229 54h29.542C40.757 54 44 50.757 44 46.771V27.229c0-3.566-2.599-6.53-6-7.116zM8 14.5C8 7.607 14.28 2 22 2s14 5.607 14 12.5V20H8v-5.5zm34 32.271A5.235 5.235 0 0136.771 52H7.229A5.235 5.235 0 012 46.771V27.229A5.235 5.235 0 017.229 22h29.542A5.235 5.235 0 0142 27.229v19.542z' fill='#093445'/><path d='M22 28c-2.206 0-4 1.794-4 4v6c0 2.206 1.794 4 4 4s4-1.794 4-4v-6c0-2.206-1.794-4-4-4zm2 10c0 1.103-.897 2-2 2s-2-.897-2-2v-6c0-1.103.897-2 2-2s2 .897 2 2v6z' fill='#093445'/></svg></div>
                            </span>
                            <span><?=$title_result?></span>
                        </div>
                    </div>
                    <?=$result_page?>
                    <div class="creator">
                        <div class="creator-left">
                            <div class="creator-text"><?=$rekvizity?></div>
                            <a href='<?=$politika_link?>' target='_blank'>Политика конфиденциальности</a>
                        </div>
                        <div class="creator-right">
                            <a href='<?=$creator_link?>' target='_blank'><?=$creator_name?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="footer-mobile">
            <div class="container">
                <div class='footer-top'>
                    <a href="<?=$politika_link?>">Политика <br>конфиденциальности</a>
                </div>
                <div class='footer-bottom'>
                    <span><?=$client_name?></span>
                    <a href="<?=$creator_link?>"><?=$creator_name_mobile?></a>
                </div>
            </div>
        </div>

        <? if($video_link){ ?>
            <div class="video">
                <div class="video-countainer">
                    <video id="video" loop="loop" autoplay="autoplay" muted="muted" playsinline="" preload="auto" controlslist="nodownload" disablepictureinpicture="" src="<?=$video_link?>">
                        <source src="<?=$video_link?>" type="video/mp4">
                    </video>
                    <div class="video-close"></div>
                    <div class="video-close-always"></div>
                </div>
            </div>
        <? } ?>
    </div>

    <div class="step-data">
        <div class="step-data-wrapper">
            <div class="step-data-left">

            </div>
            <div class="step-data-btn">
                <svg width="20" height="30" viewBox="0 0 20 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.13864 19.4929L10.0364 25.9942L16.8906 19.4469L19.0212 21.4447L10.0497 29.9699L1.02135 21.5048L3.13864 19.4929Z" fill="#373535"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.10739 9.9929L10.0051 16.4942L16.8593 9.94695L18.99 11.9447L10.0184 20.4699L0.990098 12.0048L3.10739 9.9929Z" fill="#373535"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.07516 0.493146L9.97292 6.9944L16.8271 0.447192L18.9578 2.44494L9.98621 10.9701L0.957871 2.50509L3.07516 0.493146Z" fill="#373535"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="step-data-background"></div>

    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                          <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
          <?=$form_callback?>
        </div>
      </div>
    </div>

    <div class="modal micromodal-slide" id="thanks" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="thanks-title">
                <div class="modal__wrapper">
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    <?=($modal_title ? "<div class='modal-title'>{$modal_title}</div>" : "")?>
                    <?=($modal_text ? "<div class='modal-text'>{$modal_text}</div>" : "")?>
                    <?=($modal_btn_name ? "<div class='modal-btn'>
                        <a href='{$modal_btn_link}' onclick=\"yMetrika('main_website')\" target='_blank' class='btn btn-inverse'><span>{$modal_btn_name}</span></a>
                    </div>" : "")?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal micromodal-slide" id="thanks-number" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="thanks-number-title">
                <div class="modal__wrapper">
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    <div class='modal-title'>СПАСИБО</div>
                    <div class='modal-text'>Ваш номер изменен!</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var vizualizacya = '<?=json_encode($vuz_imgs)?>';
        var color_items = '<?=$color_items?>';
        var color_text = '<?=$color_text?>';
        var sizes = '<?=str_replace("\\\'", "\'", json_encode($sizes))?>';
    </script>

    <script src="/static/js/scripts.js?v=<?=filectime("{$DOCUMENT_ROOT}/static/js/scripts.js")?>"></script>
    <script src="/static/js/js.js?v=<?=filectime("{$DOCUMENT_ROOT}/static/js/js.js")?>"></script>
</body>
