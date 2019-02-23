<?php

add_action('wp_ajax_sendContactForm', 'processContactEmail');
add_action('wp_ajax_nopriv_sendContactForm', 'processContactEmail');

function processContactEmail()
{
    $fieldList = ['name', 'email', 'company', 'phone', 'message'];
    try {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('Кажется запрос был с ошибкой...');
        }

        $message = 'Сообщение с сайта: ' . get_option('blogname');

        foreach ($fieldList as $field) {
            if (empty($_POST['contact'][$field])) {
                throw new Exception("Поле «" . $field . "» должно быть заполнено!");
            }
            $message .= "\r\n" . $field . ': ' . filter_var($_POST['contact'][$field], FILTER_SANITIZE_STRING);
        }

//        die($message);

        wp_mail(
            get_option('admin_email'),
            'Заполненная форма контактов с сайта ' . get_option('blogname') . ', клиент — ' . $_POST['contact']['name'],
            $message
        );

        die(json_encode([
            'status'  => true,
            'message' => 'Ура, все отправлено!',
        ]));
    } catch (Exception $exception) {
        die(json_encode([
            'status'  => false,
            'message' => 'Ошибка: ' . $exception->getMessage()
        ]));
    }
}