<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 26.04.2016
 * Time: 12:27
 */

namespace kroshilin\yakassa\models;

use yii\base\Model;
use Yii;

/**
 * Class BaseModel
 * @package kroshilin\yakassa\models
 *
 * @property \kroshilin\yakassa\YaKassa $component
 */

class BaseModel extends Model
{
    /**
     * @var string
     */
    public $component = 'yakassa';

    /**
     * @return \kroshilin\yakassa\YaKassa;
     */
    public function getComponent()
    {
        return Yii::$app->get($this->component);
    }

}