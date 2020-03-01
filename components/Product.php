<?php

namespace PNP\Components;

use PNP\Components\DbEntities\Mapper;
use PNP\Components\DbEntities\ProductMapper;

/**
 * Class Product
 * @package PNP\Components
 */
class Product
{
    /**
     * Base currency
     */
    public const BASE_CURRENCY = 'GBP';

    /**
     * Product constructor.
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        $this->setMapper($mapper);
    }

    /**
     * @var ProductMapper
     */
    private $mapper;

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    /**
     * @param Mapper $mapper
     * @return Product
     */
    public function setMapper(Mapper $mapper): Product
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
     * @return array of errors (empty if everything is good)
     */
    public function save(string $code, array $attrs): array
    {
        $result = $this->validate($attrs);
        $errors = $result['errors'];
        if (empty($errors)) {
            $attrs  = $result['attrs'];
            $attrs  = $this->transform($attrs);
            // This check should be done after prices transform, so we can't move it to validation
            $errors = $this->checkSpecialPrice($attrs);
            if (empty($errors)) {
                $errors = $this->getMapper()->save($code, $attrs);
            }
        }

        return $errors;
    }

    /**
     * @param array $attrs
     * @return array
     */
    protected function validate(array $attrs): array
    {
        $errors         = [];
        $validatedAttrs = [];

        try {
            $rates = $this->getMapper()->getRates();
        } catch (\Exception $ex) {
            // And that also means that we can't validate currencies
            $errors[] = $ex->getMessage();
        }

        // Description
        if (!isset($attrs['description'])) {
            $errors[] = 'description is absent';
        } else {
            $validatedAttrs['description'] = filter_var($attrs['description'], FILTER_SANITIZE_STRING);
        }

        // Normal price override
        if (!isset($attrs['normal_price_override'])) {
            $validatedAttrs['normal_price_override'] = false;
        } else {
            $validatedAttrs['normal_price_override'] = filter_var(
                $attrs['normal_price_override'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
            if (is_null($validatedAttrs['normal_price_override'])) {
                $errors[] = "normal_price_override is not of boolean type";
            }
        }

        // Normal price
        if (!empty($rates)) {
            if (empty($attrs['normal_price'])) {
                $errors[] = 'normal_price is absent';
            } elseif (!is_array($attrs['normal_price'])) {
                $errors[] = 'normal_price must be an array';
            } else {
                foreach ($attrs['normal_price'] as $currency => $price) {
                    if (!array_key_exists($currency, $rates)) {
                        $errors[] = "Unknown currency: {$currency}";
                    } else {
                        $validatedAttrs['normal_price'][$currency] = filter_var($price, FILTER_VALIDATE_FLOAT);
                    }
                }
            }
            if (empty($validatedAttrs['normal_price'][self::BASE_CURRENCY])) {
                $errors[] = "Provide normal_price for base currency (" . self::BASE_CURRENCY . ')';
            }
        }

        // Special price override
        if (!isset($attrs['special_price_override'])) {
            $validatedAttrs['special_price_override'] = false;
        } else {
            $validatedAttrs['special_price_override'] = filter_var(
                $attrs['special_price_override'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
            if (is_null($validatedAttrs['special_price_override'])) {
                $errors[] = "special_price_override is not of boolean type";
            }
        }

        // Special price
        if (!empty($rates)) {
            if (!is_array($attrs['special_price'])) {
                $errors[] = 'special_price must be an array';
            } else {
                foreach ($attrs['special_price'] as $currency => $price) {
                    if (!array_key_exists($currency, $rates)) {
                        $errors[] = "Unknown currency: {$currency}";
                    } else {
                        // If the special price is provided, it should be lower than the normal price
                        $specialPrice = filter_var($price, FILTER_VALIDATE_FLOAT);
                        $normalPrice  = $validatedAttrs['normal_price'][$currency];
                        if (empty($normalPrice)) {
                            $errors[] = "There is a special_price for currency {$currency}, but normal_price for this currency is absent";
                        } else {
                            $validatedAttrs['special_price'][$currency] = $specialPrice;
                        }
                    }
                }
                if (empty($validatedAttrs['special_price'][self::BASE_CURRENCY])) {
                    $errors[] = "Provide special_price for base currency (" . self::BASE_CURRENCY . ')';
                }
            }
        }

        if (!empty($errors)) {
            $validatedAttrs = [];
        }
        return [
            'errors' => $errors,
            'attrs'  => $validatedAttrs,
        ];
    }

    /**
     * @param array $attrs
     * @return array
     */
    protected function transform(array $attrs): array
    {
        // We've already checked that rates are available
        $rates = $this->getMapper()->getRates();

        if (empty($attrs['normal_price_override'])) {
            $basePrice = $attrs['normal_price'][self::BASE_CURRENCY];
            foreach ($attrs['normal_price'] as $currency => $price) {
                if ($currency != self::BASE_CURRENCY) {
                    $attrs['normal_price'][$currency] = $basePrice * $rates[$currency];
                }
            }
        }

        if (empty($attrs['special_price_override'])) {
            $basePrice = $attrs['special_price'][self::BASE_CURRENCY];
            foreach ($attrs['special_price'] as $currency => $price) {
                if ($currency != self::BASE_CURRENCY) {
                    $attrs['special_price'][$currency] = $basePrice * $rates[$currency];
                }
            }
        }

        return $attrs;
    }

    /**
     * @param array $attrs
     * @return array
     */
    protected function checkSpecialPrice(array $attrs): array
    {
        $errors = [];
        if (!empty($attrs['special_price'])) {
            foreach ($attrs['special_price'] as $currency => $specialPrice) {
                $normalPrice  = $attrs['normal_price'][$currency];
                if ($specialPrice >= $normalPrice) {
                    $errors[] = "special_price ({$specialPrice} {$currency}) must be lower than normal_price ({$normalPrice} {$currency})";
                }
            }
        }

        return $errors;
    }
}
