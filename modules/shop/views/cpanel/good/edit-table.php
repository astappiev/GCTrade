<table class="table table-hover item-list sort avg-list">
    <thead>
    <tr>
        <th width="5%"></th>
        <th width="5%">ID</th>
        <th class="name"><?= Yii::t('shop', 'NAME') ?></th>
        <th width="12%"><?= Yii::t('shop', 'SELLING_PRICE') ?></th>
        <th width="8%"></th>
        <th width="12%"><?= Yii::t('shop', 'PURCHASE_PRICE') ?></th>
        <th width="8%"></th>
        <th width="15%"><?= Yii::t('shop', 'NUMBER_OF') ?></th>
        <th width="10%"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $command = \Yii::$app->db->createCommand("SELECT tg_shop_good.id AS id, tg_item.alias AS item_id, tg_item.name AS item_name, tg_shop_good.price_sell, tg_shop_good.price_buy, tg_shop_good.stuck FROM tg_shop_good LEFT JOIN tg_item ON tg_shop_good.item_id = tg_item.id WHERE tg_shop_good.shop_id = :shop_id ORDER BY tg_shop_good.item_id");
    foreach($command->bindValue(':shop_id', $model->id)->queryAll() as $price): ?>

        <tr id="item_<?= $price["id"]; ?>">
            <td><img src="/images/items/<?= $price["item_id"] ?>.png" alt="<?= $price["item_name"] ?>" class="small-icon"></td>
            <td><?= $price["item_id"] ?></td>
            <td class="name"><a href="<?= Yii::$app->urlManager->createUrl(['shop/item/view', 'alias' => $price["item_id"]]) ?>"><?= $price["item_name"]; ?></a></td>
            <td><?= ($price["price_sell"]) ? $price["price_sell"] : '—' ?></td>
            <td></td>
            <td><?= ($price["price_buy"])? $price["price_buy"] : '—' ?></td>
            <td></td>
            <td><?= $price["stuck"]; ?></td>
            <td class="control">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary edit-item-buttons" title="Редактировать"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-danger remove-item-buttons" title="Удалить"><span class="glyphicon glyphicon-remove"></span></button>
                </div>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>