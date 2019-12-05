<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 2019-11-10
 * Time: 22:21
 */

namespace Vaimo\NovaPoshta\Model\ResourceModel\City;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Vaimo\NovaPoshta\Model\City;
use Vaimo\NovaPoshta\Model\ResourceModel\City as GridResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(City::class, GridResource::class);
    }
}