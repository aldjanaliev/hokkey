<?

class Amocrm{

	public $classkey = 'amocrm';

    public $errors = [
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    ];

	public function __construct($title = "", $phone = "", $comment = "", $email = "", $action = "") {
		global $DOCUMENT_ROOT;

		$this->title = $title;
		$this->phone = $phone;
		$this->comment = $comment;
		$this->email = $email;
		$this->action = $action;
		$this->amo_lead_id = $_SESSION['amo_lead_id'] ? $_SESSION['amo_lead_id'] : "";
    	$this->fileds = orderArray(file_get_contents($DOCUMENT_ROOT."/data/amo_fileds.json"), 1);
		$this->checked = get_content("integrations", "amo", "checked");
		$this->amo_subdomain = get_content("integrations", "amo", "subdomain");
		$this->amo_client_secret = get_content("integrations", "amo", "amo_client_secret");
		$this->amo_client_id = get_content("integrations", "amo", "amo_client_id");
		$this->amo_code = get_content("integrations", "amo", "amo_code");
	}

    public function actionLead(){

        # update lead
        if(!$this->amo_lead_id) {
            # добавить лид
            $leadID = $this->addLead();
			$this->amo_lead_id = $leadID;
			$_SESSION['amo_lead_id'] = $leadID;
        }
		if($this->action != "updatePhone") $this->addContact();
        # add comment info
        $this->addComment($comment, 'common');
    }
    # создание лида
    public function addLead(){
        global $domain;

        $fields = array(
            'name' => $this->title,
            'created_by' => 0,
            '_embedded' => array(
                'tags' => array(
					array('name' => 'Квиз'),
		            array('name' => $domain),
		        )
            )
        );
		$fields['custom_fields_values'] = $this->getFieldReserveCreate();
        # воронка
		$pipeline = get_content("integrations", "amo", "amo_pipeline");
        if($pipeline) $fields['pipeline_id'] = (int)$pipeline;
        # статус воронки
		$status = get_content("integrations", "amo", "amo_status");
        if($status) $fields['status_id'] = (int)$status;
        # овтетсвенный
		$responsible_user = get_content("integrations", "amo", "amo_contact");
        if($responsible_user) $fields['responsible_user_id'] = (int)$responsible_user;
        # создание сделки
        $lead = $this->request('/api/v4/leads', array($fields));
        # id lead
        $leadID = isset($lead['_embedded']['leads'][0]['id']) ? $lead['_embedded']['leads'][0]['id'] : 0;

        return $leadID;
    }

    public function getFieldReserveCreate(){
        $params = array();
		$utms = array(
			'utm_source' => $_SESSION['utm_source'],
			'utm_medium' => $_SESSION['utm_medium'],
			'utm_campaign' => $_SESSION['utm_campaign'],
			'utm_content' => $_SESSION['utm_content'],
			'utm_term' => $_SESSION['utm_term'],
		);

		# utm
		foreach ($utms as $key => $val) {
			if(!$val) continue;
			if(in_array($key, array("utm_source", "utm_medium", "utm_campaign", "utm_content", "utm_term", "utm_referrer", "roistat"))){
	            $params[] = array(
	                'field_code' => strtoupper($key),
	                'values' => array(array(
						'value' => (string)$val
					))
	            );
			}
		}

		return $params;
	}
    # добовляем комент к сделке
    public function addComment($type = "service_message"){
        if(!$this->amo_lead_id || !$this->comment) return false;

		$message = htmlspecialchars_decode(htmlspecialchars_decode($this->comment, ENT_QUOTES), ENT_QUOTES);

        if($type == "service_message"){
            # сервисное примечание
            $fields = array(
                array(
                    'entity_id' => (int)$this->amo_lead_id,
                    'note_type' => 'service_message',
                    'params' => array(
                        "service" => "Restopace",
                        'text' => str_replace("\n", "; ", $message)
                    )
                )
            );
        }else{
            # комментарий
            $fields = array(
                array(
                    'entity_id' => (int)$this->amo_lead_id,
                    'note_type' => 'common',
                    'params' => array(
                        'text' => $message
                    )
                )
            );
        }

        $this->request('/api/v4/leads/notes', $fields);
    } 
    # Создаем контакт и привязыаем к сделке
    public function addContact(){
        if(!$this->amo_lead_id) return false;

        $contacID = 0;

        # уже существует
        $contacts = $this->request("/api/v4/contacts?query={$this->phone}&order[id]=desc");
        if($contacts) $contacID = isset($contacts['_embedded']['contacts'][0]['id']) ? $contacts['_embedded']['contacts'][0]['id'] : 0;

        # поиск с 8 в начале
        $phoneSecond = $this->phone;
        if(!$contacID && $phoneSecond[0] == "7"){
            $phoneSecond[0] = "8";
            $contacts = $this->request("/api/v4/contacts?query={$phoneSecond}&order[id]=desc");
            if($contacts) $contacID = isset($contacts['_embedded']['contacts'][0]['id']) ? $contacts['_embedded']['contacts'][0]['id'] : 0;
        }

        # создать новый контакт
        if(!$contacID){
            $name = "Уточнить имя";
            $fields = array(
                array(
                    'first_name' => $name,
                    'custom_fields_values' => array(
                        array(
                            'field_code' => 'PHONE',
                            'values' => array(array('value' => (int)$this->phone, 'enum_code' => 'MOB'))
                        )
                    )
                )
            );
            if($this->email && checkEmail($this->email)){
                $fields[0]['custom_fields_values'][] = array(
                    'field_code' => 'EMAIL',
                    'values' => array(array('value' => $this->email))
                );
            }
            $contacts = $this->request('/api/v4/contacts', $fields);
            $contacID = isset($contacts['_embedded']['contacts'][0]['id']) ? $contacts['_embedded']['contacts'][0]['id'] : 0;
        }

        # прикрепить к лиду
        if($contacID){
            $fields = array(
                array(
                    'to_entity_id' => (int)$contacID,
                    'to_entity_type' => 'contacts',
                    'metadata' => array('is_main' => true)
                )
            );
            $contacts = $this->request('/api/v4/leads/'.$this->amo_lead_id.'/link', $fields);
        }
        return $contacID;
    }


    # проверка статуса подключения
	public function amoCheckStatus(){
        $response = $this->request("/api/v4/account");
		return $response && $response['id'] ? true : false;
	}

    # проверка на присутсвие данных и включена ли отправка заявок
	public function checkData(){
        $good = $this->checked && $this->amo_subdomain && $this->amo_client_secret && $this->amo_client_id && $this->amo_code ? true : false;
		return $good;
	}

    # request amocrm
    public function request($path, $data = array(), $type = "POST"){
        $link = 'https://'.$this->amo_subdomain.'.amocrm.ru'.$path;

        $access_token = $this->getTokenFile();
		if(!$access_token) return false;

        $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if($data){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $code = (int)$code;
        $result = orderArray($out);
		// echo print_r($data, 1);
		// echo print_r($result, 1);
        /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
        if ($code < 200 || $code > 204) {

        }else{
            return $result;
        }
    }


    # получение актуального токена
    public function updateToken($type = "update"){
		global $domain;

        # не хватает параметров
        if(!$this->amo_subdomain || !$this->amo_client_secret || !$this->amo_client_id || !$this->amo_code){
            return false;
        }

        $link = 'https://'.$this->amo_subdomain.'.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

        $data = [
            'client_id' => $this->amo_client_id,
            'client_secret' => $this->amo_client_secret,
            'redirect_uri' => 'https://quiz24.ru/',
        ];

        if($type == "first"){
            $data['grant_type'] = 'authorization_code';
            $data['code'] = $this->amo_code;
        }else{
            $refresh_token = $this->getTokenFile("refresh_token");
            # если нету токена на получение нового, генерируем новый токен по временному ключу
            if(!$refresh_token) return $this->updateToken("first");

            $data['grant_type'] = 'refresh_token';
            $data['refresh_token'] = $refresh_token;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $code = (int)$code;

        $response = orderArray($out);
        $path = $this->getPath();

        if(isset($response['access_token']) && $response['access_token']){
            # на всякий случай вычитаем 2 минуты из времени жизни токена
            $response['timestamp'] = time() + $response['expires_in'] - 120;
            # save data to file amocrm
            file_put_contents($path, json_encode($response));

            return $response['access_token'];
        }else{
            # remove file amocrm
            $this->iniClear();

            # генерируем новый токен по временному ключу
            if($type == "update") {
                return $this->updateToken("first");
            }

            return false;
        }
    }


    # путь до файла с токеном
    public function getPath(){
		global $ROOTDIR, $DOCUMENT_ROOT;
        return ($DOCUMENT_ROOT ? $DOCUMENT_ROOT : $ROOTDIR)."/data/amocrm.ini";
    }
    # удалить файл с получиным токеном
    public function iniClear(){
        $path = $this->getPath();
        if(is_file($path)) unlink($path);
    }
    # получить тоекн из файла
    public function getTokenFile($key = "access_token", $isFirst = true){
        $path = $this->getPath();
        if(!is_file($path)) return false;

        $json = file_get_contents($path);
        $data = $json ? orderArray($json) : array();

        # проверка чтобы не зациклился
        if($isFirst && $key == "access_token"){
            # токен устарел, получить новый токен
            if(!$data || $data['timestamp'] < time()) {
                $this->updateToken();
                return $this->getTokenFile($key, false);
            }
        }

        return isset($data[$key]) ? $data[$key] : "";
    }

    # получить статусы воронки
    public function getPeplines(){
        $pipelines = $this->request("/api/v4/leads/pipelines");
        if($pipelines['_embedded']['pipelines']){
            return $pipelines['_embedded']['pipelines'];
        }
        return array();
    }
    # получить пользователей
    public function getUsers(){
        $users = $this->request("/api/v4/users");
        if($users['_embedded']['users']){
			$result = $users['_embedded']['users'];
			foreach ($result as $key => $item) {
				unset($result[$key]['rights']);
				unset($result[$key]['_links']);
			}
            return $result;
        }
        return array();
    }

	public function getActuaclFields(){
		global $DOCUMENT_ROOT;

		# получить воронки
		$amo_fields = array();
		$amo_fields['pipelines'] = $this->getPeplines();
		$amo_fields['users'] = $this->getUsers();
		$now = new DateTime("NOW");
		$amo_fields['fields_updated'] = $now->format("Y-m-d H:i:s");

		file_put_contents($DOCUMENT_ROOT."/data/amo_fileds.json", json_encode($amo_fields));
	}

}
