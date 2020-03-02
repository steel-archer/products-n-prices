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
     * @return array of errors
     */
    public function save(string $code, array $attrs): array
    {
        $errors = [];

        $this->getConnection()->query('SET autocommit = 0');
        $this->getConnection()->query('START TRANSACTION');

        try {
            $params = [
                'code'                   => $code,
                'description'            => $attrs['description'],
                'normal_price_override'  => $attrs['normal_price_override'] ?? false,
                'special_price_override' => $attrs['special_price_override'] ?? false,
            ];
            $query = 'INSERT INTO
                          products (code, description, normal_price_override, special_price_override)
                      VALUES
                          (?:code, ?:description, ?b:normal_price_override, ?b:special_price_override)
                      ON DUPLICATE KEY UPDATE
                          description = VALUES(description),
                          normal_price_override = VALUES(normal_price_override),
                          special_price_override = VALUES(special_price_override)';
            $this->getConnection()->query($query, $params);

            if (is_array($attrs['normal_price'])) {
                $values = [];
                foreach ($attrs['normal_price'] as $currency => $price) {
                    $values[] = [
                        'product_code'  => $code,
                        'currency_code' => $currency,
                        'price'         => $price,
                    ];
                }
                $params = [$values];
                $query = 'INSERT INTO
                              normal_prices (product_code, currency_code, price)
                          VALUES ?v
                          ON DUPLICATE KEY UPDATE
                              currency_code = VALUES(currency_code),
                              price = VALUES(price)';
                $this->getConnection()->query($query, $params);
            }

            // Remove existing special prices if no special prices were provided
            if (empty($attrs['special_price'])) {
                $params = ['product_code' => $code];
                $query  = "DELETE FROM special_prices WHERE product_code = ?:product_code";
                $this->getConnection()->query($query, $params);
            }

            if (is_array($attrs['special_price'])) {
                $values = [];
                foreach ($attrs['special_price'] as $currency => $price) {
                    $values[] = [
                        'product_code'  => $code,
                        'currency_code' => $currency,
                        'price'         => $price,
                    ];
                }
                $params = [$values];
                $query = 'INSERT INTO
                              special_prices (product_code, currency_code, price)
                          VALUES ?v
                          ON DUPLICATE KEY UPDATE
                              currency_code = VALUES(currency_code),
                              price = VALUES(price)';
                $this->getConnection()->query($query, $params);
            }
            $this->getConnection()->query('COMMIT');
        } catch (\Exception $ex) {
            $this->getConnection()->query('ROLLBACK');
            $errors[] = "Can't save a product with code {$code}, error: {$ex->getMessage()}";
        }

        return $errors;
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
