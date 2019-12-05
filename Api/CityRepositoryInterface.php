<?php

namespace Vaimo\NovaPoshta\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CityRepositoryInterface
{

    public function save(\Vaimo\NovaPoshta\Model\CityInterface $request);

    public function getById($id);
}
