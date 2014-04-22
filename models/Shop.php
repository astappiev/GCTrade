<?php
namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\validators\ImageValidator;

class Shop extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_VERIFIED = 6;
    const STATUS_DEPENDS = 8;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return 'tg_shop';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function scenarios()
    {
        return [
            'add' => ['name', 'alias', 'about', 'description', 'subway', 'x_cord', 'z_cord', 'source'],
            'edit' => ['name', 'about', 'description', 'subway', 'x_cord', 'z_cord', 'source'],
            'update_date' => ['updated_at'],
            'logo' => ['logo'],
        ];
    }

    public static function findId($id)
    {
        return static::findOne($id);
    }

    public static function findByAlias($alias)
    {
        return static::find()->where(['alias' => $alias])->one();
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['id_shop' => 'id'])->orderBy('id_item');
    }

    public static function getStatusArray()
    {
        return [
            self::STATUS_DELETED => 'Не опубликован',
            self::STATUS_VERIFIED => 'На модерации',
            self::STATUS_DEPENDS => 'Зависем (Опубликован)',
            self::STATUS_ACTIVE => 'Опубликован',
        ];
    }

    public function getStatus()
    {
        $status = self::getStatusArray();
        return $status[$this->status];
    }

    public function getUpdateTime()
    {
        return \Yii::$app->formatter->asDate($this->updated_at, 'd.m.Y');
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner' => 'Владелец',
            'status' => 'Статус',
            'alias' => 'Алиас',
            'name' => 'Название',
            'about' => 'Вступительный текст',
            'description' => 'Описание',
            'subway' => 'Станция метро',
            'x_cord' => 'Координата X',
            'z_cord' => 'Координата Z',
            'logo' => 'Логотип магазина',
            'source' => 'Источник',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления'
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            // Проверяем если это новая запись.
            if ($this->isNewRecord) {
                // Определяем автора в случае его отсутсвия.
                if (!$this->owner) {
                    $this->owner = \Yii::$app->user->identity->id;
                }
                // Определяем статус.
                if (!$this->status) {
                    $this->status = self::STATUS_ACTIVE;
                }
            }
            return true;
        }
        return false;
    }


    public function rules()
    {
        return [
            ['owner', 'default', 'value' => Yii::$app->user->identity->id],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            [['alias', 'name', 'about'], 'required'],

            ['alias', 'filter', 'filter' => 'trim'],
            ['alias', 'string', 'min' => 3, 'max' => 30],
            ['alias', 'unique', 'targetClass' => '\app\models\Shop', 'message' => 'Данный алиас уже используется.'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 3, 'max' => 90],
            ['name', 'unique', 'targetClass' => '\app\models\Shop', 'message' => 'Данное имя уже используется.'],

            [['description', 'subway', 'logo', 'source'], 'string'],
            [['x_cord', 'z_cord'], 'integer', 'max' => 30000],

            ['logo', 'file', 'types'=> ['jpg', 'png', 'gif', 'jpeg']],
            ['logo', 'image', 'maxWidth' => 150, 'minWidth' => 150, 'maxHeight' => 150, 'minHeight' => 150],
        ];
    }
}