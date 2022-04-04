<?php

class Bitrix24{

    public function __construct($title = "", $phone = "", $comment = "", $email = "", $action = ""){
		global $DOCUMENT_ROOT;

		$this->title = $title;
		$this->phone = $phone;
		$this->comment = $comment;
		$this->email = $email;
        $this->action = $action; 
		$this->contactid = 0;
        $this->bitrim_deal_id = $_SESSION['bitrim_deal_id'] ? $_SESSION['bitrim_deal_id'] : "";
        $this->bitrim_lead_id = $_SESSION['bitrim_lead_id'] ? $_SESSION['bitrim_lead_id'] : "";

    	$this->fileds = orderArray(file_get_contents($DOCUMENT_ROOT."/data/bitrix_fileds.json"), 1);
		$this->checked = get_content("integrations", "amo", "checked");
        $this->link = get_content("integrations", "bitrix", "subdomain");
		$this->type = get_content("integrations", "bitrix", "type");
    }

    public function actionAdd(){
        if($this->action != "updatePhone"){
            $this->addContact();

            if($this->type == 'deal'){
                if(!$this->bitrim_deal_id) {
                    # добавить лид
                    $dealID = $this->addDeal();
        			$this->bitrim_deal_id = $dealID;
        			$_SESSION['bitrim_deal_id'] = $dealID;
                }
            }else{
                if(!$this->bitrim_lead_id) {
                    # добавить лид
                    $leadID = $this->addLead();
        			$this->bitrim_lead_id = $leadID;
        			$_SESSION['bitrim_lead_id'] = $leadID;
                }
            }
        }

        $this->addComment();
    }
    # создание сделки
    public function addDeal(){
        global $domain;

        $fields = array(
            "fields" => array(
                "TITLE" => $this->title,
                "OPENED" => "Y"
            ),
            "params" => array("REGISTER_SONET_EVENT" => "Y")
        );
        if($this->contactid) $fields['fields']['CONTACT_ID'] = $this->contactid;
        $fields = $this->addUtms($fields);

        # воронка
		$pipeline = get_content("integrations", "bitrix", "pipelines_deal");
        if($pipeline) $fields["fields"]['CATEGORY_ID'] = (int)$pipeline;
        # статус воронки
		$status = get_content("integrations", "bitrix", "status_deal");
        if($status) $fields["fields"]['STAGE_ID'] = $status;
        # овтетсвенный
		$responsible_user = get_content("integrations", "bitrix", "bitrix_contact");
        if($responsible_user) $fields["fields"]['ASSIGNED_BY_ID'] = (int)$responsible_user;
        # создание сделки
        $lead = $this->request('crm.deal.add', $fields);
        # id lead
        $dealID = isset($lead['result']) ? $lead['result'] : 0;

        return $dealID;
    }
    # создание лид
    public function addLead(){
        global $domain;

        $fields = array(
            "fields" => array(
                "TITLE" => $this->title,
                "OPENED" => "Y",
            ),
            "params" => array("REGISTER_SONET_EVENT" => "Y")
        );
        if($this->contactid) $fields['fields']['CONTACT_ID'] = $this->contactid;
        $fields = $this->addUtms($fields);

        # статус воронки
        $status = get_content("integrations", "bitrix", "status_lead");
        if($status) $fields["fields"]['STATUS_ID'] = $status;
        # овтетсвенный
		$responsible_user = get_content("integrations", "bitrix", "bitrix_contact");
        if($responsible_user) $fields["fields"]['ASSIGNED_BY_ID'] = (int)$responsible_user;
        # создание сделки
        $lead = $this->request('crm.lead.add', $fields);
        # id lead
        $leadID = isset($lead['result']) ? $lead['result'] : 0;

        return $leadID;
    }
    # Создаем контакт и привязыаем к сделке
    public function addContact(){
        $contacID = 0;
        # поиск с 8 в начале
        $phoneSecond = $this->phone;
        if($phoneSecond[0] == "7") $phoneSecond[0] = "8";
        # уже существует
        $result = $this->request("crm.contact.list", array(
                'filter' => array("LOGIC" => "OR", "PHONE" => array($this->phone, $phoneSecond)),
                'select' => array("ID", "NAME", "LAST_NAME")
            )
        );
        $contacID = isset($result['result'][0]['ID']) ? $result['result'][0]['ID'] : 0;

        # создать новый контакт
        if(!$contacID){
            $fields = array(
                'fields' => array(
                    "NAME" => "Уточнить имя",
                    "OPENED" => "Y",
                    "PHONE" => array(
                        array("VALUE" => $this->phone, "VALUE_TYPE" => "WORK")
                    )
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")
            );
            if($this->email && checkEmail($this->email)){
                $fields['fields']['EMAIL'] = array(
                    array("VALUE" => $this->email, "VALUE_TYPE" => "WORK")
                );
            }
            $contacts =  $this->request("crm.contact.add", $fields);
            $contacID = isset($contacts['result']) ? $contacts['result'] : 0;
        }

        $this->contactid = $contacID;
    }
    # добавить комментарий
    public function addComment(){
        if($this->bitrim_deal_id){
            $this->request("crm.timeline.comment.add", array(
                    'fields' => array(
                        "ENTITY_ID" => $this->bitrim_deal_id,
                        "ENTITY_TYPE" => "deal",
                        "COMMENT" => $this->comment
                    )
                )
            );
        }
        if($this->bitrim_lead_id){
            $this->request("crm.timeline.comment.add", array(
                    'fields' => array(
                        "ENTITY_ID" => $this->bitrim_lead_id,
                        "ENTITY_TYPE" => "lead",
                        "COMMENT" => $this->comment
                    )
                )
            );
        }
    }

    public function addUtms($fields){
        if($_SESSION['utm_source']) $fields['fields']['UTM_SOURCE'] = $_SESSION['utm_source'];
        if($_SESSION['utm_medium']) $fields['fields']['UTM_MEDIUM'] = $_SESSION['utm_medium'];
        if($_SESSION['utm_campaign']) $fields['fields']['UTM_CAMPAIGN'] = $_SESSION['utm_campaign'];
        if($_SESSION['utm_content']) $fields['fields']['UTM_CONTENT'] = $_SESSION['utm_content'];
        if($_SESSION['utm_term']) $fields['fields']['UTM_TERM'] = $_SESSION['utm_term'];
        return $fields;
    }
    # проверка статуса подключения
	public function checkStatus(){
        $response = $this->request($this->type == 'lead' ? "crm.lead.list" : "crm.deal.list");
		return $response && isset($response['result']) ? true : false;
	}
    # проверка на присутсвие данных и включена ли отправка заявок
	public function checkData(){
        $good = $this->checked && $this->link ? true : false;
		return $good;
	}

    # путь до файла с токеном
    public function getPath(){
		global $ROOTDIR, $DOCUMENT_ROOT;
        return ($DOCUMENT_ROOT ? $DOCUMENT_ROOT : $ROOTDIR)."/data/bitrix24crm.ini";
    }
    # удалить файл с получиным токеном
    public function iniClear(){
        $path = $this->getPath();
        if(is_file($path)) unlink($path);
    }

    public function __call($name, $data = array()){
        $apiMethod = $this->getApiMethod($name);
        return $this->request($apiMethod, $data);
    }
    public function getApiMethod($name){
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $name, $matches);
        $segments = array_map(function ($item) {
            return mb_strtolower($item);
        }, $matches[1]);
        return implode('.', $segments);
    }
    public function request($method, $data = array()){
        $queryData = http_build_query($data);
        $link = $this->link.'/'.$method;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $link,
            CURLOPT_POSTFIELDS => $queryData,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);
        return $result;
    }

	public function getActuaclFields(){
		global $DOCUMENT_ROOT;

		# получить воронки
		$bitrix_fields = array();

        $pipelines_deal = $this->request("crm.dealcategory.list");
		$bitrix_fields['pipelines_deal'] = $pipelines_deal['result'];

        $bitrix_fields['status_deal'] = array();
        $status_deal = $this->request("crm.dealcategory.stage.list", array('id' => 0));
		$bitrix_fields['status_deal'][0] = $status_deal['result'];
        foreach ($pipelines_deal['result'] as $pipelines) {
            $status_deal = $this->request("crm.dealcategory.stage.list", array('id' => $pipelines['ID']));
    		$bitrix_fields['status_deal'][$pipelines['ID']] = $status_deal['result'];
        }

        $users = $this->request("user.get");
		$bitrix_fields['users'] = $users['result'];

		$now = new DateTime("NOW");
		$bitrix_fields['fields_updated'] = $now->format("Y-m-d H:i:s");

        $status = $this->request("crm.status.list");
        $status_lead = array("" => "-");
        foreach ($status['result'] as $sts) {
            if($sts['ENTITY_ID'] == "STATUS"){
                $status_lead[$sts['STATUS_ID']] = $sts['NAME'];
            }
        }
		$bitrix_fields['status_lead'] = $status_lead;

		file_put_contents($DOCUMENT_ROOT."/data/bitrix_fileds.json", json_encode($bitrix_fields));
	}
}
