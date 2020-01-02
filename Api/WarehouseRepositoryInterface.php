<?php

namespace Vaimo\NovaPoshta\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WarehouseRepositoryInterface
{

    public function save(\Vaimo\NovaPoshta\Model\WarehouseInterface $request);

    public function getById($id);
}
