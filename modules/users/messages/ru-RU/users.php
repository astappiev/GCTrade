<?php
return [
    /* DefaultController */
    'DEFAULT_CONTROLLER_RULES_NOT_PERMISSIONS' => 'У вас нет доступа к данной странице.',
    'DEFAULT_CONTROLLER_USER_UPDATED' => 'Найстройки профиля изменены.',
    'DEFAULT_CONTROLLER_USER_NOT_UPDATE' => 'Возникла ошибка при изменении пользователя.',
    'DEFAULT_CONTROLLER_REQUEST_PASSWORD_SUCCESS' => 'Проверьте свою электронную почту для получения дальнейших инструкций по восстановлению пароля.',
    'DEFAULT_CONTROLLER_REQUEST_PASSWORD_ERROR' => 'Прошу прощение, но я не могу сбросить пароль для этого email.',
    'DEFAULT_CONTROLLER_RESET_PASSWORD_SUCCESS' => 'Благодарю, новый пароль сохранен.',
    'DEFAULT_CONTROLLER_SET_PASSWORD_SUCCESS' => 'Пароль изменен.',
    'DEFAULT_CONTROLLER_SET_PASSWORD_ERROR' => 'Возникла ошибка при изменении пароля.',

    'DEFAULT_CONTROLLER_AUTH_SUCCESS' => 'Вы успешно авторизовались, рад снова вас видеть!',
    'DEFAULT_CONTROLLER_AUTH_FAILED' => 'Что то пошло не так, к сожалению я не могу вас авторизовать.',
    'DEFAULT_CONTROLLER_AUTH_REGISTER_SUCCESS' => 'Хорошие новости! Во время регистрации проблем не обнаружено, добро пожаловать!',
    'DEFAULT_CONTROLLER_AUTH_REGISTER_FAILED' => 'Сожалею, но во время регистрации произошла ошибка.',
    'DEFAULT_CONTROLLER_AUTH_EXIST_USERNAME' => 'Вы успешно подтвердили свой аккаунт. Теперь можете авторизовываться с помощью GreenCubes!',
    'DEFAULT_CONTROLLER_AUTH_EXIST_USERNAME_PASSWORD_RESET' => 'Обратите внимание! Ваш пароль был сброшен, если хотите можете его установить, <a href="{url}">здесь</a>.',
    'DEFAULT_CONTROLLER_AUTH_EXIST_EMAIL' => 'Стало быть ты уже зарегистрирован под ником {login}, я взял смелось предположить что это твой аккаунт и позвоил себе переименовать его.',

    /* Default views */
    'MESSAGE_VIEWS_INDEX_TITLE' => 'Твой профиль',
    'MESSAGE_VIEWS_INDEX_USERNAME' => 'Ваш логин:',
    'MESSAGE_VIEWS_INDEX_EMAIL' => 'Ваш email:',
    'MESSAGE_VIEWS_INDEX_CREATED' => 'Дата регистрации:',
    'MESSAGE_VIEWS_INDEX_SHOP_COUNT' => 'Магазинов создано:',

    'MESSAGE_VIEWS_EDIT_TITLE' => 'Настройки профиля',

    /* MessageController */
    'MESSAGE_CONTROLLER_NO_PERMISSION' => 'Вы не можете просматривать данное сообщение.',
    'MESSAGE_CONTROLLER_NOT_FOUND' => 'Искомое сообщение не существует.',

    /* Message views */
    'MESSAGES' => 'Сообщения',
    'CREATE_MESSAGES' => 'Создать сообщение',


    /* Settings model */
    'SETTING_USER_ID' => 'Номер пользователя',
    'SETTING_MAIL_DELIVERY' => 'Получать уведомления от сайта',
    'SETTING_MAIL_LEAVE' => 'Получать уведомления, когда отслеживаемый пользователь перестает играть',

    /* User model */
    'USER_ID' => 'Номер пользователя',
    'USER_ROLE' => 'Роль пользователя',
    'USER_EMAIL' => 'Email',
    'USER_NEW_EMAIL' => 'Новый email',
    'USER_USERNAME' => 'Имя пользователя',
    'USER_PASSWORD_HASH' => 'Зашифрованный пароль',
    'USER_PASSWORD_RESET_TOKEN' => 'Ключ сброса пароля',
    'USER_ACCESS_TOKEN' => 'Ключ доступа',
    'USER_AUTH_KEY' => 'Ключ аутентификации',
    'USER_CREATED_AT' => 'Создан',
    'USER_UPDATED_AT' => 'Последнее обновление',

    'USER_ROLE_USER' => 'Участник',
    'USER_ROLE_AUTHOR' => 'Пользователь',
    'USER_ROLE_MODER' => 'Модератор',
    'USER_ROLE_ADMIN' => 'Администратор',

    /* Message model */
    'MESSAGE_ID' => 'Номер сообщения',
    'MESSAGE_STATUS' => 'Статус сообщения',
    'MESSAGE_SENDER' => 'Отправитель',
    'MESSAGE_RECIPIENT' => 'Получатель',
    'MESSAGE_TITLE' => 'Заглавие',
    'MESSAGE_TEXT' => 'Текст',
    'MESSAGE_CREATED_AT' => 'Создано',
    'MESSAGE_UPDATED_AT' => 'Последнее обновление',
    'MESSAGE_STATUS_OBTAINED' => 'Получено',
    'MESSAGE_STATUS_OBTAINED_NOTIFIED' => 'Получено и уведомлено',
    'MESSAGE_STATUS_READS' => 'Прочитано',
    'MESSAGE_STATUS_REMOVED' => 'Удалено',

    /* LoginForm model */
    'LOGIN_FORM_USERNAME' => 'Имя пользователя',
    'LOGIN_FORM_PASSWORD' => 'Пароль',
    'LOGIN_FORM_REMEMBER_ME' => 'Запомнить меня',
    'LOGIN_FORM_VALIDATE_USER_OR_PASSWORD_ERROR' => 'Неверное имя пользователя или пароль.',

    /* PasswordResetRequestForm model */
    'PASSWORD_RESET_REQUEST_FORM_EMAIL' => 'Email',
    'PASSWORD_RESET_REQUEST_FORM_RULES_EMAIL_EXIST_MESSAGE' => 'Пользователя с таким email не существует.',

    /* PasswordUserForm model */
    'PASSWORD_USER_FORM_PASSWORD' => 'Пароль',
    'PASSWORD_USER_FORM_PROTECT_PASSWORD' => 'Повтор пароля',
    'PASSWORD_USER_FORM_OLD_PASSWORD' => 'Старый пароль',
    'PASSWORD_USER_FORM_INVALID_OLD_PASSWORD' => 'Неверен старый пароль пользователя.',

    /* ResetPasswordForm model */
    'RESET_PASSWORD_FORM_PASSWORD' => 'Пароль',
    'RESET_PASSWORD_FORM_EMPTY_TOKEN' => 'Ключ восстановления пароля, не существует.',
    'RESET_PASSWORD_FORM_INVALID_TOKEN' => 'Неверный ключ восстановления.',

    /* SignupForm model */
    'SIGNUP_FORM_USERNAME' => 'Имя пользователя',
    'SIGNUP_FORM_EMAIL' => 'Email',
    'SIGNUP_FORM_PASSWORD' => 'Пароль',
    'SIGNUP_FORM_RULES_USERNAME_UNIQUE_MESSAGE' => 'Данный логин уже используется.',
    'SIGNUP_FORM_RULES_USERNAME_UNIQUE_EMAIL' => 'Данный email уже существует.',
];
