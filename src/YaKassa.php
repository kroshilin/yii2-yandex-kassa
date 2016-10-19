<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 25.04.2016
 * Time: 21:20
 */
namespace kroshilin\yakassa;

use yii\base\Component;

class YaKassa extends Component
{
    const SECURITY_MD5   = "MD5";
    const SECURITY_PKCS7 = "PKCS7";

    const PAYMENT_ACTION_PRODUCTION = 'https://money.yandex.ru/eshop.xml';
    const PAYMENT_ACTION_DEMO       = 'https://demomoney.yandex.ru/eshop.xml';

    public $shopPassword;
    public $securityType;
    public $logCategory;
    public $shopId;
    public $scId;
    public $currency;
    public $requestSource;
    public $mwsCert;
    public $mwsPrivateKey;
    public $mwsCertPassword;
    public $paymentAction;

    /**
     * @var string
     */
    public $messagesCategory = 'yakassa';

    public function init()
    {
        parent::init();
        $this->registerMessages();
    }

    /**
     * Building XML response.
     *
     * @param  string $action     "checkOrder" or "paymentAviso" string
     * @param  string $invoiceId  transaction number
     * @param  string $resultCode result code
     * @param  string $message    error message. May be null.
     *
     * @return string                prepared XML response
     */
    public function buildResponse($action, $invoiceId, $resultCode, $message = null)
    {
        $xml = new \DOMDocument("1.0", "utf-8");;
        $child = $xml->createElement($action . "Response");
        $child->setAttribute('performedDatetime', date("Y-m-d\TH:i:s.000P"));
        $child->setAttribute('code', $resultCode);
        if ($message) {
            $child->setAttribute('message', $message);
        }
        $child->setAttribute('invoiceId', $invoiceId);
        $child->setAttribute('shopId', $this->shopId);
        $xml->appendChild($child);

        return $xml->saveXML();
    }

    /**
     * @return void Registers widget translations
     */
    protected function registerMessages()
    {
        if (!array_key_exists($this->messagesCategory, \Yii::$app->i18n->translations)) {
            \Yii::$app->i18n->translations[$this->messagesCategory] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath'       => __DIR__ . '/messages',
                'fileMap'        => [
                    'yakassa' => 'yakassa.php'
                ],
            ];
        }
    }
}