<?
function getMonth($num){
	$month_names = array(1 => "января", 2 => "февраля", 3 => "марта", 4 => "апреля", 5 => "мая", 6 => "июня", 7 => "июля", 8 => "августа", 9 => "сентября", 10 => "октября", 11 => "ноября", 12 => "декабря");
	return $month_names[$num];
}
function getDateFormat($date){
	return $date->format("d")." ".getMonth($date->format("n"));
}
function removeDirectory($dir){
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? removeDirectory("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
}
// security
function securityForm($a, $param =""){
	if(is_array($a)){
		  $result = array();
		  foreach ($a as $key => $value) $result[$key] = securityForm($value, $param);
		  return $result; // Возвращаемый "защищённый" массив
	}
	return securityFormThis($a, $param);
}
function securityFormThis($getvalue, $param) {
	if(stristr($param, "strip_tags")) $getvalue = strip_tags($getvalue);
	if(!stristr($param, "noslashes")) $getvalue = stripcslashes($getvalue);
	if(!stristr($param, "password")) $getvalue = htmlspecialchars($getvalue, ENT_QUOTES);
	$result = addcslashes($getvalue, '$');
	return $result;
}
# склонение числа
function pluralForm($n, $form1, $form2, $form5) {
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}

function isJson($string) {
	if(!is_numeric($string)){
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	return false;
}
# html символы -> ковычки
function html_entity_decode_convert($array) {
    foreach ($array as $key => $val) {
    	if(!is_array($val)) $array[$key] = html_entity_decode($val, ENT_QUOTES);
		else $array[$key] = html_entity_decode_convert($val);
    }
	return $array;
}
# json в массив с исправлением  ошибочных записей
function orderArray($orderlist, $file='') {


	$z =array();
	if(strstr($orderlist,"&quot;")){
		if($file) $z["&quot;"] = "\u0022";
	}
	if ((strstr($orderlist,"u04") && !strstr($orderlist,"\u04")) || (strstr($orderlist,"u00") && !strstr($orderlist,"\u00"))) {
		$z["u04"] = "\u04";
		$z["u00"] = "\u00";
		$z["u21"] = "\u21";
		$z["u20"] = "\u20";
	}
	if($z) $orderlist = strtr($orderlist,$z);

	if(!isJson($orderlist)) return $orderlist;

	$result = json_decode($orderlist,1);
	# html-код ковычек -> нормальные ковычки
    if(is_array($result)) $result = html_entity_decode_convert($result);

	return is_array($result) ? $result : array();
}

# Рекрсивное создание папок
function checkAndCreateDir($path) {
	global $DOCUMENT_ROOT;

	if($path){
		$path_now  = "{$DOCUMENT_ROOT}/";
		$array_dirs = explode("/", $path);
		foreach ($array_dirs as $dir) {
            if($dir){
                $path_now .= "{$dir}/";
                if (!file_exists($path_now)) mkdir($path_now, 0775, true);
            }
		}

	}
}

function randHash($len=12){
	return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
}

function inputDefault($type){
	$inputs = "<input type='hidden' name='action' value='{$type}'>";
	return $inputs;
}
# GET CONTENT
function get_string($value, $class = false){
    if(gettype($value) == "string"){
        $value = str_replace("\n\r", "<br>", $value);
        $value = str_replace("\n", "<br>", $value);
        $value = str_replace("\r", "<br>", $value);
        # date
        preg_match('/%date[\d]+%/', $value, $matches);
        if($matches){
            $count_day = preg_replace('/[^0-9]/', '', $matches[0]);
            $now = new DateTime("NOW");
            $now->add(new DateInterval("P{$count_day}D"));
            $date_name = getDateFormat($now);
            $value = str_replace($matches[0], $date_name, $value);
        }
        #step
        $count = count(get_content("test", "test_block", "test")) + 1;
        $value = str_replace("%count_step%", "{$count} ".pluralForm($count, "вопрос", "вопроса", "вопросов"), $value);

		if($class){
			$value = "<span class='{$class}'>{$value}</span>";
		}

		// tags
		preg_match_all('/\[[a-z]+[0-9]*\]/', $value, $matches);
		if($matches){
		    foreach($matches[0] as $matche){
		        $vl = trim($matche, "[]");
		        $value = str_replace($matche, "<span class='{$vl}'>", $value);
		    }
		}
		preg_match_all('/\[\/[a-z]+[0-9]*\]/', $value, $matches);
		if($matches){
		    foreach($matches[0] as $matche){
		        $value = str_replace($matche, "</span>", $value);
		    }
		}
    }
    return $value;
}
function get_content($type, $zone_key, $block_key, $nostyle = false){
    global $DOCUMENT_ROOT, $tabs;
    $content = orderArray(file_get_contents($DOCUMENT_ROOT."/data/{$type}.json"), 1);
    if(!$content) return "";

    $data = isset($content[$zone_key][$block_key]) ? $content[$zone_key][$block_key] : "";

    return get_content_style($data, $nostyle);
}
function get_content_style($data, $nostyle = false){
	$value = "";
	$class = "";
	if((isset($data['style']) && $data['style']) || $nostyle){
		foreach ($data['style'] as $cls) $class .= ($class ? " " : "").$cls;
	}

    switch ($data['type']) {
        case 'file':
            $value = isset($data['value']['path']) ? $data['value']['path'] : "";
            break;
        case 'textarea_size':
            if($data['value']){
                foreach ($data['value'] as $item) {
                    if($item['size'] == "main") {
                        $value = $item['value'];
                        break;
                    }
                }
            }
            break;
        case 'fields':
            $value = isset($data['value']) ? $data['value'] : "";
            if($value){
                foreach ($value as $i => $itm) {
                    foreach ($itm as $j => $tm) {
                        if($tm['type'] == "input" || $tm['type'] == "textarea"){
                            $value[$i][$j]['value'] = get_string($tm['value'], $class);
                        }
                    }
                }
            }
            break;
        default:
            $value = isset($data['value']) ? $data['value'] : "";
            break;
    }
    if(!$nostyle) $value = get_string($value, $class);
	return $value;
}


# SLIDER
function slider($data){
    global $DOCUMENT_ROOT;
    $hash = randHash();
    $item_main = "";
    $item_right = "";
    if($data['items']){
        foreach ($data['items'] as $i => $sldr) {
            $index = $i + 1;
            $path = $sldr['image']['value']['path'];
            $size = getimagesize("{$DOCUMENT_ROOT}{$path}");
            $item_main .= "<div class='swiper-slide' data-i='{$index}' data-hash='{$hash}'><a href='{$path}' data-photo='{$hash}' data-size='{$size[0]}x{$size[1]}' alt='{$sldr['text1']['value']}".($sldr['text2']['value'] ? ", {$sldr['text2']['value']}" : "")."'><img src='{$path}'></a></div>";
            $item_right .= "<div class='swiper-itme' data-i='{$index}' data-hash='{$hash}' data-name='{$sldr['text1']['value']}' data-city='{$sldr['text2']['value']}'><img src='{$path}'></div>";
        }
    }
    return "<div class='slider' data-count='".count($data['items'])."'>
        ".($data['title'] ? "<div class='slider-title'>{$data['title']}</div>" : "")."
        ".($data['second'] ? "<div class='slider-second'>{$data['second']}</div>" : "")."
        ".($data['mini'] ? "<div class='slider-mini color'>{$data['mini']}</div>" : "")."
        <div class='slider-body'>
            <div class='slider-wrapper'>
                <div class='slider-main'>
                    <div class='swiper-container' data-hash='{$hash}'>
                        <div class='swiper-wrapper'>{$item_main}</div>
						<div class='swiper-button'>
	                        <div class='swiper-button-prev'><svg width='9' height='15' viewBox='0 0 9 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.224664 7.49996C0.224664 7.23113 0.327309 6.96233 0.532169 6.75737L6.9819 0.307712C7.39218 -0.102571 8.05738 -0.102571 8.4675 0.307712C8.87761 0.717829 8.87761 1.3829 8.4675 1.79321L2.76042 7.49996L8.4673 13.2067C8.87741 13.617 8.87741 14.282 8.4673 14.6921C8.05718 15.1026 7.39198 15.1026 6.9817 14.6921L0.531969 8.24254C0.327077 8.03749 0.224664 7.76869 0.224664 7.49996Z' fill='#1E92FD'/></svg></div>
							<div class='swiper-button-next'><svg width='9' height='15' viewBox='0 0 9 15' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.224664 7.49996C0.224664 7.23113 0.327309 6.96233 0.532169 6.75737L6.9819 0.307712C7.39218 -0.102571 8.05738 -0.102571 8.4675 0.307712C8.87761 0.717829 8.87761 1.3829 8.4675 1.79321L2.76042 7.49996L8.4673 13.2067C8.87741 13.617 8.87741 14.282 8.4673 14.6921C8.05718 15.1026 7.39198 15.1026 6.9817 14.6921L0.531969 8.24254C0.327077 8.03749 0.224664 7.76869 0.224664 7.49996Z' fill='#1E92FD'/></svg></div>
                        </div>
                        <div class='swiper-pagination'></div>
                    </div>
                </div>
                <div class='slider-rigth'>{$item_right}</div>
            </div>
            <div class='slider-text'></div>
        </div>
    </div>";
}

# TEST
function get_test(){
	global $DOCUMENT_ROOT;
	$test = get_content("test", "test_block", "test");
    $test_tabs = "";
    $test_content = "";
    foreach ($test as $i => $step) {
        $test_tabs .= "<div class='step' data-step='{$i}'><span>{$i}</span></div>";

        $have_step = (count($test)+1-$i);
        $txt_step = $have_step>1 ? "Осталось {$have_step} ".pluralForm($have_step, "вопрос", "вопросa", "вопросов") : "Остался последний шаг";

        $image_have = false;
        foreach ($step['variable'] as $variable) {
            if($variable['type'] == 2) $image_have = true;
        }

		$data = "";
		$data .= $step['blk_title'] ? "<div class='step-2-title'>".get_string($step['blk_title'])."</div>" : "";
		$data .= $step['blk_subtitle'] ? "<div class='step-2-second'>".get_string($step['blk_subtitle'])."</div>" : "";
		if($step['blk_image']['path']){
	        $size = getimagesize("{$DOCUMENT_ROOT}{$step['blk_image']['path']}");
	        $size = "{$size[0]}x{$size[1]}";

			$data .= "<div class='step-2-img' data-type='{$step['blk_image_type']}'>";
				if($step['blk_image_zoom']) $data .= "<a href='{$step['blk_image']['path']}' data-size='{$size}' data-photo='step_{$i}'>";
						$data .= "<img src='{$step['blk_image']['path']}'>";
				if($step['blk_image_zoom']) $data .= "</a>";
			$data .= "</div>";
		}


        $test_content .= "<div class='window ".($i==1 ? "active" : "")."' data-step='{$i}' data-image='".($image_have ? "have" : "no")."' data-content='".($data ? "have" : "no")."'>
                            <div class='window-title'>{$step['title']}</div>
                            ".($step['subtitle'] ? "<div class='window-second'>{$step['subtitle']}</div>" : "")."
                            <div class='window-content'>
                                <div class='window-left'>
                                    <div class='window-left-wrp'>".fields($step, $i)."</div>
                                </div>
                                <div class='window-right'>
                                    <div class='window-rg'>
                                        ".($data ? "<div class='step-blk'><div class='step-wrp'>{$data}</div></div>" : "")."
                                        ".get_block_r($txt_step)."
                                    </div>
                                </div>
                            </div>
                            <div class='window-btns'>
                                ".($i == 1 ? "" : "<a class='btn prev-step' href='#' onclick='wndw.prev(); return false;'><span><svg width='39' height='31' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M.684 14.086a2 2 0 000 2.828l12.728 12.728a2 2 0 102.828-2.828L4.927 15.5 16.24 4.186a2 2 0 00-2.828-2.828L.684 14.086zm37.91-.586H2.097v4h36.495v-4z' fill='#535B56' fill-opacity='.5'/></svg></span></a>")."
                                <a class='btn next-step' href='#' onclick='wndw.next(); return false;'><span>ДАЛЬШЕ <svg width='47' height='31' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M45.66 16.382a2 2 0 00-.047-2.828L32.672 1.117a2 2 0 00-2.828.064 2 2 0 00.047 2.828l11.503 11.055-11.12 11.569a2 2 0 00.049 2.828 2 2 0 002.827-.064l12.51-13.015zM.203 18l44.053-1-.067-4L.136 14l.067 4z' fill='#fff'/></svg></span></a>
                            </div>
                        </div>";
    }
	$i += 1;
    $test_tabs .= "<div class='step' data-step='{$i}'><span>{$i}</span></div>";
	return array(
		'tabs' => "<div class='step step-empty'></div>".$test_tabs,
		'content' => $test_content
	);
}
function fields($step, $i){
    $result = "";
	$key = "test[{$i}]";
    $key_text = "test[{$i}-text]";
    $fields = $step['fields'];

    foreach ($step['variable'] as $variable) {
		$img_have = false;
		$image = array();

		switch ($variable['type']) {
			case 1:
				$type = $step["type_main"] == 2 ? "checkbox" : "radio";
				break;
			case 2:
				$type = $step["type_main"] == 2 ? "checkbox" : "radio";
				$img_have = true;
				if($variable['image']['path']) $image = $variable['image'];
				break;
			case 3:
				$type = "input";
				break;
			case 4:
				$type = "radio";
				$variable['text'] = "Пока не знаю";
				break;
			case 7:
				$type = "radio";
				$variable['text'] = "Пока не решил";
				break;
			case 5:
				$type = "checkbox_text";
				$variable['text'] = 'Другое.<br>Напишите свой вариант';
				break;
			case 6:
				$type = "checkbox_text_big";
				$variable['text'] = 'Другое';
				$variable['text_second'] = 'Другое.<br>Напишите свой вариант';
				break;
		}
		$placeholder = $variable['text'] ? str_replace("<br>", " ", $variable['text']) : "";

		if($variable['doptext']){
			$variable['doptext'] = get_string($variable['doptext']);
		}

        switch ($type) {
            case 'input':
                $result .= "<label class='input input-default'>
                                <input type='text' name='{$key}' placeholder='{$placeholder}'>
                            </label>";
                break;
            case 'radio':
			case 'checkbox':
                $result .= "<label class='input {$type} ".($img_have ? "input-img-have" : "")." ".($variable['doptext'] ? "input-content-have" : "")." ".($img_have ? "" : "input-type-{$type}")."'>
                                <input type='{$type}' name='{$key}".($type == "checkbox" ? "[]" : "")."' value='{$placeholder}'>
								".($image['path'] ? "<div class='input-img'><span><img src='{$image['path']}'></span></div>" : "")."
								<div class='input-txt'>
	                                <div class='input-icon'>
	                                	".($type == "checkbox" ? "<svg width='9' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M7.89 0c-.297 0-.576.13-.786.366L2.992 4.989l-1.096-1.23a1.046 1.046 0 00-.785-.366c-.297 0-.576.13-.786.366A1.32 1.32 0 000 4.64c0 .333.116.646.325.881l1.882 2.113c.21.235.489.365.785.365.297 0 .576-.13.786-.366L8.675 2.13a1.36 1.36 0 000-1.764A1.047 1.047 0 007.89 0z' fill='#1E92FD'/></svg>" : "")."
	                                </div>
	                                <div class='input-text'>{$variable['text']}</div>
									".($variable['doptext'] ? "<div class='input-content'>{$variable['doptext']}</div>" : "")."
									".($variable['doptext'] ? "<div class='input-content-right'><svg width='15' height='9' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' clip-rule='evenodd' d='M1.77 0L7.5 5.592 13.23 0 15 1.718 7.5 9 0 1.718 1.77 0z' fill='#373535'/></svg></div>" : "")."
								</div>
                            </label>";

                break;
			case 'checkbox_text':
                $result .= "<label class='input checkbox checkbox-text checkbox-text-mini'>
                                <input type='radio' name='{$key}' value='checkbox-text'>
								<div class='input-txt'>
	                                <div class='input-icon'>
										<svg width='9' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M7.89 0c-.297 0-.576.13-.786.366L2.992 4.989l-1.096-1.23a1.046 1.046 0 00-.785-.366c-.297 0-.576.13-.786.366A1.32 1.32 0 000 4.64c0 .333.116.646.325.881l1.882 2.113c.21.235.489.365.785.365.297 0 .576-.13.786-.366L8.675 2.13a1.36 1.36 0 000-1.764A1.047 1.047 0 007.89 0z' fill='#1E92FD'/></svg>
	                                </div>
	                                <div class='input-text'>
										".($variable['text'] ? "<div class='input-placeholder'>{$variable['text']}</div>" : "")."
	                                    <textarea name='{$key_text}'></textarea>
									</div>
                                </div>
                            </label>";
                break;
            case 'checkbox_text_big':
                $result .= "<label class='input checkbox checkbox-text checkbox-text-big'>
                                <input type='radio' name='{$key}' value='checkbox-text'>
								<div class='input-img'><div class='input-placeholder'>{$variable['text_second']}</div><textarea name='{$key_text}'></textarea></div>
								<div class='input-txt'>
	                                <div class='input-icon'>
										<svg width='9' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M7.89 0c-.297 0-.576.13-.786.366L2.992 4.989l-1.096-1.23a1.046 1.046 0 00-.785-.366c-.297 0-.576.13-.786.366A1.32 1.32 0 000 4.64c0 .333.116.646.325.881l1.882 2.113c.21.235.489.365.785.365.297 0 .576-.13.786-.366L8.675 2.13a1.36 1.36 0 000-1.764A1.047 1.047 0 007.89 0z' fill='#1E92FD'/></svg>
	                                </div>
	                                <div class='input-text'>{$variable['text']}</div>
                                </div>
                            </label>";
                break;
        }
    }
    return $result;
}

function get_block_r($title){
	$subtitile = get_content("test", "block_main", "subtitle");
	$items = get_content("test", "block_main", "get_items");
	$text = $title == "end" ? get_content("test_end", "test_result", "test_blk_text") : get_content("test", "block_main", "text");

	$items_html = "";
	foreach ($items as $i => $item) {
		$num = $i + 1;
		$items_html .= "<div class='ti-list'>
							<div class='ti-num'>{$num}</div>
							<div class='ti-txt'>{$item['text']['value']}</div>
						</div>";
	}
	return "<div class='test-info'>
				".($title != "end" ? "<div class='ti-title'>{$title}</div>".($subtitile ? "<div class='ti-second'>{$subtitile}</div>" : "") : "")."
				<div class='ti-lists'>{$items_html}</div>
				".($text ? "<div class='ti-bottom'>{$text}</div>" : "")."
			</div>";
}

function get_end_test(){

	$title = get_content("test_end", "test_result", "title");
	$subtitle = get_content("test_end", "test_result", "subtitle");
	$text_list_top = get_content("test_end", "test_result", "text_list_top");
    $lists = get_content("test_end", "test_result", "list");
	$lists_items = "";
	foreach ($lists as $list) {
		$value = strip_tags($list['text']['value']);
		$lists_items .= "<label>
							<input type='radio' name='call' value='{$value}'>
							<div class='radio-bot'></div>
							<div class='radio-text'>{$list['text']['value']}</div>
						</label>";
	}
	$text_list_bottom = get_content("test_end", "test_result", "text_list_bottom");
	$text_phone_top = get_content("test_end", "test_result", "text_phone_top");
	$text_btn = get_content("test_end", "test_result", "text_btn");
	$text_good = get_content("test_end", "test_result", "text_good");
    $image = get_content("test_end", "test_result", "form_image");

	$result = "<div class='window' data-step='end'>
					<div class='step-title'>{$title}</div>
					<div class='step-title-second'>{$subtitle}</div>
					<div class='step-wrapper'>
						<div class='step-wrapper-image' style='background-image:url({$image})'></div>
						<div class='step-left'>
								<div class='form-inputs'>
									<div class='form-title'>{$text_list_top}</div>
									<div class='chekboxs'>{$lists_items}</div>
									<div class='input-text-bold'>{$text_list_bottom}</div>
									<div class='input-text-bottom'>{$text_phone_top}</div>
								</div>
								<div class='inputs-bottom'>
									<div class='input-bot'>
										<input type='text' name='phone' placeholder='_(___)___-__-__'>
									</div>
									<div class='input-phone'>
										<div class='input-bot'>
											<input type='text' name='email' placeholder='Введите вашу почту'>
										</div>
									</div>
									<a class='btn btn-send-test' href='#'><span>{$text_btn}</span></a>
								</div>
						</div>
						<div class=step-right>".get_block_r("end")."</div>
					</div>
					<div class='circle' id='circle'>
						<div class='good-icon'><svg width='30' height='25' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M25.805.262c-.946 0-1.835.386-2.504 1.085L10.194 15.068l-3.493-3.65a3.442 3.442 0 00-2.504-1.085c-.946 0-1.835.385-2.504 1.084a3.763 3.763 0 00-1.037 2.617c0 .989.368 1.918 1.037 2.617l5.998 6.269a3.442 3.442 0 002.504 1.084c.945 0 1.835-.385 2.505-1.085L28.31 6.581c1.38-1.444 1.38-3.792 0-5.235A3.443 3.443 0 0025.804.262z' fill='#1E92FD'/></svg></div>
					</div>
					<div class='step-bottom-text'>{$text_good}</div>
				</div>";
	return $result;
}

# color opacity
function hexToRgb($hex, $alpha = false) {
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   if ( $alpha ) {
      $rgb['a'] = $alpha;
   }
   return implode(",", $rgb);
}


function get_font(){
	$font = get_content("main", "design", "font");
	$array = array(
		'Open Sans' => '<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">',
		'Roboto' => '<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">',
	);
	switch ($font) {
		case 'Muller':
			$fontstyle = "
			    <link rel='preload' href='/static/fonts/MullerLight.woff' as='font' type='font/woff' crossorigin='anonymous'>
			    <link rel='preload' href='/static/fonts/MullerMedium.woff' as='font' type='font/woff' crossorigin='anonymous'>
			    <link rel='preload' href='/static/fonts/MullerRegular.woff' as='font' type='font/woff' crossorigin='anonymous'>
			    <link rel='preload' href='/static/fonts/MullerBold.woff' as='font' type='font/woff' crossorigin='anonymous'>
			    <style>
			        @font-face {
			        	font-family: 'Muller';
			        	src: url('/static/fonts/MullerRegular.eot');
			        	src: local('MullerRegular'),
			        		url('/static/fonts/MullerRegular.eot?#iefix') format('embedded-opentype'),
			        		url('/static/fonts/MullerRegular.woff') format('woff'),
			        		url('/static/fonts/MullerRegular.ttf') format('truetype');
			        	font-weight: normal;
			        	font-style: normal;
			            font-display: swap;
			        }
			        @font-face {
			        	font-family: 'Muller';
			        	src: url('/static/fonts/MullerBold.eot');
			        	src: local('MullerBold'),
			        		url('/static/fonts/MullerBold.eot?#iefix') format('embedded-opentype'),
			        		url('/static/fonts/MullerBold.woff') format('woff'),
			        		url('/static/fonts/MullerBold.ttf') format('truetype');
			        	font-weight: 700;
			        	font-style: normal;
			            font-display: swap;
			        }
			        @font-face {
			        	font-family: 'Muller';
			        	src: url('/static/fonts/MullerLight.eot');
			        	src: local('MullerLight'),
			        		url('/static/fonts/MullerLight.eot?#iefix') format('embedded-opentype'),
			        		url('/static/fonts/MullerLight.woff') format('woff'),
			        		url('/static/fonts/MullerLight.ttf') format('truetype');
			        	font-weight: 300;
			        	font-style: normal;
			            font-display: swap;
			        }
			        @font-face {
			        	font-family: 'Muller';
			        	src: url('/static/fonts/MullerMedium.eot');
			        	src: local('MullerMedium'),
			        		url('/static/fonts/MullerMedium.eot?#iefix') format('embedded-opentype'),
			        		url('/static/fonts/MullerMedium.woff') format('woff'),
			        		url('/static/fonts/MullerMedium.ttf') format('truetype');
			        	font-weight: 500;
			        	font-style: normal;
			            font-display: swap;
			        }
			    </style>";
			break;
		default:
			$fontstyle = '<link rel="preconnect" href="https://fonts.gstatic.com">'.$array[$font];
			break;
	}

	return $fontstyle."<style>body { font-family: '{$font}', sans-serif; }</style>";
}

function get_result(){
	global $DOCUMENT_ROOT, $akps, $form_callback;
	$content = orderArray(file_get_contents($DOCUMENT_ROOT."/data/result.json"), 1);
	$zones = $content['blocks'];
	$result = array();

	foreach ($zones as $key => $dt) {
		$content = "";

		foreach ($dt as $i => $data) {
			if(is_array($data)) $dt[$i] = get_content_style($data, false);
		}
		if(!trim($dt['checked'])) continue;

		switch ($dt['key']) {
			case 'people':
				$content = "<div class='text-1'>
	                            <div class='profile-blk'>
	                                <div class='profile'>
	                                   	".($dt['photo'] ? "<div class='photo'><img src='{$dt['photo']}'></div>" : "")."
										".($dt['fio'] ? "<div class='profile-name'>{$dt['fio']}</div>" : "")."
	                                    ".($dt['dolzhnost'] ? "<div class='profile-text'>{$dt['dolzhnost']}</div>" : "")."
	                                </div>
	                            </div>
								".($dt['text'] ? "<div class='text'><div class='txt'>{$dt['text']}</div></div>" : "")."
	                        </div>";
				break;
			case 'phone':
				$btn_text = $dt['btn_text'] ? $dt['btn_text'] : "изменить номер";
				$content = "<div class='text-blk' ".($dt['opendown'] ? '1' : '2').">
	                            <form method='post' action='/actions/send.php' class='ajax'>
	                                ".inputDefault('updatePhone')."
	                                <div class='text-blk-akps'>
	                                    ".($dt['text1'] ? "<div class='text-blk-1 text-size-2'>{$dt['text1']}</div>" : "")."
	                                    ".($dt['opendown'] && $akps ? "<div class='akps-result'>{$akps}</div>" : "")."
	                                    ".($dt['text2'] ? "<div class='text-blk-1-second text-size-2'>{$dt['text2']}</div>" : "")."
	                                </div>
	                               ".($dt['text3'] ? " <div class='text-blk-2 text-size-2'>{$dt['text3']}</div>" : "")."
	                                <div class='text-blk-3'>
	                                    <div class='text-blk-input'><input type='text' name='phone' value='' placeholder='_(___)___-__-__' inputmode='text'></div>
	                                    <div class='text-blk-input text-blk-btn'><input type='submit' onclick=\"yMetrika('number_changed')\" name='{$btn_text}' value='{$btn_text}'></div>
	                                </div>
	                            </form>
	                        </div>";
				break;
			case 'image':
				if($dt['image']){
					$size = getimagesize("{$DOCUMENT_ROOT}{$dt['image']}");
					$content = "<a href='{$dt['image']}' data-photo='".rand()."' data-size='{$size[0]}x{$size[1]}'><img src='{$dt['image']}'></a>";
				}
				break;
			case 'callback':
				if($dt['callback_checked']) $content = $form_callback;
				break;
			case 'list':
				$html = "";
				$have_imgae = false;
				foreach ($dt['list'] as $key => $list) {
					if($list['image']['value']['path']) $have_imgae = true;
					$html .= "<div class='pr-list'>
				                ".($list['image']['value']['path'] ? "<span class='pr-icon'><img src='{$list['image']['value']['path']}'></span>" : "")."
				                <span class='pr-text'>{$list['text']['value']}</span>
							</div>";
				}
				if($html) $content = "<div class='pr-lists ".($have_imgae ? "pr-lists-img" : "")."'>{$html}</div>";
				break;
			case 'slider':
			    $slider = array(
			        'items' => $dt['slider']
			    );
			    $content = slider($slider);
				break;
			case 'soc':
				$html = "";
				foreach ($dt['list'] as $key => $list) {
					if(!$list['link']['value']) continue;
					$icon = "";
					if(stristr($list['link']['value'], "facebook.com")){
						$icon = "<svg width='16' height='30' viewBox='0 0 16 30' fill='none' xmlns='http://www.w3.org/2000/svg'>
							<path d='M12.9966 4.98125H15.7353V0.21125C15.2628 0.14625 13.6378 0 11.7453 0C7.79657 0 5.09157 2.48375 5.09157 7.04875V11.25H0.73407V16.5825H5.09157V30H10.4341V16.5837H14.6153L15.2791 11.2513H10.4328V7.5775C10.4341 6.03625 10.8491 4.98125 12.9966 4.98125Z' fill='white'/>
						</svg>";
					}else if(stristr($list['link']['value'], "youtube.com")){
						$icon = "<svg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<path d='M29.3819 7.79839C29.0361 6.51322 28.0228 5.50019 26.7379 5.15413C24.3902 4.51166 14.9997 4.51166 14.9997 4.51166C14.9997 4.51166 5.60941 4.51166 3.26178 5.12964C2.00156 5.47548 0.963588 6.51345 0.61775 7.79839C0 10.1458 0 15.0141 0 15.0141C0 15.0141 0 19.9069 0.61775 22.2298C0.963817 23.5147 1.97684 24.528 3.26201 24.874C5.63413 25.5165 14.9999 25.5165 14.9999 25.5165C14.9999 25.5165 24.3902 25.5165 26.7379 24.8985C28.023 24.5527 29.0361 23.5394 29.3821 22.2545C29.9999 19.9069 29.9999 15.0388 29.9999 15.0388C29.9999 15.0388 30.0246 10.1458 29.3819 7.79839Z' fill='white'/>
									<path d='M12.0099 19.5118L19.8187 15.0143L12.0099 10.5167V19.5118Z' fill='{$dt['color']}'/>
								</svg>";
					}else if(stristr($list['link']['value'], "vk.com")){
						$icon = "<svg width='29' height='17' viewBox='0 0 29 17' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<path d='M24.064 9.74231C23.5951 9.15023 23.7293 8.88681 24.064 8.35756C24.07 8.35152 27.9403 3.00343 28.339 1.18973L28.3415 1.18852C28.5396 0.527559 28.3415 0.0418091 27.3833 0.0418091H24.2126C23.4054 0.0418091 23.0333 0.458684 22.8339 0.925101C22.8339 0.925101 21.2195 4.78935 18.9358 7.29423C18.1987 8.01802 17.858 8.25002 17.4556 8.25002C17.2574 8.25002 16.9493 8.01802 16.9493 7.35706V1.18852C16.9493 0.395851 16.7233 0.0418091 16.0551 0.0418091H11.0695C10.5633 0.0418091 10.2624 0.411559 10.2624 0.755934C10.2624 1.50752 11.4043 1.68031 11.5227 3.79489V8.38293C11.5227 9.38827 11.3402 9.57314 10.9354 9.57314C9.85758 9.57314 7.24154 5.69318 5.69125 1.25256C5.37829 0.391017 5.07258 0.0430173 4.25938 0.0430173H1.0875C0.182458 0.0430173 0 0.459892 0 0.926309C0 1.75039 1.07783 5.84785 5.01217 11.2612C7.63425 14.9563 11.3269 16.9585 14.6861 16.9585C16.7052 16.9585 16.9517 16.5138 16.9517 15.7489C16.9517 12.2182 16.7693 11.8847 17.7806 11.8847C18.2495 11.8847 19.0566 12.1167 20.9416 13.899C23.0961 16.0124 23.4501 16.9585 24.656 16.9585H27.8267C28.7305 16.9585 29.1885 16.5138 28.9251 15.6366C28.3221 13.7914 24.2476 9.99606 24.064 9.74231Z' fill='white'/>
								</svg>";
					}else if(stristr($list['link']['value'], "instagram.com")){
						$icon = "<svg width='27' height='26' viewBox='0 0 27 26' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<path d='M19.6913 0H7.30864C3.27859 0 0 3.15716 0 7.03795V18.9622C0 22.8428 3.27859 26 7.30864 26H19.6915C23.7214 26 26.9999 22.8428 26.9999 18.9622V7.03795C26.9999 3.15716 23.7214 0 19.6913 0V0ZM25.4171 18.9622C25.4171 22.0023 22.8486 24.4757 19.6913 24.4757H7.30864C4.15138 24.4757 1.58285 22.0023 1.58285 18.9622V7.03795C1.58285 3.99763 4.15138 1.52423 7.30864 1.52423H19.6915C22.8486 1.52423 25.4171 3.99763 25.4171 7.03795V18.9622Z' fill='white'/>
									<path d='M13.5 5.89081C9.42912 5.89081 6.11737 9.0799 6.11737 13C6.11737 16.92 9.42912 20.1091 13.5 20.1091C17.5708 20.1091 20.8826 16.92 20.8826 13C20.8826 9.0799 17.5708 5.89081 13.5 5.89081ZM13.5 18.5849C10.3021 18.5849 7.70022 16.0796 7.70022 13C7.70022 9.92057 10.3021 7.41504 13.5 7.41504C16.698 7.41504 19.2997 9.92057 19.2997 13C19.2997 16.0796 16.698 18.5849 13.5 18.5849Z' fill='white'/>
									<path d='M21.0591 3.36609C19.8561 3.36609 18.8776 4.30852 18.8776 5.46676C18.8776 6.62521 19.8561 7.56763 21.0591 7.56763C22.2621 7.56763 23.2407 6.62521 23.2407 5.46676C23.2407 4.30832 22.2621 3.36609 21.0591 3.36609ZM21.0591 6.04321C20.7291 6.04321 20.4604 5.78454 20.4604 5.46676C20.4604 5.14878 20.7291 4.89032 21.0591 4.89032C21.3893 4.89032 21.6579 5.14878 21.6579 5.46676C21.6579 5.78454 21.3893 6.04321 21.0591 6.04321Z' fill='white'/>
								</svg>";
					}else if(stristr($list['link']['value'], "ok.ru")){
						$icon = "<svg width='27' height='27' viewBox='0 0 27 27' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<path d='M5.31076 14.4909C4.62114 15.8465 5.40414 16.4945 7.19064 17.6015C8.70939 18.5398 10.8075 18.8829 12.1553 19.0202C11.6029 19.5512 14.1341 17.1167 6.84414 24.1288C5.29839 25.6104 7.78689 27.9875 9.33151 26.5374L13.5131 22.5043C15.114 24.0444 16.6485 25.5204 17.6948 26.543C19.2405 27.9988 21.7279 25.6419 20.199 24.1344C20.0843 24.0253 14.5324 18.6995 14.871 19.0258C16.2356 18.8885 18.3023 18.5252 19.803 17.6072L19.8019 17.606C21.5884 16.4934 22.3714 15.8465 21.6919 14.4909C21.2813 13.7214 20.1743 13.0779 18.7005 14.1905C18.7005 14.1905 16.7104 15.7149 13.5008 15.7149C10.29 15.7149 8.30101 14.1905 8.30101 14.1905C6.82839 13.0723 5.71689 13.7214 5.31076 14.4909Z' fill='white'/>
									<path d='M13.4991 13.6597C17.4119 13.6597 20.6069 10.602 20.6069 6.83775C20.6069 3.05775 17.4119 0 13.4991 0C9.58526 0 6.39026 3.05775 6.39026 6.83775C6.39026 10.602 9.58526 13.6597 13.4991 13.6597ZM13.4991 3.46388C15.4218 3.46388 16.99 4.9725 16.99 6.83775C16.99 8.68725 15.4218 10.1959 13.4991 10.1959C11.5765 10.1959 10.0083 8.68725 10.0083 6.83775C10.0071 4.97137 11.5754 3.46388 13.4991 3.46388Z' fill='white'/>
								</svg>";
					}
					$html .= "<a target='_blank' onclick=\"yMetrika('click_soc')\" href='{$list['link']['value']}' style='background-color:{$dt['color']};'>{$icon}</a>";
				}

				$content = "<div class='soc'><div class='soc-lines'>{$html}</div></div>";
				break;
			case 'btn':
				$content = "<a class='btn btn-inverse' href='{$dt['btn_link']}' target='_blank' onclick=\"yMetrika('main_website')\"><span>{$dt['btn_text']}</span></a>";
				break;
		}
		$result[$dt['position']] = "<div class='block {$dt['checked']} ".($dt['border'] ? "block-border" : "")."' data-type='{$dt['key']}'>
						".($dt['title'] ? "<div class='block-head'>{$dt['title']}</div>" : "")."
						".($dt['text_top'] ? "<div class='block-top'><div class='text'>{$dt['text_top']}</div></div>" : "")."
						<div class='block-content'>{$content}</div>
						".($dt['text_bottom'] ? "<div class='block-bottom'><div class='text'>{$dt['text_bottom']}</div></div>" : "")."
					</div>";
	}
	ksort($result);
	return implode("", $result);
}
function scan_dir($dir) {
    $ignored = array('.', '..', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
function file_size($path){
	$bytes = filesize($path);
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem){
        if($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}
function check_filename($path, $name, $raz="zip") {
	$files = scan_dir($path);
	if(in_array($name.".".$raz, $files)) return true;
	return false;
}
function get_filename($path, $name, $raz="zip") {
	$name_check = $name;
	$i = 1;
	while (check_filename($path, $name_check, $raz)) {
		$name_check = $name."_{$i}";
		$i += 1;
	}
	return $name_check;
}
function checkEmail($email) {
	$email = trim($email);
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email)){
		return true;
	}
	return false;
}
?>
