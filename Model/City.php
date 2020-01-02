<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 2019-12-04
 * Time: 12:07
 */

namespace Vaimo\NovaPoshta\Model;


use Magento\Framework\Model\AbstractModel;

use Vaimo\NovaPoshta\Model\ResourceModel\City as ResourceModel;

/**
 * Class City
 * @package Vaimo\NovaPoshta\Model
 */
class City extends AbstractModel implements CityInterface
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param $city_id
     * @return City
     */
    public function setCityId($city_id)
    {
        return $this->setData('city_id', $city_id);
    }

    /**
     * @param $city_name
     * @return City
     */
    public function setCityName($city_name)
    {
        return $this->setData('city_name', $city_name);
    }

    /**
     * @param $ref
     * @return City
     */
    public function setRef($ref)
    {
        return $this->setData('ref', $ref);
    }

}