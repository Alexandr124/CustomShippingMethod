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

class City extends AbstractModel implements CityInterface
{

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function setCityId($city_id)
    {
        // TODO: Implement setCityId() method.
    }

    public function setCityName($city_name)
    {
        // TODO: Implement setCityName() method.
    }

    public function setRef($ref)
    {
        // TODO: Implement setRef() method.
    }

}