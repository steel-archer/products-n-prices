<?php

namespace PNP\Components;

use PNP\Components\DbEntities\ProductMapper;

/**
 * Class Product
 * @package PNP\Components
 */
class Product
{
    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->setMapper(new ProductMapper());
    }

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
     * @return Product
     */
    public function setMapper(ProductMapper $mapper): Product
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @param string $code
     * @return array
     */
    public function find(string $code) : array
    {
        return $this->getMapper()->find($code);
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
