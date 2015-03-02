<?php

use yii\db\Schema;
use yii\db\Migration;

class m150301_212241_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL UNIQUE',
            'new_email' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'username' => Schema::TYPE_STRING . ' DEFAULT NULL UNIQUE',
            'password_hash' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'password_reset_token' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'access_token' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%user_setting}}', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'mail_delivery' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            'mail_see_leave' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->addForeignKey('FK_user_setting_user', '{{%user_setting}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%user_message}}', [
            'id' => Schema::TYPE_PK,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'user_sender' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'user_recipient' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_user_message_user_sender', '{{%user_message}}', 'user_sender', '{{%user}}', 'id');
        $this->addForeignKey('FK_user_message_user_recipient', '{{%user_message}}', 'user_recipient', '{{%user}}', 'id');

        $this->createTable('{{%see}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'login' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'is_send' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_see_user', '{{%see}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%item}}', [
            'id' => Schema::TYPE_PK,
            'id_primary' => Schema::TYPE_INTEGER . ' NOT NULL',
            'id_meta' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'alias' => Schema::TYPE_STRING . '(10) DEFAULT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%item_alias}}', [
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'subname' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_item_alias_item', '{{%item_alias}}', 'item_id', '{{%item}}', 'id');

        $this->createTable('{{%shop}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'type' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'alias' => Schema::TYPE_STRING . '(60) NOT NULL',
            'name' => Schema::TYPE_STRING . '(100) NOT NULL',
            'about' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'subway' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'x_cord' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'z_cord' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'image_url' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'source' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_shop_user', '{{%shop}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%shop_book}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'author' => Schema::TYPE_STRING . '(128) NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'price_sell' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_shop_book_shop', '{{%shop_book}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_shop_book_item', '{{%shop_book}}', 'item_id', '{{%item}}', 'id');

        $this->createTable('{{%shop_good}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'price_sell' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'price_buy' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'stuck' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_shop_good_shop', '{{%shop_good}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_shop_good_item', '{{%shop_good}}', 'item_id', '{{%item}}', 'id');

        $this->createTable('{{%auction_lot}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type_id' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'metadata' => Schema::TYPE_TEXT,
            'description' => Schema::TYPE_TEXT,
            'price_min' => Schema::TYPE_INTEGER. ' NOT NULL DEFAULT 1',
            'price_step' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'price_blitz' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
            'time_bid' => Schema::TYPE_INTEGER. ' NOT NULL DEFAULT 172800',
            'time_elapsed' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_auction_lot_user', '{{%auction_lot}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%auction_bid}}', [
            'id' => Schema::TYPE_PK,
            'lot_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'cost' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_auction_bid_user', '{{%auction_bid}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('FK_auction_bid_auction_lot', '{{%auction_bid}}', 'lot_id', '{{%auction_lot}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('FK_auction_bid_user', '{{%auction_bid}}');
        $this->dropForeignKey('FK_auction_bid_auction_lot', '{{%auction_bid}}');
        $this->dropTable('{{%auction_bid}}');
        $this->dropForeignKey('FK_auction_lot_user', '{{%auction_lot}}');
        $this->dropTable('{{%auction_lot}}');
        $this->dropForeignKey('FK_shop_good_shop', '{{%shop_good}}');
        $this->dropForeignKey('FK_shop_good_item', '{{%shop_good}}');
        $this->dropTable('{{%shop_good}}');
        $this->dropForeignKey('FK_shop_book_shop', '{{%shop_book}}');
        $this->dropForeignKey('FK_shop_book_item', '{{%shop_book}}');
        $this->dropTable('{{%shop_book}}');
        $this->dropForeignKey('FK_see_user', '{{%see}}');
        $this->dropTable('{{%see}}');
        $this->dropForeignKey('FK_user_message_user_sender', '{{%user_message}}');
        $this->dropForeignKey('FK_user_message_user_recipient', '{{%user_message}}');
        $this->dropTable('{{%user_message}}');
        $this->dropForeignKey('FK_user_setting_user', '{{%user_setting}}');
        $this->dropTable('{{%user_setting}}');
        $this->dropForeignKey('FK_shop_user', '{{%shop}}');
        $this->dropTable('{{%shop}}');
        $this->dropForeignKey('FK_item_alias_item', '{{%item_alias}}');
        $this->dropTable('{{%item_alias}}');
        $this->dropTable('{{%item}}');
        $this->dropTable('{{%user}}');
    }
}
