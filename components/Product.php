<?php

namespace PNP;

use PNP\ProductMapper;

class Product
{

    /**
     * @var ProductMapper
     */
    private $mapper;

    /**
     * @return ProductMapper
     */
    public function getMapper(): ProductMapper
    {
        return $this->mapper;
    }

    /**
     * @param ProductMapper $mapper
     */
    public function setMapper(ProductMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param string $code
     * @return array
     */
    public function get(string $code) : array
    {

    }

    /**
     * @param string $code
     * @param array $attrs
     * @return array
     */
    public function save(string $code, array $attrs): array
    {

    }

    /**
     * @param string $code
     * @param array $attrs
     * @return array
     */
    public function validate(string $code, array $attrs): array
    {

    }
}
