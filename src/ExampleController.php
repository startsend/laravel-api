<?php
  namespace App\Http\Controllers;

  use App\Http\Controllers\Controller;
  use startsend\api\api;

  class ExampleController extends Controller {

    public function index() {
      $token = '';  // Код токена вы можете получить здесь: https://app.startsend.ru/user-api/token
      $phone = '';  // Номер телефона для теста

      $sms = new api($token);

      $text = "Заглавная буква в начале текста";
      $comment = "Пример работы транслитерации строки \"$text\"";
      $translit = $sms->transliterate->getTransliteration($text);
      $this->_echo($comment, $translit);

      $string = "Длина этого короткого текста на русском  примерно 70 символов или около того";
      $res = $sms->countSmsParts->checkTextLength($string);
      $this->_echo("Определяем размер сообщения \"$string\"", "Частей = {$res['parts']}, длина = {$res['len']}");

      // Баланс:
      $res = $sms->getBalance();
      $this->_echo("Получаем баланс", $res->result[0]->balance." ".$res->currency);

      // Отправка простого сообщения:
      if (false) {
        $message = 'Привет от StartSend.ru!';
        $res = $sms->createSMSMessage($message);
        $message_id = $res->message_id;
        $res = $sms->sendSms($message_id, $phone);
        $result = ($res==false) ? "Во время отправки сообщения произошла ошибка" : "Сообщение успешно отправлено, его ID: {$res->sms_id}";
        $this->_echo("Отправка sms-сообщения '$message' на номер: $phone", $result);
      }

      // Отправка сообщения с паролем от альфа-имени:
      if (false) {
        /* Если у вас пока нет собственного Альфа-имени, то вы можете тестировать от системного Альфа-имени с id=0 */
        $alphaname_id = 0;
        $res = $sms->createPasswordObject('both', 5);
        $password_object_id = $res->result->password_object_id;
        $res2 = $sms->sendSmsMessageWithCode('Ваш пароль: %CODE%', $password_object_id, $phone, $alphaname_id);
        $result = ($res2==false) ? "Во время отправки сообщения произошла ошибка" : "Сообщение успешно отправлено, его ID: {$res2->sms_id}";
        $this->_echo("Отправка сообщения с паролем от альфа-имени с ID=$alphaname_id", $result);
      }

      // Получение списка своих сообщений:
      if (false) {
        $messages = $sms->getMessagesList();
        echo "<pre>";
        print_r($messages->result);
        echo "</pre>";
      }

      // Получение списка Альфа-имён:
      if (false) {
        $alpha_names = $sms->getAlphaNames();
        echo "<pre>";
        print_r($alpha_names);
        echo "</pre>";
      }

      // Получение ID Альфа-имени:
      if (false) {
        $name = '0';  // Ваше Альфа-имя
        $res = $sms->getAlphaNameId($name);
        $this->_echo("Получение ID Альфа-имени $name", $res->id);
      }
    }


    public function _echo($comment, $result="") {
      echo "<br />";
      echo "Действие: $comment <br />" ;
      if(!empty($result))
        echo "Результат: $result <br />";
    }
  }
?>