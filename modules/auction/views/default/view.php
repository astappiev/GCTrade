<?php

use app\modules\auction\models\Lot;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\auction\models\Lot $model
 */
$this->registerJsFile('@web/js/jquery/jquery.countdown.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/auction.gctrade.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Аукцион', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content auction lot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!\Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Изменить', ['cpanel/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['cpanel/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот лот?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="well clearfix">
                <div class="info pull-left">
                    <?php if($model->type_id === Lot::TYPE_ITEM): ?>

                        <?= $this->render('type/item', [
                            'model' => $model,
                        ]) ?>

                    <?php elseif($model->type_id === Lot::TYPE_LAND): ?>

                        <?= $this->render('type/region', [
                            'model' => $model,
                        ]) ?>

                    <?php else: ?>

                        <p>Тест</p>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <ul class="list-group">
                <?php if(!$model->bid && $model->updated_at + 14*24*60*60 < time()): ?>
                    <li class="list-group-item list-group-item-danger">
                        Аукцион закрыт
                    </li>
                <?php elseif($model->bid && $model->bid->updated_at + 24*60*60 < time() || $model->bid->cost >= $model->price_blitz): ?>
                    <li class="list-group-item list-group-item-success">
                        Лол выигран <?= ($model->bid->cost >= $model->price_blitz) ? '(достигнута блиц)' : null ?>
                    </li>
                    <li class="list-group-item">
                        Стоимость лота <span class="badge"><?= $model->bid->cost ?> зелени</span>
                    </li>
                    <li class="list-group-item">
                        Победитель <span class="badge"><?= $model->bid->user->username ?></span>
                    </li>
                    <li class="list-group-item">
                        Ставка сделана <span class="badge"><?= date('h:m d.m.Y', $model->bid->updated_at) ?></span>
                    </li>
                <?php else: ?>
                    <li class="list-group-item list-group-item-warning">
                        До закрытия аукциона <span class="badge countdown" data-time="<?= $model->bid? ($model->bid->updated_at + 24*60*60) : ($model->updated_at + 14*24*60*60) ?>">00:00:00</span>
                    </li>
                    <?php if(!$model->bids): ?>
                        <li class="list-group-item list-group-item-info">
                            Текущая ставка <span class="badge">нет ставок</span>
                        </li>
                    <?php else: ?>
                        <?php $last_bid = $model->bid; ?>
                        <li class="list-group-item list-group-item-info">Текущая ставка <span class="badge"><?= $last_bid->cost.' зелени ('.$last_bid->user->username.')' ?></span></li>
                        <?php foreach($model->getBids()->where('NOT id = :last_id', [':last_id' => $last_bid->id])->each(5) as $bid) {
                            echo '<li class="list-two-group">'.Yii::$app->formatter->asInteger($bid->cost).' зелени ('.$bid->user->username.')</li>';
                        } ?>
                    <?php endif; ?>
                    <li class="list-group-item">
                        Начальная цена <span class="badge"><?= \Yii::$app->formatter->asInteger($model->price_min) ?> зелени</span>
                    </li>
                    <li class="list-group-item <?= !$model->bid ? 'hidden' : null ?>">
                        Блиц цена <span class="badge"><?= \Yii::$app->formatter->asInteger($model->price_blitz) ?> зелени</span>
                    </li>
                    <li class="list-group-item list-group-item-danger clearfix">
                        <form method="post" id="add-bid" action="<?= Yii::$app->urlManager->createUrl(['auction/bid/create']) ?>">
                            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                            <input type="hidden" name="lot_id" value="<?= $model->id ?>">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="inputCost" class="sr-only">Стоимость</label>
                                    <input type="text" name="cost" class="form-control" id="inputCost" placeholder="Сумма ставки">
                                </div>
                                <div class="col-xs-6 text-right">
                                    <input type="submit" class="btn btn-danger" value="Сделать ставку">
                                </div>
                            </div>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Владелец',
                'value' => $model->user->username
            ],
            'description:html',
            [
                'label' => 'Создан',
                'value' => date('h:m d.m.Y', $model->created_at)
            ],
        ],
    ]) ?>

</div>
