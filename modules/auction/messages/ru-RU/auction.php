<?php
return [
    /* DefaultController */
    'DEFAULT_CONTROLLER_LOT_NOT_FOUND' => 'Лот не существует',
    'DEFAULT_CONTROLLER_LOT_NO_PERMISSION' => 'У вас нет доступпа к данному лоту.',

    /* BidController */
    'BID_CONTROLLER_LOT_NOT_FOUND' => 'Лот не существует',
    'BID_CONTROLLER_SUM_IS_INCORRECT' => 'Сумма указана неверно',
    'BID_CONTROLLER_NO_PERMISSION' => 'Не авторизованы или у вас нет прав',
    'BID_CONTROLLER_IMPOSSIBLE_TWO_BITS' => 'Вы не можете сделать 2 ставки подряд',
    'BID_CONTROLLER_LOWER_THAN_EXISTING' => 'Вы не можете сделать ставку ниже уже существующей',
    'BID_CONTROLLER_LOWER_THAN_MINIMAL' => 'Ваша ставка должна быть больше начальной стоимости',
    'BID_CONTROLLER_LOWER_THAN_STEP' => 'Ваша ставка меньше шага аукциона',
    'BID_CONTROLLER_BID_SUCCESSFUL' => 'Ставка успешна',
    'BID_CONTROLLER_ERROR_SAVE' => 'Возникла ошибка при сохранении',
    'BID_CONTROLLER_UNKNOWN_ERROR' => 'Неизвестная ошибка',

    /* Lot model */
    'LOT_ID' => 'Номер лота',
    'LOT_USER_ID' => 'Пользователь',
    'LOT_NAME' => 'Название лота',
    'LOT_METADATA' => 'Данные о лоте',
    'LOT_TYPE_ID' => 'Тип лота',
    'LOT_STATUS' => 'Состояние',
    'LOT_DESCRIPTION' => 'Описание',
    'LOT_PRICE_MIN' => 'Начальная цена',
    'LOT_PRICE_STEP' => 'Шаг аукциона',
    'LOT_PRICE_BLITZ' => 'Блиц цена',
    'LOT_TIME_BID' => 'Время ставки',
    'LOT_TIME_ELAPSED' => 'Время аукциона',
    'LOT_CREATED_AT' => 'Создан',
    'LOT_UPDATED_AT' => 'Последнее обновление',
    'LOT_PICTURE_URL' => 'Изображение',
    'LOT_ITEM_ID' => 'Предмет',
    'LOT_REGION_NAME' => 'Регион',

    'LOT_TYPE_ITEM' => 'Предмет',
    'LOT_TYPE_LAND' => 'Территория',
    'LOT_TYPE_PROJECT' => 'Проект',
    'LOT_TYPE_OTHER' => 'Прочее',

    'LOT_STATUS_PUBLISHED' => 'Опубликовано',
    'LOT_STATUS_DRAFT' => 'Черновик',
    'LOT_STATUS_STARTED' => 'Начат',
    'LOT_STATUS_FINISHED' => 'Окончен',
    'LOT_STATUS_CLOSED' => 'Закрыт',
    'LOT_STATUS_BLOCKED' => 'Заблокирован',

    /* Bid model */
    'BID_ID' => 'Номер ставки',
    'BID_LOT' => 'Лот',
    'BID_USER' => 'Пользователь',
    'BID_COST' => 'Сумма сделки',
    'BID_CREATED_AT' => 'Создано',
    'BID_UPDATED_AT' => 'Последнее обновление',
];
