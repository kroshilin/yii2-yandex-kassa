<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 26.04.2016
 * Time: 11:21
 */

namespace kroshilin\yakassa\models;

use Yii;

class MD5 extends BaseModel
{
    public $action;
    public $orderSumAmount;
    public $orderSumCurrencyPaycash;
    public $orderSumBankPaycash;
    public $shopId;
    public $invoiceId;
    public $customerNumber;
    public $md5;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action', 'orderSumAmount', 'orderSumCurrencyPaycash', 'orderSumBankPaycash', 'shopId', 'invoiceId', 'customerNumber', 'md5'], 'required'],
            ['md5', 'validateMD5']
        ];
    }

    /**
     * Checking the MD5 sign.
     * @param  array $request payment parameters
     * @return bool true if MD5 hash is correct
     */
    private function validateMD5($attribute, $params)
    {
        $str = implode(';', [
            $this->action,
            $this->orderSumAmount,
            $this->orderSumCurrencyPaycash,
            $this->orderSumBankPaycash,
            $this->shopId,
            $this->invoiceId,
            $this->customerNumber,
            $this->getComponent()->shopPassword
        ]);

        Yii::info(
            Yii::t($this->getComponent()->messagesCategory, "String to md5: {md5}", ['md5' => $str]),
            $this->getComponent()->logCategory
        );
        $md5 = strtoupper(md5($str));

        if ($md5 != strtoupper($this->$attribute)) {
            Yii::error(
                Yii::t($this->getComponent()->messagesCategory, "Wait for md5: {md5}, recieved md5: {recievedmd5}", [
                    'md5' => $md5,
                    'recievedmd5' => $this->$attribute
                ]),
                $this->getComponent()->logCategory
            );
            $this->addError(
                $attribute,
                Yii::t($this->getComponent()->messagesCategory, 'Security check failed: wrong MD5 hash')
            );
        }
    }
}