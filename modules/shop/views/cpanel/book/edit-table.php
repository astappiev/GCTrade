<table class="table table-hover item-list sort">
    <thead>
    <tr>
        <th width="5%"></th>
        <th class="name"><?= Yii::t('app/shop', 'NAME') ?></th>
        <th class="name" width="25%"><?= Yii::t('app/shop', 'AUTHOR') ?></th>
        <th width="15%"><?= Yii::t('app/shop', 'SELLING_PRICE') ?></th>
        <th width="10%"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model->products as $price): ?>

        <tr id="item_<?= $price->id; ?>">
            <td><img src="/images/items/<?= $price->item->alias ?>.png" alt="<?= $price->item->name; ?>" class="small-icon"></td>
            <td class="name"><?= $price->name; ?></td>
            <td class="name"><?= $price->author ?></td>
            <td><?= $price->price_sell ? $price->price_sell : '—' ?></td>
            <td class="control">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary edit-book-buttons" title="Редактировать"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-danger remove-book-buttons" title="Удалить"><span class="glyphicon glyphicon-remove"></span></button>
                </div>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>