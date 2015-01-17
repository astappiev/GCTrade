<?php

namespace app\modules\auction\models;

use app\modules\users\models\User;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Lot
 * @package app\modules\auction\models
 * Model Lot.
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $status
 * @property string $name
 * @property string $metadata
 * @property string $description
 * @property integer $price_min
 * @property integer $price_step
 * @property integer $price_blitz
 * @property integer $time_bid
 * @property integer $time_elapsed
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Bid $bids
 * @property Bid $bid
 * @property \app\modules\users\models\User $user
 */
class Lot extends ActiveRecord
{
    public $region_name;
    public $picture_url;
    public $item_id;

    const TYPE_ITEM_IMAGE = 8;
    const TYPE_ITEM = 1;
    const TYPE_LAND = 2;
    const TYPE_PROJECT = 3;
    const TYPE_OTHER = 9;

    const STATUS_DRAFT = 2;
    const STATUS_PUBLISHED = 5;
    const STATUS_STARTED = 6;
    const STATUS_FINISHED = 7;
    const STATUS_CLOSED = 9;
    const STATUS_BANNED = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_lot}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className(),
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'picture_url' => [
                        'path' => 'images/auction/',
                        'tempPath' => 'images/auction/tmp/',
                        'url' => '/images/auction'
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['user_id', 'type_id', 'status', 'name', 'metadata', 'description', 'price_min', 'price_step', 'price_blitz', 'created_at', 'updated_at'];
        $scenarios['update'] = ['status', 'name', 'metadata', 'description', 'price_min', 'price_step', 'price_blitz', 'updated_at'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price_min', 'metadata'], 'required'],

            [['name', 'metadata', 'description', 'region_name'], 'string'],
            [['time_bid', 'time_elapsed'], 'integer'],
            [['time_bid', 'created_at', 'updated_at'], 'safe'],

            [['name'], 'string', 'max' => 255],
            ['price_blitz', 'integer', 'max' => 1000000000],
            ['price_min', 'integer', 'max' => 10000000],
            ['price_step', 'integer', 'max' => 500000],
            ['price_min', 'default', 'value' => 1],
            ['status', 'default', 'value' => 2],

            ['type_id', 'in', 'range' => array_keys(self::getTypeArray())],

            ['user_id', 'default', 'value' => \Yii::$app->user->id],
            ['time_elapsed', 'default', 'value' => time() + 1209600], // 2 weeks
            ['time_bid', 'default', 'value' => 172800], // 2 days

            ['metadata', 'app\modules\auction\models\validators\MetadataValidator'],

            ['region_name', 'app\modules\auction\models\validators\LandValidator'],
            ['region_name', 'required', 'when' => function ($model) {
                return $model->type_id == self::TYPE_LAND;
            }, 'whenClient' => "function (attribute, value) {
                return $('#lot-type_id').val() == " . self::TYPE_LAND . ";
            }"],

            ['picture_url', 'app\modules\auction\models\validators\PictureValidator'],
            ['picture_url', 'required', 'when' => function ($model) {
                return ($model->type_id == self::TYPE_ITEM_IMAGE || $model->type_id == self::TYPE_PROJECT || $model->type_id == self::TYPE_OTHER);
            }, 'whenClient' => "function (attribute, value) {
                var type = $('#lot-type_id').val();
                return (type == " . self::TYPE_ITEM_IMAGE . " || type == " . self::TYPE_PROJECT . " || type == " . self::TYPE_OTHER . ");
            }"],

            ['item_id', 'app\modules\auction\models\validators\ItemValidator'],
            ['item_id', 'required', 'when' => function ($model) {
                return $model->type_id == self::TYPE_ITEM_IMAGE;
            }, 'whenClient' => "function (attribute, value) {
                return $('#lot-type_id').val() == " . self::TYPE_ITEM_IMAGE . ";
            }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'name' => 'Название лота',
            'metadata' => 'Данные о лоте',
            'type_id' => 'Тип лота',
            'status' => 'Состояние',
            'description' => 'Описание',
            'price_min' => 'Начальная цена',
            'price_step' => 'Шаг аукциона',
            'price_blitz' => 'Блиц цена',
            'created_at' => 'Создан',
            'updated_at' => 'Последнее обновление',

            'picture_url' => 'Изображение',
            'item_id' => 'id предмета',
            'region_name' => 'Название региона',
        ];
    }

    /**
     * @return array
     */
    public static function getTypeArray()
    {
        return [
            self::TYPE_LAND => 'Территория',
            self::TYPE_ITEM => 'Предмет',
            self::TYPE_ITEM_IMAGE => 'Предмет (Изображение)',
            self::TYPE_PROJECT => 'Проект',
            self::TYPE_OTHER => 'Прочее',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_PUBLISHED => 'Опубликовано',
            self::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        $type = self::getTypeArray();
        return $type[$this->type_id];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $type = self::getTypeArray();
        return $type[$this->type_id];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['lot_id' => 'id'])->orderBy(['cost' => SORT_DESC])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['lot_id' => 'id'])->orderBy(['cost' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function afterFind()
    {
        if($this->type_id == self::TYPE_ITEM_IMAGE) {
            $data = json_decode($this->metadata);
            $this->item_id = $data->item_id;
            $this->picture_url = $data->picture_url;
        } else if ($this->type_id == self::TYPE_LAND) {
            $data = json_decode($this->metadata);
            $this->region_name = $data->name;
        } else if ($this->type_id == self::TYPE_PROJECT || $this->type_id == self::TYPE_OTHER) {
            $data = json_decode($this->metadata);
            $this->picture_url = $data->picture_url;
        }
    }
}
