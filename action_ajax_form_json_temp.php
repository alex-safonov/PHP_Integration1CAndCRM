<?php

// Формируем массив для JSON ответа

$params = array(
    'name' => $_POST['name'],
    'string' => $_POST['string'],
    'quantity' => $_POST['quantity'],
); 

// Эмулируем массив для JSON ответа

// $params = array(
//     'name' => '1C.csv',
//     'string' => '0',
//     'quantity' => '1',
// ); 


function add8($value) { // функция добавления 8 к телефонам
    return '8'.$value;
};


/* ===========================================================================================*/
/* ====================== ФУНКЦИЯ ЗАПРОСА ДАННЫХ ИЗ CRM, ДОБАВЛЕНА В API =====================*/
/* ==================================== FUNCTION SENDTOBTX ===================================*/
/* ===========================================================================================*/

/*function sendtoBTX($metodBTX,$params,$checkError='continue',$logError='error') {
        global $now_token;
        $api_error = false;
        if(!$params['auth']) { $params['auth'] = $now_token['access_token']; }
        $url = 'https://martines.bitrix24.ru/rest/'.$metodBTX.'.json';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        $result_in = json_decode($result,true);
        if($result_in['error'] || $result_in['error_description']) {
            $api_error = $result_in['error'].' | '.$result_in['error_description'];
        }
        if($api_error != false && $checkError == 'exit' ) {
            echo '<br><b>'.$metodBTX.' - '.$api_error.'</b></br>';
            file_put_contents('/var/www/martines.ru/web/bitrix24-api/logs/'.$logError.'_'.date('Y-m-d').'.txt', PHP_EOL.date('[Y-m-d H:i:s] ').$metodBTX.' - '.$api_error, FILE_APPEND);
            //оповещение о ошибке?
            exit();
        } elseif($api_error != false) {
            echo '<br><b>'.$metodBTX.' - '.$api_error.'</b></br>';
            file_put_contents('/var/www/martines.ru/web/bitrix24-api/logs/'.$logError.'_'.date('Y-m-d').'.txt', PHP_EOL.date('[Y-m-d H:i:s] ').$metodBTX.' - '.$api_error, FILE_APPEND);
            //оповещение о ошибке?
            return $result;
        } else {
            return $result;
        }
}*/
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* =========================== ФУНКЦИЯ ПОИСКА ДАННЫХ КОНТАКТОВ В CRM =========================*/
/* ================================== FUNCTION GETLASTCONTACT ================================*/
/* ===========================================================================================*/

/*function getLastContact($RequestLead_Phone, $RequestLead_Email, $RequestLead_IM) {

    //поиск дублей по телефону
    if($RequestLead_Phone) {
    $params = array(
        'type' => 'PHONE',
        'entity_type' => 'CONTACT',
        'order' => array( 
            'ID' => 'ASC' 
        ),
        'values' => $RequestLead_Phone
    );
    $result = sendtoBTX('crm.duplicate.findbycomm',$params);
    $arDublesPhone = json_decode($result,true);
    
    //echo '<br>----dublephone-----<pre>';
    //print_r($arDublesPhone);
    //echo '</pre>-----------<br>';
    
    }
    
    //поиск дублей по емайлу
    if($RequestLead_Email) {
    $params = array(
        'type' => 'EMAIL',
        'entity_type' => 'CONTACT',
        'order' => array( 
            'ID' => 'ASC' 
        ),
        'values' => $RequestLead_Email
    );
    $result = sendtoBTX('crm.duplicate.findbycomm',$params);
    $arDublesEmail = json_decode($result,true);
    
    //echo '<br>----dublemail-----<pre>';
    //print_r($arDublesEmail);
    //echo '</pre>-----------<br>';
    
    }

    if($RequestLead_IM) {
    //Выборка по IM
    foreach ($RequestLead_IM as $key => $value) {   
        $IMfilter[] = $value['VALUE'];
    }
    $params = array(
        'filter' => array(
                'IM' => $IMfilter,
        ),
        'select' => array( 
            'ID',
            'IM',
        ),
    );
    $result = sendtoBTX('crm.contact.list',$params);
    $arSearchIM = json_decode($result,true);

    foreach ($arSearchIM['result'] as $key => $value) {
        foreach ($value['IM'] as $key2 => $value2) {
            $SearchIM[$value['ID'].'_'.$key2]['ID'] = $value['ID'];
            $SearchIM[$value['ID'].'_'.$key2]['VALUE_TYPE'] = $value2['VALUE_TYPE'];
            $SearchIM[$value['ID'].'_'.$key2]['VALUE'] = $value2['VALUE'];
        }
    }   
    
    foreach ($RequestLead_IM as $key => $value) {
        foreach ($SearchIM as $keyin => $valuein) {
            //echo $value['VALUE'].' | '.$value['VALUE_TYPE'].' - '.$valuein['VALUE'].' | '.$valuein['VALUE_TYPE'].'<br>';
            if($value['VALUE'] == $valuein['VALUE'] && $value['VALUE_TYPE'] == $valuein['VALUE_TYPE']) {
                $IMresult[] = $valuein['ID'];
            }
        }
    }
    $IMresult = array_unique($IMresult);
    
    //echo '<br>----dubleIM-----<pre>';
    //print_r($IMresult);
    //echo '</pre>-----------<br>';
    
    }
    
    if(count($arDublesPhone['result']['CONTACT']) > 0 || count($arDublesEmail['result']['CONTACT']) > 0 || count($IMresult) > 0) {
        $Contacts = array_unique(array_merge((array)$arDublesPhone['result']['CONTACT'], (array)$arDublesEmail['result']['CONTACT'], (array)$IMresult));
        return $Contacts;
    } else {
        return false;
    }
}   */
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ============================ ФУНКЦИЯ ПОИСКА ДАННЫХ ЛИДОВ В CRM ============================*/
/* =================================== FUNCTION GETLASTLEAD ==================================*/
/* ===========================================================================================*/

function getLastLead($RequestLead_Phone, $RequestLead_Email) {

    //поиск дублей по телефону
    if($RequestLead_Phone) {
        $params = array(
            'type' => 'PHONE',
            'entity_type' => 'LEAD',
            'order' => array( 
                'ID' => 'ASC' 
            ),
            'values' => $RequestLead_Phone
        );
    $result = sendtoBTX('crm.duplicate.findbycomm',$params); // ищем совпадающие значения телефонов в Лиде
    $arDublesPhone = json_decode($result,true);     
    }
    
    //поиск дублей по емайлу
    if($RequestLead_Email) {
        $params = array(
            'type' => 'EMAIL',
            'entity_type' => 'LEAD',
            'order' => array( 
                'ID' => 'ASC' 
            ),
            'values' => $RequestLead_Email
        );
    $result = sendtoBTX('crm.duplicate.findbycomm',$params); // ищем совпадающие значения e-mail'ов в Лиде
    $arDublesEmail = json_decode($result,true);
    }

    // Создаем массив данных с уникальными значениями телефонов и e-mail'ов:
    if(count($arDublesPhone['result']['LEAD']) > 0 || count($arDublesEmail['result']['LEAD']) > 0) {
        $ContactALL_Leads = array_unique(array_merge((array)$arDublesPhone['result']['LEAD'], (array)$arDublesEmail['result']['LEAD']));
        return $ContactALL_Leads;   
        } 
    else {
        return false;
    };
}
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ====================== ФУНКЦИЯ СОЗДАНИЯ КОНТАКТА ИЗ ФАЙЛА EXCEL В CRM =====================*/
/* ===========================================================================================*/

/*function add_contact_from_1C ($key_array_for_output_information) {

echo '<pre>';  
    echo '<br>---------------------------------------------------------------------------------<br>';

// print_r($key_array_for_output_information);
// echo '<br><br>'; // выводим поле с ID1

echo 'Данные из файла Excel для загрузки в CRM:<br><br>';

foreach ($key_array_for_output_information as $k => $v) {
    echo '$k => $v';
    echo '<br>';
}

echo '<br>'; 
   
    echo '$ID_Contact_CRM = ...... // Формируем новый Контакт и Получаем значение ID контакта в CRM для передачи в 1С<br>';

    echo '<br>---------------------------------------------------------------------------------<br>';
echo '</pre>';      
}*/

/* ======================================= КОНЕЦ БЛОКА =======================================*/



/* ===========================================================================================*/
/* ====================================== ОТКРЫТИЕ ФАЙЛА =====================================*/
/* ===========================================================================================*/

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix24-api/api.php'; // Подключаем API CRM


// if(isset($_POST['all'])) { // Проверка обработки файла (весь или по блокам)
//     $params['all'] = $_POST['all']; 
// } else {
//     $params['all'] = 'off'; 
// }

$row = 0;
if (($handle = fopen($params['name'], 'r')) !== FALSE) { // Открываем файл для чтения


while ((($data = fgetcsv($handle, 1000, ';')) !== FALSE) && ($row < ($params['string']+$params['quantity'])) ){ // Считываем CSV-данные

 
   // while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) { // Считываем CSV-данные

//                echo '<pre><hr><br>Строка: ';
//                    print_r($row+1); // Выводим номер обрабатываем строки
//                echo '</pre>';


         if($row >= $params['string'] && $row < ($params['string']+$params['quantity'])) { // Обрабатываем заданные строки
        // if($row == $params['string']) { // Обрабатываем заданные строки

                $result[$row] = $data; // Обрабатываем строку с ячейками

                $_1C_name = $result[$row][0]; // выбираем поле с именем
                $_1C_date_add = $result[$row][14]; // выбираем поле с датой создания контрагента  
                $_1C_site_id = $result[$row][15]; // выбираем поле с ID сайта 
                $_1C_flag = $result[$row][16]; // выбираем поле с флагом из интернета 
                $_1C_destination = $result[$row][17]; // выбираем поле с направлением 
                $_1C_manager = $result[$row][18]; // выбираем поле с менеджером             
                $id_1c = $result[$row][21]; // выбираем поле  с ID 1С 
                $_1C_address = $result[$row][25]; // выбираем поле с адресом 

                $array_phones = array_slice($result[$row], 1, 9); // выбираем 9 полей с телефонами 
                $array_emails = array_slice($result[$row], 10, 4); // выбираем 4 поля с e-mailами 

                $array_phones_unique = array_diff(array_unique($array_phones), array('', NULL, false)); // исключаем пустые значения из уникального массива
                $array_phones_8 = array_map('add8', $array_phones_unique); // формирование массива с 8
                $array_phones_all = array_merge($array_phones_unique, $array_phones_8); // формирование массива с всеми телефонами

                $array_emails_unique = array_diff(array_unique($array_emails), array('', NULL, false)); // формирование массива с всеми e-mail адресами

                $array_phones_8_for_add = []; // создаем двумерный массив для загрузки телефонов
                foreach ($array_phones_8 as $value) {       
                    $array_phones_8_for_add[$i] = [
                        'VALUE' => $value,
                        'VALUE_TYPE' => 'HOME'
                    ];
                    $i++;
                }
                unset($value); // разорвать ссылку на последний элемент

                $array_emails_for_add = []; // создаем двумерный массив для загрузки e-mailов
                foreach ($array_emails_unique as $value) {      
                    $array_emails_for_add[$i] = [
                        'VALUE' => $value,
                        'VALUE_TYPE' => 'HOME'
                    ];
                    $i++;
                }
                unset($value); // разорвать ссылку на последний элемент

//                echo '<pre>';
//                print_r($result[$row])  ;
//                echo '</pre>';
            
                $key_array_for_output_information = array(
                'ID 1C' => $id_1c,
                'Имя' => $_1C_name,
                'Дата создания контрагента' => $_1C_date_add,
                'ID сайта' => $_1C_site_id,
                'Флаг из интернета' => $_1C_flag,
                'Направление' => $_1C_destination,
                'Менеджер' => $_1C_manager,
                'Адрес' => $_1C_address,

                'Телефон 1' => $array_phones_8[0],
                'Телефон 2' => $array_phones_8[1],
                'Телефон 3' => $array_phones_8[2],
                'Телефон 4' => $array_phones_8[3],
                'Телефон 5' => $array_phones_8[4],
                'Телефон 6' => $array_phones_8[5],
                'Телефон 7' => $array_phones_8[6],
                'Телефон 8' => $array_phones_8[7],
                'Телефон 9' => $array_phones_8[8],

                'E-mail 1' => $array_emails_unique[0],
                'E-mail 2' => $array_emails_unique[1],
                'E-mail 3' => $array_emails_unique[2],
                'E-mail 4' => $array_emails_unique[3],
                );
/* ======================================= КОНЕЦ БЛОКА =======================================*/




/* ===========================================================================================*/
/* =================== ПОИСК ЛИДОВ С СОВПАДАЮЩИМИ ТЕЛЕФОНАМИ И E-MAIL'АМИ ====================*/
/* ===========================================================================================*/

    $RequestLead_Phone = $array_phones_all; // массив телефонов для поиска в Лидах
    $RequestLead_Email = $array_emails_unique; // массив e-mail'ов для поиска в Лидах

    $ContactALL_Leads = getLastLead($RequestLead_Phone, $RequestLead_Email); // функция для поиска совпадающих значений в Лидах CRM 

//echo '<pre>';  
//    echo '<br>---------------------------------------------------------------------------------<br>';
//    echo '<br>Связанные Лиды, выводим по возрастанию:: <br><br>';
    sort($ContactALL_Leads); // Сортируем (прямая сортировка) ID Лидов с совпадающими данными для определения первого заведенного Лида - для наглядности и отладки
//    print_r($ContactALL_Leads); // Выводим ID ЛИДов с совпадающими данными
//    echo '<br>';                

//    echo '<br>---------------------------------------------------------------------------------<br>';

    $lead_min = min($ContactALL_Leads); // Выбираем ID первого заведенного Лида

    $params_lead = array(
        'id' => $lead_min,
        );

    $result5 = sendtoBTX('crm.lead.get',$params_lead); // получаем значения полей первого заведенного Лида  
    $arContact5 = json_decode($result5,true); // получаем значение для вывода

//    echo 'Информация о ЛИДе: <br>'; // Выводим информацию о Лиде

//    echo '<br>Информация ЛИДа - ID Лида: '.$arContact5[result][ID].'<br>';
/*    echo '<br>Информация ЛИДа - ID контакта: '.$arContact5[result][CONTACT_ID].'<br>';
    echo 'Информация ЛИДа - ID контакта Б24 (системное, проверочное): '.$arContact5[result][UF_CRM_1435658655].'<br>';              
    echo 'Информация ЛИДа - ID контрагента в 1С: '.$arContact5[result][UF_CRM_1551086404].'<br>';    
    echo 'Информация ЛИДа - Имя из 1С: '.$arContact5[result][UF_CRM_1567167422].'<br>';     
    echo 'Информация ЛИДа - Юрлицо из 1С: '.$arContact5[result][UF_CRM_1567167462].'<br>';   
    echo 'Информация ЛИДа - Ассоциация с 1С (304 - 'Ожидание обработки', 296 - 'Не сопоставлен', 298 - 'Сопоставлен', n0 - 'Не выбран'): '.$arContact5[result][UF_CRM_1551086354].'<br>';
    echo 'Информация ЛИДа - Дата создания: '.$arContact5[result][DATE_CREATE].'<br>';
    echo 'Информация ЛИДа - Канал, текст, системный: '.$arContact5[result][UF_CRM_1433839993].'<br>';
    echo 'Информация ЛИДа - Канал кампании (NEW): '.$arContact5[result][UF_CRM_1555925599].'<br>';
    echo 'Информация ЛИДа - Канал кампании: '.$arContact5[result][UF_CRM_1432728035].'<br>';  
    echo 'Информация ЛИДа - Источник кампании: '.$arContact5[result][UF_CRM_1432729113].'<br>';
    echo 'Информация ЛИДа - Название кампании: '.$arContact5[result][UF_CRM_1432906050].'<br>';
    echo 'Информация ЛИДа - UTM-метки, utm_medium: '.$arContact5[result][UTM_MEDIUM].'<br>';                
    echo 'Информация ЛИДа - Действие пользователя: '.$arContact5[result][UF_CRM_1432906097].'<br>';   
    echo 'Информация ЛИДа - Тип Клиента (103 - 'Новый', 105 - 'Активность 3 мес.', 107 - 'Активность 6 мес.', 109 - 'Активность 12 мес.', 111 - 'Неактивность 12 мес.''): '.$arContact5[result][UF_CRM_1436518757].'<br>';
    echo 'Информация ЛИДа - Тип Клиента (системный): '.$arContact5[result][UF_CRM_1436520273].'<br><br>'; */
//    echo 'Информация ЛИДа - Первоначальный Лид: '.$lead_min.'<br><br>'; 
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* =============== ВЫЧИСЛЯЕМ ЗНАЧЕНИЯ КАНАЛОВ КОМПАНИЙ ДЛЯ ЛИДОВ И КОНТАКТОВ =================*/
/* ===========================================================================================*/

//    echo '<br>---------------------------------------------------------------------------------<br>';

    $Lead_channel = getChannelByID($arContact5[result][UF_CRM_1432728035], lead); // функция для вывода значений Канала Компании по Лиду 

//    print_r($Lead_channel);

//    echo 'Информация ЛИДа - Канал компании (ID_LEAD): '.$Lead_channel[lead].'<br>';
    $Lead_channel_company = $Lead_channel[lead]; // Канал компании (ID_LEAD)
//    echo 'Информация ЛИДа - Канал компании (ID_CONTACT), добавляем в Канал Компании для Контакта: '.$Lead_channel[contact].'<br>';
    $Contact_channel_company = $Lead_channel[contact]; // Канал компании (ID_CONTACT), добавляем в Канал Компании для Контакта
//    echo 'Информация ЛИДа - Канал компании (ID), добавляем в Канал Компании (NEW) для Лида и Контакта (если 5783 - не определен): '.$Lead_channel[ID].'<br>';
    $Lead_channel_company_new = $Lead_channel[ID]; // Канал компании (ID_CONTACT), добавляем в Канал Компании (NEW) для Лида
    $Contact_channel_company_new = $Lead_channel[ID]; // Канал компании (ID_CONTACT), добавляем в Канал Компании (NEW) для Контакта

              
    // Обновляем данные минимального Лида:
        $params_lead_сс = array(                
            'id' => $lead_min,
            'fields' => array( 
                'UF_CRM_1555925599' => $Lead_channel_company_new, // Минимальному Лиду добавляем первоначальный Канал Компании (NEW)
            ),
        );
        // Раскомментировать: 
        $result7 = sendtoBTX('crm.lead.update',$params_lead_сс); // Обновляем значение минимальному Лиду  
        // Раскомментировать: 
        $arContact7 = json_decode($result7,true); // получаем значение для вывода       

//echo ' <br>Добавляем информацию в контакт: <br><br>'; // Выводим информацию о Лиде

    $for_contact_utm = $arContact5[result][UTM_MEDIUM];
//    echo 'UTM-метки, utm_medium: '.$for_contact_utm.'<br>';    
    $for_contact_channel_text = $arContact5[result][UF_CRM_1433839993];
//    echo 'Канал, текст, системный: '.$for_contact_channel_text.'<br>';
    $for_contact_company_name = $arContact5[result][UF_CRM_1432906050];   
//    echo 'Название кампании: '.$for_contact_company_name.'<br>'; 
    $for_contact_company_sourse = $arContact5[result][UF_CRM_1432729113];
//    echo 'Источник кампании: '.$for_contact_company_sourse.'<br>';
    $for_contact_company_channel = $arContact5[result][UF_CRM_1432728035];    
//    echo 'Канал кампании: '.$for_contact_company_channel.'<br>';  

//    echo 'Канал кампании (NEW): '.$Contact_channel_company_new.'<br>';

//    echo '<br>---------------------------------------------------------------------------------<br>';
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ===================== ДОБАВЛЕНИЕ ЛИДАМ ЗНАЧЕНИЯ 'ПЕРВОНАЧАЛЬНЫЙ ЛИД' ======================*/
/* ===========================================================================================*/

        if ($lead_min) {
            foreach ($ContactALL_Leads as $ID_lead) { // Перебор массива со связанными Лидами

                $params_lead = array(                
                    'id' => $ID_lead,
                    'fields' => array( 
                        'UF_CRM_1474361222' => $lead_min, // Всем Лидам добавляем первоначальный (наименьший ID Лида)
                    ),
                );
                $result6 = sendtoBTX('crm.lead.update',$params_lead); // Обновляем значения Лидов  
                $arContact6 = json_decode($result6,true); // получаем значение для вывода       

//                print_r($arContact6);
//                echo '<br>';

                $params_lead_timeline = array( // Устанавливаем значения параметров для обновления timeline Лидов
                    'fields' => array(
                    'ENTITY_ID' => $ID_lead,
                    'ENTITY_TYPE' => 'lead',
                    'COMMENT' => "Установлен первоначальный лид: <a href='https://martines.bitrix24.ru/crm/lead/details/".$lead_min."/' >".$arContact5[result][NAME]."</a>",
                    ),
                 );
// РАСКОММЕНТИРОВАТЬ ПРИ ЗАПУСКЕ       
$result = sendtoBTX('crm.timeline.comment.add',$params_lead_timeline);  // Обновляем timeline Лидов
            }
        unset($value); // разорвать ссылку на последний элемент    
        }

//        echo '---------------------------------------------------------------------------------<br>'; 
//echo '</pre>';  
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ============= ОПРЕДЕЛЯЕМ, ЧТО ПОЯВИЛОСЬ РАНЬШЕ - ЛИД В CRM ИЛИ КОНТАКТ В 1С ===============*/
/* ===========================================================================================*/
//    echo '<br>Что появилось раньше, курица или яйцо? <br><br>';  

    $day_add_lead_t = strtotime ($arContact5[result][DATE_CREATE]); // Переводим дату создания Лида в метку времени Unix 

    $day_add_lead = date('d.m.Y', $day_add_lead_t); // Задаем формат отображения даты в формате День-Месяц-Год

//        echo 'Дата создания ЛИДа: '.$day_add_lead.'';
//        echo '  в Unix-формате: ';
//        var_dump($day_add_lead_t);

    if (!$_1C_date_add) { // Если дата создания контакта в 1С не задана, ставим по умолчанию начальную
        $_1C_date_add = '01.01.1980';
        };

    $day_add_contact_t = strtotime ($_1C_date_add); // Переводим дату создания Контакта в метку времени Unix 
    $day_add_contact = date('d.m.Y', $day_add_contact_t); // Задаем формат отображения даты в формате День-Месяц-Год

//        echo 'Дата создания Контакта в 1С: '.$day_add_contact.'';
//        echo '  в Unix-формате: ';
//        var_dump($day_add_contact_t);

    if (!$day_add_lead_t || ($day_add_lead_t > $day_add_contact_t)) { // Если дата создания лида отсутствует или больше даты создания Контакта, то сначала появился Контакт

//                    echo '<br>Сначала появился Контакт <br>';
     }
    else {};
//        echo 'Сначала появился ЛИД<br><br>';

//    echo '<br>---------------------------------------------------------------------------------<br>'; 
//echo '</pre>';      
/* ======================================= КОНЕЦ БЛОКА =======================================*/



/* ===========================================================================================*/
/* ========================= СУЩЕСТВУЕТ ID1С ИЗ ФАЙЛА EXCEL В CRM =========================*/
/* ===========================================================================================*/

    $params2 = array(
            'filter' => array('UF_CRM_5C7E87AF41569' => $id_1c), // фильтруем контакты по наличию ID1С из файла Excel
            'select' => array('ID', 'NAME', 'PHONE', 'EMAIL', 'UF_CRM_5C7E87AF41569', 'UF_CRM_1582804562007'), // выводим необходимые нам поля
            );
    $result2 = sendtoBTX('crm.contact.list',$params2); // Получаем данные контактов из CRM
    $arContact = json_decode($result2,true); // декодируем json для вывода

//echo '<pre>';
        if ($id_1c === $arContact[result][0][UF_CRM_5C7E87AF41569]) { // проверка на совпадение ID1С из файла Excel и ID1С в CRM 

//            echo '<br>Вариант 1:  Есть совпадения ID1С из файла Excel и ID1С в CRM:<br><br>';
//            echo 'ID1С контакта в файле Excel: <br>';

//            echo '----------------<br>';
//            echo '   '.$id_1c.'<br>'; // ID1С из файла Excel
//            echo '----------------<br>';

//            echo '<br>ID1С контакта в CRM: <br>';
//            echo '----------------<br>';
//            echo '   '.$arContact[result][0][UF_CRM_5C7E87AF41569].'<br>'; // ID1С контакта в CRM
//            echo '----------------<br><br>';

//            echo '<br>ID контакта в CRM: <br>';
//            echo '----------------<br>';
//            echo '     '.$arContact[result][0][ID].'<br>'; // ID контакта в CRM
//            echo '----------------<br><br>';

//print_r($arContact);



            $ID_Contact_CRM = $arContact[result][0][ID]; // Получаем значение ID контакта в CRM для передачи в 1С 

            $status_of_create = 'v.01 - Обновляем существующий контакт:   == ПРОВЕРИТЬ ДАННЫЕ В CRM ==';

            /*echo 'Добавление следующих телефонов / e-mail'ов к контакту с таким ID1С: <br><br>';

            echo 'Телефоны из файла 1C с добавленной восьмеркой: <br>';
                foreach ($array_phones_8 as $value) { // Перебор массива с телефонами
                    print_r($value);
                    echo '<br>';
            }
                unset($value); // разорвать ссылку на последний элемент
            echo '<br>';    

            echo 'E-mail'ы из файла 1C: <br>';
                foreach ($array_emails_unique as $value) { // Перебор массива с E-mail'ами
                    print_r($value);
                    echo '<br>';
            }
                unset($value); // разорвать ссылку на последний элемент*/

//            echo '<br>';                                                  
//            echo '<br>---------------------------------------------------------------------------------<br>';
//            echo '<br><br><br>';

//            echo 'Код для дополнения данных в CRM из файла Excel<br>';




// Функция добавления телефонов и emailов в существующий контакт:

// Сравниваем даты создания Контакта в CRM и 1С:
        if (!$day_add_lead_t || ($day_add_lead_t < $day_add_contact_t)) { $status_of_contact = '741'; };// Если дата создания лида отсутствует или больше даты создания Контакта, то сначала появился Контакт
        if ($day_add_lead_t >= $day_add_contact_t) { $status_of_contact = '743'; };

        $params_update_contact = array(  
            'id' => $arContact[result][0][ID],           
            'fields' => array( 
                'EMAIL' => $array_emails_for_add, //   добавляем e-mailы из созданного двумерного массива            
                'PHONE' => $array_phones_8_for_add, //  добавляем телефоны из созданного двумерного массива
                'UF_CRM_1567167611' => $_1C_name, // добавляем из файла Excel поле с именем
                'UF_CRM_1582636011363' => $_1C_flag, //  добавляем из файла Excel поле с значением 'флаг из интернета'   
                'ADDRESS' => $_1C_address, //  добавляем из файла Excel адрес 
                'UF_CRM_1582804562007' => $status_of_contact, // добавляем полю 'Статус Контакта' значение 'новый (741)' или 'действующий (743)'
            ),
        );
         //Раскомментировать:                         
        $result9 = sendtoBTX('crm.contact.update', $params_update_contact); // Создаем контакт  
        // Раскомментировать:                        
        $arContact9 = json_decode($result9,true); // получаем значение для вывода        

//            echo '<br><br><br>';
//            echo '---------------------------------------------------------------------------------</br>';

/*            echo 'Данные о контакте с совпадающими ID1С из файла Excel и ID1С в CRM: <br><br>'; 
                print_r($arContact);  // Выводим данные контакта из CRM*/
            }
        else {
//        echo '<br>Данные о контакте в CRM: <br>'; 
//        echo 'Нет совпадения ID1С из файла Excel и ID1С в CRM<br><br>';

//        echo '<br>---------------------------------------------------------------------------------<br>';

//echo '</pre>';          
        }                   
/* ======================================= КОНЕЦ БЛОКА =======================================*/



/* ===========================================================================================*/
/* ===================================== ПРОВЕРКА ЛОГИКИ =====================================*/
/* ============== В CRM НЕТ ID1C, НО НАЙДЕНЫ СОВПАДАЮЩИЕ ТЕЛЕФОНЫ И/ИЛИ E-MAIL'Ы =============*/
/* ================================ СОЗДАЕМ СВЯЗАННЫЕ КОНТАКТЫ ===============================*/
/* ===========================================================================================*/

    $ContactALL = getLastContact($array_phones_all, $array_emails_unique); // функция для поиска значений контактов в CRM, добавлена в API 

  
    if (($id_1c !== $arContact[result][0][UF_CRM_5C7E87AF41569]) & $ContactALL) { // Проверка наличия ID1С из файла Excel в CRM 
//echo '<pre>';
//    echo '<br>---------------------------------------------------------------------------------<br>';

//    echo '<br>Вариант 2:  Нет совпадений ID1С из файла Excel и ID1С в CRM, но в CRM есть контакты с совпадающим телефоном или e-mailом/<br><br>';
//    echo 'ID контактов в CRM по убыванию: <br><br>';
    //rsort($ContactALL); // Первый вариант: Сортируем (обратная сортировка) ID контактов с совпадающими данными для определения последнего заведенного контакта
    sort($ContactALL); // Сортируем ID контактов с совпадающими данными для определения последнего заведенного контакта
//    print_r($ContactALL); // Выводим ID контактов с совпадающими данными
//    echo '</br>';                

//    echo '<br>---------------------------------------------------------------------------------<br>';

    $double_contact = 0; // Ставим метку для определения ID1C у двойных контактов

        foreach ($ContactALL as $value_ID) { // Перебираем массив с ID контактов
//            echo '<br>ID контакта CRM: '.$value_ID.'<br>'; 
        
            $params3 = array(
                        'filter' => array('ID' => $value_ID), // Передаем ID контакта для получения информации о нем
                        'select' => array('ID', 'NAME', 'PHONE', 'EMAIL', 'UF_CRM_5C7E87AF41569'), // выводим необходимые нам поля
                    );
            $result3 = sendtoBTX('crm.contact.list',$params3); // получаем значения полей контакта
            $arContact3 = json_decode($result3,true); // декодируем json для вывода

                if (!$arContact3[result][0][UF_CRM_5C7E87AF41569]) { // Проверка на наличие какого-либо ID1С в CRM 
                    
//                    echo '<br>У контакта нет ID1C, заведенного в CRM. Добавляем данные из файла, создаём связанные контакты. <br><br>';

//                    echo '<br>Добавляем ID1C из файла в CRM: <br><br>'; 
//                    echo $id_1c.' ===> CRM<br><br>'; 

                    // Функция добавления телефонов и emailов в существующий контакт:

                    $params_update_contact = array(  
                        'id' => $value_ID,           
                        'fields' => array( 
                            'EMAIL' => $array_emails_for_add, //   добавляем e-mailы из созданного двумерного массива            
                            'PHONE' => $array_phones_8_for_add, //  добавляем телефоны из созданного двумерного массива
                            'UF_CRM_5C7E87AF41569' => $id_1c, //  добавляем ID1C
                            'UF_CRM_1567167611' => $_1C_name, // добавляем из файла Excel поле с именем в поле 'Имя из 1С'
                            'UF_CRM_1582636011363' => $_1C_flag, //  добавляем из файла Excel поле с значением 'флаг из интернета'   
                            'ADDRESS' => $_1C_address, //  добавляем из файла Excel адрес 
                        ),
                    );

                    // Раскомментировать:                   
                    $result4 = sendtoBTX('crm.contact.update',$params_update_contact); // добавляем ID1С из файла Excel в CRM, обновляя данные контакта 
                    // Раскомментировать:                       
                    $arContact4 = json_decode($result4,true); // получаем значение для вывода

                    $ID_Contact_CRM = $value_ID; // Получаем значение ID контакта в CRM для передачи в 1С 

                    $status_of_create = 'v.02 - Обновляем существующий контакт по найденым совпадающим телефонам / emailам:   == ПРОВЕРИТЬ ДАННЫЕ В CRM ==';

                    $double_contact = 1; // Ставим метку, что есть контакт без ID1C, заполняем данные

                    break;
                }
                else {
//                    echo 'У контакта есть ID1C в CRM: '.$arContact3[result][0][UF_CRM_5C7E87AF41569].'. (ID1C в файле Excel: '.$id_1c.'). Переходим к следующему контакту. <br><br>';    

                   // $double_contact = 2; // Ставим метку, что есть двойные контакты с ID1C                 

//                    echo '<br>---------------------------------------------------------------------------------<br><br>';
                }
        }
//                    echo 'Формируем новый контакт:';
//                    echo '<br>'.$double_contact.'<br>';
//                    echo 'Вызываем функцию создания нового контакта<br>';

                // Функция создания контакта:

            if ($double_contact != 1) {
            $params_new_contact = array(                
                'fields' => array( 
                    'NAME' => $_1C_name, // добавляем из файла Excel поле с именем
                    'EMAIL' => $array_emails_for_add, //   добавляем e-mailы из созданного двумерного массива            
                    'PHONE' => $array_phones_8_for_add, //  добавляем телефоны из созданного двумерного массива
                    'UF_CRM_5C7E87AF41569' => $id_1c, //  добавляем из файла Excel поле с ID1C
                    'UF_CRM_1582632934067' => $_1C_date_add, //  добавляем из файла Excel поле с датой создания контрагента 
                    'UF_CRM_1582636011363' => $_1C_flag, //  добавляем из файла Excel поле с значением 'флаг из интернета'   
                    'ADDRESS' => $_1C_address, //  добавляем из файла Excel адрес 
                    'ASSIGNED_BY_ID' => '7', //  заполняем поле 'Ответственный' 
                    'UF_CRM_1567167611' => $_1C_name, // добавляем из файла Excel поле с именем в поле 'Имя из 1С'
                    'UF_CRM_1575625700' => $ContactALL, // добавляем ID связанных контактов в поле 'Связанные контакты'
                    'UF_CRM_1582804562007' => '743', // добавляем полю 'Статус Контакта' значение 'действующий'
                    'UF_CRM_1436519678' => '115', // добавляем полю 'Тип Клиента' значение 'Активность 3 месяца'

                    'UTM_SOURCE' => $for_contact_utm, //  добавляем из Лида: UTM-метки (utm_medium) 
                    'UF_CRM_1433840026' => $for_contact_channel_text, //  добавляем из Лида: Канал, текст, системный 
                    'UF_CRM_1433337265' => $for_contact_company_name, //  добавляем из Лида: Название кампании 
                    'UF_CRM_1433337254' => $for_contact_company_sourse, //  добавляем из Лида: Источник кампании 
                    'UF_CRM_1433337241' => $for_contact_company_channel, //  добавляем из Лида: Канал кампании 
                    'UF_CRM_1557732681' => $Contact_channel_company_new, //  добавляем из Лида: Канал кампании (NEW) 
                    'UF_CRM_5ACB5A147ECC0' => $lead_min, //  добавляем значение Первоначальный Лид 
                    'UF_CRM_1574949535' => $ContactALL_Leads, //  добавляем значение Связанные Лиды 
                ),
            );
            // Раскомментировать:               
            $result8 = sendtoBTX('crm.contact.add', $params_new_contact); // Создаем контакт  
            // Раскомментировать:                   
            $arContact8 = json_decode($result8,true); // получаем значение для вывода        

            // Раскомментировать:               
//            echo 'Выводим ID контакта в CRM:<br>'.$arContact8[result].'<br>';

            // Раскомментировать:               
            $ID_Contact_CRM = $arContact8[result];

            $status_of_create = 'v.03 - Создаем новый контакт (есть связанные контакты):   == ПРОВЕРИТЬ ДАННЫЕ В CRM ==';

//            echo '<br>---------------------------------------------------------------------------------<br>';           
            }
    }
//echo '</pre>';         
        unset($value_ID); // разорвать ссылку на последний элемент
 
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ===================================== ПРОВЕРКА ЛОГИКИ =====================================*/
/* ================== В CRM НЕТ ID1C, НЕТ СОВПАДАЮЩИХ ТЕЛЕФОНОВ И E-MAIL'ОВ ==================*/
/* ================= СОЗДАЕМ КОНТАКТ В CRM С ТЕЛЕФОНАМИ И E-MAIL'АМИ ИЗ ФАЙЛА ================*/
/* ===========================================================================================*/
 
    if (!$arContact[result][0][UF_CRM_5C7E87AF41569] & !$ContactALL) {
//echo '<pre>';         
//        echo '<br><br>';
//        echo 'Вызываем функцию создания нового контакта<br><br>';

// Функция создания контакта:

        $params_new_contact = array(                
            'fields' => array( 
                'NAME' => $_1C_name, // добавляем из файла Excel поле с именем
                'EMAIL' => $array_emails_for_add, //   добавляем e-mailы из созданного двумерного массива            
                'PHONE' => $array_phones_8_for_add, //  добавляем телефоны из созданного двумерного массива
                'UF_CRM_5C7E87AF41569' => $id_1c, //  добавляем из файла Excel поле с ID1C
                'UF_CRM_1582632934067' => $_1C_date_add, //  добавляем из файла Excel поле с датой создания контрагента 
                'UF_CRM_1582636011363' => $_1C_flag, //  добавляем из файла Excel поле с значением 'флаг из интернета'   
                'ADDRESS' => $_1C_address, //  добавляем из файла Excel адрес 
                'ASSIGNED_BY_ID' => '7', //  заполняем поле 'Ответственный' 
                'UF_CRM_1567167611' => $_1C_name, // добавляем из файла Excel поле с именем в поле 'Имя из 1С'
                'UF_CRM_1582804562007' => '743', // добавляем полю 'Статус Контакта' значение 'действующий'
                'UF_CRM_1436519678' => '115', // добавляем полю 'Тип Клиента' значение 'Активность 3 месяца'  

                'UTM_SOURCE' => $for_contact_utm, //  добавляем из Лида: UTM-метки (utm_medium) 
                'UF_CRM_1433840026' => $for_contact_channel_text, //  добавляем из Лида: Канал, текст, системный 
                'UF_CRM_1433337265' => $for_contact_company_name, //  добавляем из Лида: Название кампании 
                'UF_CRM_1433337254' => $for_contact_company_sourse, //  добавляем из Лида: Источник кампании 
                'UF_CRM_1433337241' => $for_contact_company_channel, //  добавляем из Лида: Канал кампании 
                'UF_CRM_1557732681' => $Contact_channel_company_new, //  добавляем из Лида: Канал кампании (NEW)    
                'UF_CRM_5ACB5A147ECC0' => $lead_min, //  добавляем значение Первоначальный Лид
                'UF_CRM_1574949535' => $ContactALL_Leads, //  добавляем значение Связанные Лиды             
            ),
        );
        // Раскомментировать:                          
        $result8 = sendtoBTX('crm.contact.add', $params_new_contact); // Создаем контакт  
        // Раскомментировать:                         
        $arContact8 = json_decode($result8,true); // получаем значение для вывода        

        // Раскомментировать:               
//        echo 'Выводим ID контакта в CRM:<br>'.$arContact8[result].'<br>';

        // Раскомментировать:               
        $ID_Contact_CRM = $arContact8[result];

        $status_of_create = 'v.04 - Создаем новый контакт';     

//        echo '<br>---------------------------------------------------------------------------------<br>';
//echo '</pre>';  
    }                   
/* ======================================= КОНЕЦ БЛОКА =======================================*/






/* ===========================================================================================*/
/* ======================== СОЗДАЁМ ФАЙЛ С ДАННЫМИ ДЛЯ ЗАГРУЗКИ В 1С =========================*/
/* ===========================================================================================*/

    // $data_for_1C[$row][] = array_fill(0, 18, ''); // Создаём массив (в дальнейшем - строка .csv-файла с данными) 

    // $data_for_1C[$row][0] = $_1C_name; // Нулевой элемент - Имя в файле Excel

    // $data_for_1C[$row][1] = $id_1c; // Первый элемент - ID1С в файле Excel
    // $data_for_1C[$row][2] = $ID_Contact_CRM; // Второй элемент - ID Контакта в CRM

    // if ($day_add_lead_t) { // Третий элемент - Дата создания Лида в CRM, если он существует. Для пустого значения было преобразование: $day_add_lead_t = '01.01.1970'
    //     $data_for_1C[$row][3] = $day_add_lead;
    //     }
    // else {
    //     $data_for_1C[$row][3] = $day_add_contact; 
    // };   
    // // if ($day_add_lead_t != '01.01.1970') {$data_for_1C[2] = $day_add_lead;}; 
   
    // array_splice( $data_for_1C[$row], 4, count($array_phones_unique), $array_phones_unique ); // С четвертого по двенадцатый элемент добавляются телефоны
    // array_splice( $data_for_1C[$row], 13, count($array_emails_unique), $array_emails_unique ); // С тринадцатого по семнадцатый элемент добавляются emailы

    // $data_for_1C[$row][18] = $status_of_create; // Статус обработки строки



    $data_for_1C[] = array_fill(0, 18, ''); // Создаём массив (в дальнейшем - строка .csv-файла с данными) 

    $data_for_1C[0] = $_1C_name; // Нулевой элемент - Имя в файле Excel

    $data_for_1C[1] = $id_1c; // Первый элемент - ID1С в файле Excel
    $data_for_1C[2] = $ID_Contact_CRM; // Второй элемент - ID Контакта в CRM

    if ($day_add_lead_t) { // Третий элемент - Дата создания Лида в CRM, если он существует. Для пустого значения было преобразование: $day_add_lead_t = '01.01.1970'
        $data_for_1C[3] = $day_add_lead;
        }
    else {
        $data_for_1C[3] = null; 
    };   

    // else {
    //     $data_for_1C[3] = $day_add_contact; 
    // };  

    // if ($day_add_lead_t != '01.01.1970') {$data_for_1C[2] = $day_add_lead;}; 
   
    //array_splice( $data_for_1C, 4, count($array_phones_unique), $array_phones_unique ); // С четвертого по двенадцатый элемент добавляются телефоны
    // array_splice( $data_for_1C, 13, count($array_emails_unique), $array_emails_unique ); // С тринадцатого по семнадцатый элемент добавляются emailы

    // С четвертого по двенадцатый элемент добавляются телефоны
    $data_for_1C[4] = $array_phones_unique[0]; 
    $data_for_1C[5] = $array_phones_unique[1]; 
    $data_for_1C[6] = $array_phones_unique[2]; 
    $data_for_1C[7] = $array_phones_unique[3]; 
    $data_for_1C[8] = $array_phones_unique[4]; 
    $data_for_1C[9] = $array_phones_unique[5]; 
    $data_for_1C[10] = $array_phones_unique[6]; 
    $data_for_1C[11] = $array_phones_unique[7]; 
    $data_for_1C[12] = $array_phones_unique[8]; 
    
    // С тринадцатого по шестнадцатый элемент добавляются emailы
    $data_for_1C[13] = $array_emails_unique[0];     
    $data_for_1C[14] = $array_emails_unique[1];     
    $data_for_1C[15] = $array_emails_unique[2];     
    $data_for_1C[16] = $array_emails_unique[3]; 

    $data_for_1C[17] = $status_of_create; // Статус обработки строки




//    echo '<pre>Выводим массив данных для передачи данных в .csv-файл:<br>'; 
//    print_r($data_for_1C);
//    echo '---------------------------------------------------------------------------------<br></pre>'; 

    /* Вывод файла для 1С через функцию fputcsv:

    $data_for_1C_file = array ( $data_for_1C ); // Сводные данные для .csv-файла


    $file_record = fopen('file_for_1c.csv', 'a'); // Открываем файл для добавления данных (параметр 'a')

    foreach ($data_for_1C_file as $fields) {
        fputcsv($file_record, $fields, ';'); //Записываем данные в файл
    }
    fclose($file_record); // Закрываем файл 
    */

    /* Вывод файла для 1С через функцию file_put_contents: */
    file_put_contents('/var/www/martines.ru/web/bitrix24-api/main-upload/1C_file_all.txt', PHP_EOL.implode('; ', $data_for_1C), FILE_APPEND);
    // file_put_contents('/var/www/martines.ru/web/bitrix24-api/main-upload/1C_file_'.date('Y-m-d').'.txt', PHP_EOL.implode('; ', $data_for_1C), FILE_APPEND);
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ================================== СОЗДАЁМ LOG - ФАЙЛ: ====================================*/
/* ===========================================================================================*/
        file_put_contents('/var/www/martines.ru/web/bitrix24-api/logs/upload_all_'.date('Y-m-d').'.txt', PHP_EOL.date('[Y-m-d H:i:s] ').implode(', ', $data), FILE_APPEND);
    }
/* ======================================= КОНЕЦ БЛОКА =======================================*/


/* ===========================================================================================*/
/* ================================ ВЫВОД ДЛЯ СКРИПТА (JSON) =================================*/
/* ===========================================================================================*/
       
/*    echo '---------------------------------------------------------------------------------<br><br><br>'; 
print_r($result);
    echo '---------------------------------------------------------------------------------<br><br><br>'; 
echo json_encode($result); 
    echo '---------------------------------------------------------------------------------<br><br><br>';*/ 
//echo json_encode($data_for_1C); 

/* ======================================= КОНЕЦ БЛОКА =======================================*/

$row++; // Переходим к следующей строке
}

// var_dump($data_for_1C);
// echo '<br><br>';

//$data_for_json = implode(";", $data_for_1C);

// print_r($data_for_json);

echo json_encode($data_for_1C); 

// print_r($data_for_1C); 

    fclose($handle); // Закрываем файл 

    // if($row > ($params['string']+$params['quantity'])) {
    //     $result['next'] = $params['all'];
    // } else {
    //     $result['next'] = 'off';
    // }

    // if($row > ($params['string']+$params['quantity'])) {
    //     $result['next'] = 'off';
    // } else {
    //     $result['next'] = 'on';
    // }
    
}

//print_r($data_for_1C);



?>

