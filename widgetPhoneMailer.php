<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$ret = Array();

//если не существует ф-и json_encode
if (!function_exists('json_encode')) {
    require_once(dirname(__FILE__).'/JSON/JSON.php');
    $json = new Services_JSON();
    function json_encode($arr){
        global $json;
        return $json->encode($arr);
    }
}

//url страницы, на которой находится польз-ль
$url_page = !empty($_REQUEST['url_page']) ? $_REQUEST['url_page'] : 'не определен' ;

if( !empty( $_REQUEST['widgetPhone'] ) ){
	$widgetPhone = $_REQUEST['widgetPhone'];



	if( /* Прошла валидацию */
		!empty( $widgetPhone['phone'] ) &&
		preg_match( '/^[0-9\-\(\)\+]{5,30}$/ui' , $widgetPhone['phone'] )
	) {
		require_once dirname(__FILE__).'/PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
	    $mail->From = 'mail@alam.ru'; // адрес, с которого вы хотите послать письмо. (должнен существовать такой email)

        // Вариант с использованием SMTP
        if (!function_exists('mail')) { // если не существует ф-и mail, то осуществляем отправку через smtp
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->From = 'alam.stroifirma@yandex.ru'; // адрес, с которого вы хотите послать письмо.
            $mail->Host = "smtp.yandex.ru"; // SMTP server
            //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
            $mail->SMTPAuth = true;                  // enable SMTP authentication
            $mail->Port = 465;                    // set the SMTP port
            $mail->SMTPSecure = "ssl";
            $mail->Username = "alam.stroifirma"; // SMTP account username
            $mail->Password = "666";        // SMTP account password
        }

	    $mail->FromName = 'Строительная компания "Алам"'; // имя отправителя
		// $mail->AddAddress('mail@alam.ru', 'Алам'); // кому - адрес, Имя
		$mail->AddAddress('lifehacker666@gmail.com', 'Имя_отправителя'); // кому - адрес, Имя
		$mail->IsHTML(true);        // выставляем формат письма HTML
		$mail->Subject = 'Заявка с сайта Alam.ru. (Просьба срочно перезвонить!)';  // тема письма

		$mail->Body = "
			<h2>Заявка с сайта alam.ru (Просьба срочно перезвонить!)</h2>
			<table border='1' cellspacing='0' cellpadding='10'>
				<tr>
					<th>Номер телефона:</th>
					<td>".$widgetPhone['phone']."</td>
				</tr>
				<tr>
					<th>URL страницы:</th>
					<td>".$url_page."</td>
				</tr>
            </table>
		";

		$mail->Send();

		$ret['good'] = 'Выша заявка отправлена.';

		$ret['.window.message .validation-informer span'] = '';
        $ret['script'] = "$('.window.message .item-form-element.phone').addClass('success');"; //добавим класс success
        $ret['script'] .= "Message('Спасибо!<br>Ваша заявка отправлена<br><a class=\"button close-btn\" href=\"#\" onclick=\"$(\'.window\').removeClass(\'show\');return false;\">Ok</a>');"; //выведем сообщение
        $ret['script'] .= " $('.window.message .close, .window .close-btn').bind( 'click', function() { $('.widget-phone').stop().fadeIn(450);  });"; //заново запустим виджет

	} else {

		/* Ошибки - валидация формы */
        if( empty( $widgetPhone['phone'] ) ||  ( !empty( $widgetPhone['phone'] ) && !preg_match( '/^[0-9\-\(\)\+]{5,30}$/ui' , $widgetPhone['phone'] ) ) ) {

            $ret['.window.message .validation-informer span'] = 'Некорректно указан телефон.<br> Укажите, пожалуйста, в формате:<br> +7 (123) 456 78 90';
            $ret['script'] = "$('.window.message .item-form-element.phone').addClass('error');"; //добавим класс error
            $ret['script'] .= "$('.window.message .validation-informer').show().click(function(){ $(this).hide(); });"; // покажем сообщение об ошибке и скрываем его по клику на сбщ
            $ret['script'] .= "$('.window.message .item-form-element.phone').click(function(){ $('.window.message .validation-informer').hide(); $('.window.message .item-form-element.phone').removeClass('error'); });"; //по клику на инпут скрываем сбщ об ошибке и удаляем класс error

        } else {

        	// другие ошибки

        }

	}
} else {

	// если данные не отправлены
	$ret['.window.message .validation-informer span'] = 'Ошибка отправки формы. Попробуйте еще раз позже';
	$ret['script'] = "$('.window.message .item-form-element.phone').addClass('error');"; //добавим класс error
	$ret['script'] .= "$('.window.message .validation-informer').show().click(function(){ $(this).hide(); });"; // покажем сообщение об ошибке и скрываем его по клику на сбщ
	$ret['script'] .= "$('.window.message .item-form-element.phone').click(function(){ $('.window.message .validation-informer').hide(); $('.window.message .item-form-element.phone').removeClass('error'); });"; //по клику на инпут скрываем сбщ об ошибке и удаляем класс error

}

echo json_encode($ret);