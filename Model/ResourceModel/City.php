<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 2019-12-04
 * Time: 12:11
 */

namespace Vaimo\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class City extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('vaimo_novaposhta_cities', 'city_id');
    }
}