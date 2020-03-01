<?php

namespace PNP\Components\DbEntities;

/**
 * Class ProductMapper
 * @package PNP\Components\DbEntities
 */
class ProductMapper extends Mapper
{
    /**
     * @param string $code
     * @return array
     */
    public function find(string $code): array
    {
        $params   = ['code' => $code];
        $query    = 'SELECT
                        description,
                        normal_price_override,
                        special_price_override
                    FROM
                        products
                    WHERE
                        code = ?:code';
        $products = $this->getConnection()->query($query, $params)->row();

        if (!empty($products)) {
            $query         = 'SELECT currency_code, price FROM normal_prices WHERE product_code = ?:code';
            $normal_prices = $this->getConnection()->query($query, $params)->vars();

            if (!empty($normal_prices)) {
                $products['normal_price'] = $normal_prices;
            }

            $query         = 'SELECT currency_code, price FROM special_prices WHERE product_code = ?:code';
            $special_prices = $this->getConnection()->query($query, $params)->vars();

            if (!empty($special_prices)) {
                $products['special_price'] = $special_prices;
            }

            return $products;
        }

        return [];
    }

    /**
     * @param string $code
     * @param array $attrs
     * @return array
     */
    public function save(string $code, array $attrs): array
    {
        return [];
    }

    /**
     * Return data in the format [currency => rate]
     * @return array
     * @throws \Exception
     */
    public function getRates(): array
    {
        $query  = 'SELECT code, exchange_rate FROM currencies';
        $result = $this->getConnection()->query($query)->vars();
        if (empty($result)) {
            throw new \Exception("Can't get currencies rates");
        }
        return $result;
    }
}
