<table class="table table-hover item-list sort">
    <thead>
    <tr>
        <th width="5%"></th>
        <th width="5%"><?= Yii::t('app/shop', 'ID') ?></th>
        <th class="name"><?= Yii::t('app/shop', 'Name') ?></th>
        <th width="15%"><?= Yii::t('app/shop', 'Selling price') ?></th>
        <th width="15%"><?= Yii::t('app/shop', 'Purchase price') ?></th>
        <th width="15%"><?= Yii::t('app/shop', 'Number of') ?></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach($model->products as $price): ?>

        <tr>
            <td><img src="/images/items/<?= $price->item->getAlias() ?>.png" alt="<?= $price->item->name; ?>" class="small-icon"></td>
            <td><?= $price->item->getAlias() ?></td>
            <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['shop/item/view', 'alias' => $price->item->getAlias()]) ?>"><?= $price->item->name; ?></a></td>
            <td><?= $price->price_sell ? $price->price_sell : '—' ?></td>
            <td><?= $price->price_buy ? $price->price_buy : '—' ?></td>
            <td><?= $price->stuck; ?></td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>