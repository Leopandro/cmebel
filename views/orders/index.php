<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Clients;
use yii\helpers\ArrayHelper;
use app\models\OrderStatus;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
	        [
	            'attribute' => 'order_opencart_id',
	            'options' => ['width' => '50px;']
	        ],
            //'version',
	        [
		        'attribute' => 'client_id',
		        //'filter' =>  ArrayHelper::map(Clients::find()->all(), 'id', 'name'),
		        'filter' =>  false,
		        'value' => 'client.name',
	        ],
	        [
		        'attribute' => 'status_id',
		        'filter' =>  ArrayHelper::map(OrderStatus::find()->all(), 'id', 'name'),
		        'value' => 'status.name',
	        ],
			//'address'
//				[
//					'attribute' => 'payment_method',
//					'label' => 'адрес',
//					'value' => function($model)
//					{
//						return
//								$model->payment_method.' '.
//								$model->payment_address_1.' '.
//								$model->payment_address_2.' '.
//								$model->payment_city.' '.
//								$model->payment_postcode.' '.
//								$model->payment_country.' '.
//								$model->payment_zone.' ';
//					},
//				],

			//'commentary'

				[
						'attribute' => 'comment'
				],
	        [
		        'attribute' => 'total',
		        'filter' => false
	        ],
	        [
		        'attribute' => 'date_added',
		        'format' => 'datetime',
		        'filter' => false
	        ],
	        [
		        'attribute' => 'date_modified',
		        'format' => 'datetime',
		        'filter' => false
	        ],

            [
	            'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>

</div>
