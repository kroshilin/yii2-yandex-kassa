Yandex Kassa
============
Yandex kassa as Yii2 component

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kroshilin/yii2-yandex-kassa "*"
```

or add

```
"kroshilin/yii2-yandex-kassa": "*"
```

to the require section of your `composer.json` file.


Usage
-----
First, add extension under the components section of your config.php

```
'components' => [
	...
	'yakassa' => [
		'class' => 'kroshilin\yakassa\YaKassa',
		'shopPassword' => 'password',
		'securityType' => 'MD5',
		'shopId' => '12345',
		'scId' => '123',
		'currency' => '10643'
	]
	...
]
```

Create controller and configure actions for checkOrder and paymentAviso yandex requests
If need, you can use callable 'beforeResponse' property of actions, to define additional checks of Yandex's requests.
Depends on result of 'beforeResponse' (true||false) action would generate corresponding response.
```
class YaKassaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'order-check' => ['post'],
                    'payment-notification' => ['post'],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'order-check' => [
                'class' => 'app\components\yakassa\actions\CheckOrderAction',
                'beforeResponse' => function ($request) {
                    /**
                     * @var \yii\web\Request $request
                     */
                    Yii::warning("Кто-то хотел купить несуществующую подписку! InvoiceId: $invoiceId", Yii::$app->yakassa->logCategory);
                    return false;
                }
            ],
            'payment-notification' => [
                'class' => 'app\components\yakassa\actions\PaymentAvisoAction',
                'beforeResponse' => function ($request) {
                    /**
                     * @var \yii\web\Request $request
                     */
                }
            ],
        ];
    }
}
```

Using widget is simple. You have to implement 2 interfaces. First is OrderInterface for your order model, to pass sum and id to form.
Second is Customer interface, to pass customer id and pre-fill phone and email if exist.

```
echo kroshilin\yakassa\widgets\Payment::widget([
    'order' => $order,
    'userIdentity' => Yii::$app->user->identity,
	'data' => ['customParam' => 'value'],
	'paymentType' => ['PC' => 'Со счета в Яндекс.Деньгах', 'AC' => 'С банковской карты']
]);
```


