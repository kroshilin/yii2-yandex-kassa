<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 26.04.2016
 * Time: 11:09
 */

namespace kroshilin\yakassa\actions;

use kroshilin\yakassa\models\MD5;
use kroshilin\yakassa\YaKassa;
use yii\base\Action;
use Yii;

/**
 * Class BaseAction
 * @package kroshilin\yakassa\actions
 *
 * @property \kroshilin\yakassa\YaKassa $component
 */

class BaseAction extends Action
{
    /**
     * @var string
     */
    public $component = 'yakassa';

    /**
     * Used to check YaKassa request orderCheck||paymentAviso||orderCancel
     * @var string
     */
    public $actionName;

    /**
     * User-defined function that would be run before response.
     * Response code depends on return result 0 when true, 100 when false
     * Format: function (\yii\web\Request $request) { ... }
     * Should return true||false
     * @var callable
     */
    public $beforeResponse;

    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = false;
        Yii::$app->response->setStatusCode(200);
        Yii::$app->response->headers->set('Content-Type', 'application/xml');
    }

    public function run()
    {
        if ($this->getComponent()->securityType == YaKassa::SECURITY_MD5) {
            $model = new MD5();
            $model->load(Yii::$app->request->post(), "");
            $model->validate();
            if ($model->hasErrors()) {
                return $this->getComponent()->buildResponse($this->actionName, $model->invoiceId, 1, Yii::t($this->getComponent()->messagesCategory, 'Wrong MD5 hash'));
            }
            if (!$this->beforeResponse) {
                return $this->getComponent()->buildResponse($this->actionName, $model->invoiceId, 0);
            }
            if (call_user_func($this->beforeResponse, Yii::$app->request)) {
                return $this->getComponent()->buildResponse($this->actionName, $model->invoiceId, 0);
            } else {
                return $this->getComponent()->buildResponse($this->actionName, $model->invoiceId, 100);
            }
        } elseif ($this->component->securityType == YaKassa::SECURITY_PKCS7) {
            // TODO: write code for PKCS7
        }
        Yii::warning(Yii::t($this->getComponent()->messagesCategory, 'Got unknown security type message'), $this->getComponent()->logCategory);
        return $this->getComponent()->buildResponse($this->actionName, null, 200, Yii::t($this->getComponent()->messagesCategory, 'Got unknown security type message'));
    }

    /**
     * @return \kroshilin\yakassa\YaKassa;
     */
    public function getComponent()
    {
        return Yii::$app->get($this->component);
    }
}