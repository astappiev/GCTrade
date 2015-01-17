<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\modules\users\models\Message;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('users', 'MESSAGES');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo GridView::widget([
        'tableOptions' => [
            'id' => 'msg-table',
            'class' => 'table',
        ],
        'layout' => '{summary}<div class="panel panel-default">{items}</div>{pager}',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    if($data->status == Message::STATUS_REMOVED)
                        return '<span class="label label-danger">'.$data->getStatus().'</span>';
                    if($data->status == Message::STATUS_READS)
                        return '<span class="label label-default">'.$data->getStatus().'</span>';
                    return '<span class="label label-primary">Новое</span>';
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'sender',
                'value' => function ($data) {
                    if(!$data->sender) return 'GCTrade';
                    return $data->sender->username;
                },
                'enableSorting' => false,
            ],
            [
                'attribute' => 'title',
                'enableSorting' => false, //
                'value' => function ($data) {
                    return Html::a(Html::encode($data->title), ['view', 'id' => $data->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'HH:mm:ss dd.mm.yyyy ']
            ],
        ],
    ]); ?>

</div>
