<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\users\models\User $user
 * @var app\modules\users\models\Profile $profile
 */

$this->title = 'Update User: ' . $user->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'user' => $user,
        'profile' => $profile,
	]); ?>

</div>
