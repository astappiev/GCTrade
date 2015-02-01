<?php
namespace app\modules\shop\models;

use app\modules\users\models\User;
use karpoff\icrop\CropImageUploadBehavior;
use yii;
use yii\db\ActiveRecord;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Class Shop
 * @package  app\modules\shop\models
 * Model Shop.
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $type
 * @property string $alias
 * @property string $name
 * @property string $about
 * @property string $description
 * @property string $subway
 * @property integer $x_cord
 * @property integer $z_cord
 * @property string $logo_url
 * @property string $source
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \app\modules\users\models\User $user
 * @property string $logo
 */
class Shop extends ActiveRecord
{
    const STATUS_DRAFT = 2;
    const STATUS_PUBLISHED = 5;
    const STATUS_BLOCKED = 10;

    const TYPE_GOODS = 0;
    const TYPE_BOOKS = 1;

    protected $_logo;
    public $photo_crop;
    public $photo_cropped;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
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
                    'logo_url' => [
                        'path' => 'images/shop/',
                        'tempPath' => 'images/shop/tmp/',
                        'url' => '/images/shop'
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
        return [
            'create' => ['name', 'user_id', 'status', 'type', 'alias', 'about', 'description', 'subway', 'x_cord', 'z_cord', 'logo_url', 'source'],
            'update' => ['name', 'status', 'alias', 'about', 'description', 'subway', 'x_cord', 'z_cord', 'logo_url', 'source'],
            'update_date' => ['updated_at'],
            'delete-logo' => ['logo_url'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findId($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds the Shop model based on its alias value.
     * @param string $alias
     * @return Shop the loaded model
     */
    public static function findByAlias($alias)
    {
        return static::find()->where(['alias' => $alias])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        if($this->type === self::TYPE_GOODS) {
            return $this->hasMany(Good::className(), ['shop_id' => 'id'])->orderBy('item_id');
        } elseif ($this->type === self::TYPE_BOOKS) {
            return $this->hasMany(Book::className(), ['shop_id' => 'id']);
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        if ($this->_logo === null) {
            $this->_logo = $this->logo_url ? ('/images/shop/'.$this->logo_url) : '/images/nologo.png';
        }
        return $this->_logo;
    }

    /**
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return array
     */
    public static function getTypeArray()
    {
        return [
            self::TYPE_GOODS => 'Обычный',
            self::TYPE_BOOKS => 'Книжный',
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $status = self::getStatusArray();
        return $status[$this->status];
    }

    /**
     * @return string
     */
    public function getType()
    {
        $status = self::getTypeArray();
        return $status[$this->type];
    }

    /**
     * @return string
     */
    public function getUpdateTime()
    {
        return \Yii::$app->formatter->asDate($this->updated_at, 'd.m.Y');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'Пользователь',
            'status' => 'Статус',
            'type' => 'Тип магазина',
            'alias' => 'Алиас',
            'name' => 'Название',
            'about' => 'Вступительный текст',
            'description' => 'Описание',
            'subway' => 'Станция метро',
            'x_cord' => 'Координата X',
            'z_cord' => 'Координата Z',
            'logo_url' => 'Логотип магазина',
            'source' => 'Ссылка на форум или сайт',
            'created_at' => 'Создано',
            'updated_at' => 'Последнее обновление',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'default', 'value' => Yii::$app->user->id],
            ['status', 'default', 'value' => self::STATUS_PUBLISHED],
            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            ['type', 'default', 'value' => self::TYPE_GOODS],
            ['type', 'in', 'range' => array_keys(self::getTypeArray())],

            [['alias', 'name', 'about'], 'required'],

            ['alias', 'filter', 'filter' => 'trim'],
            ['alias', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9]/'],
            ['alias', 'string', 'min' => 3, 'max' => 30],
            ['alias', 'unique', 'targetClass' => '\app\modules\shop\models\Shop', 'message' => 'Данный алиас уже
            используется.'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 3, 'max' => 90],
            ['name', 'unique', 'targetClass' => '\app\modules\shop\models\Shop', 'message' => 'Данное имя уже используется.'],

            [['description', 'subway', 'logo_url', 'source'], 'string'],
            [['x_cord', 'z_cord'], 'integer', 'max' => 30000],
        ];
    }
}