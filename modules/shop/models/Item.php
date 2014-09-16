<?php

namespace app\modules\shop\models;

use yii\db\ActiveRecord;

/**
 * Class Item
 * @package app\modules\shop\models
 * Model Item.
 *
 * @property integer $id
 * @property integer $id_primary
 * @property integer $id_meta
 * @property string $name
 */
class Item extends ActiveRecord
{
    protected $_alias;
    protected $_image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tg_item';
    }

    /**
     * Get Price model corresponding to the current model.
     * @return Price model
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['id_item' => 'id']);
    }

    /**
     * Get Alias param from model
     * @return string alias
     */
    public function getAlias()
    {
        if ($this->_alias === null) {
            $this->_alias = ($this->id_meta != 0) ? ($this->id_primary . '.' . $this->id_meta) : $this->id_primary;
        }
        return $this->_alias;
    }

    /**
     * Get image from model
     * @return string alias
     */
    public function getImage()
    {
        if ($this->_image === null) {
            $this->_image = '/images/items/'.$this->getAlias().'.png';
        }
        return $this->_image;
    }

    /**
     * Finds the Item model based on its alias value.
     * @param string $alias
     * @return Item model
     */
    public static function findByAlias($alias)
    {
        $id = explode(".", $alias);
        return static::find()->where(['id_primary' => $id[0], 'id_meta' => isset($id[1]) ? $id[1] : 0])->one();
    }

    /**
     * Finds the Item model based on its name value.
     * @param string $name
     * @return Item model
     */
    public static function findByName($name)
    {
        $item = static::findOne(['name' => $name]);
        if(!$item)
            return ItemUsernames::findByName($name)->getItem();

        return $item;
    }
}