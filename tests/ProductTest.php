<?php

namespace PNP\Tests;

use PNP\Components\Product;
use PNP\Components\DbEntities\ProductMapper;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 * @package PNP\Tests
 */
class ProductTest extends TestCase
{
    /**
     * @dataProvider saveProvider
     * @param string $code
     * @param array $attrs
     * @param array $expectedErrors
     */
    public function testSave(string $code, array $attrs, array $expectedErrors): void
    {
        $mapperMock = $this->createMock(ProductMapper::class);
        $mapperMock->method('getRates')
                   ->willReturn(['CAD' => 3, 'GBP' => 1, 'USD' => 2.55]);
        $mapperMock->method('save')
                   ->willReturn([]);

        $product = new Product($mapperMock);
        $this->assertSame($expectedErrors, $product->save($code, $attrs));
    }

    /**
     * @return array
     */
    public function saveProvider(): array
    {
        return [
            [
                '',
                [],
                [Product::ERROR_EMPTY_PRODUCT_CODE],
            ],
            [
                'Test product',
                [
                    'normal_price_override' => [],
                    'special_price_override' => [],
                    'special_price' => [
                        'HAH' => 2002,
                    ],
                ],
                [
                    Product::ERROR_ABSENT_DESCRIPTION,
                    Product::ERROR_NPO_NOT_BOOL,
                    Product::ERROR_ABSENT_NORMAL_PRICE,
                    Product::ERROR_SPO_NOT_BOOL,
                    sprintf(Product::ERROR_UNKNOWN_CURRENCY, 'HAH'),
                ],
            ],
            [
                'Test product',
                [
                    'description' => '',
                    'normal_price' => ['TEST' => 12],
                    'special_price' => 20,
                ],
                [
                    sprintf(Product::ERROR_UNKNOWN_CURRENCY, 'TEST'),
                    sprintf(Product::ERROR_NO_NORMAL_PRICE, 'CAD'),
                    sprintf(Product::ERROR_NO_NORMAL_PRICE, 'GBP'),
                    sprintf(Product::ERROR_NO_NORMAL_PRICE, 'USD'),
                    Product::ERROR_SPECIAL_PRICE_NOT_ARRAY,
                ],
            ],
            [
                'Test product',
                [
                    'description' => '',
                    'normal_price'           => [
                        'GBP' => 10,
                        'CAD' => 15,
                    ],
                    'special_price_override' => true,
                    'special_price'          => [
                        'USD' => 1,
                        'GBP' => 100,
                    ],
                ],
                [
                    sprintf(Product::ERROR_NO_NORMAL_PRICE, 'USD'),
                    sprintf(Product::ERROR_SP_WITHOUT_NP, 'USD'),
                    sprintf(Product::ERROR_NO_SPECIAL_PRICE, 'CAD'),
                ],
            ],
            [
                'Test product',
                [
                    'description'            => 'product description',
                    'normal_price_override'  => false,
                    'normal_price'           => [
                        'GBP' => 10.5,
                        'USD' => 20,
                        'CAD' => 5.5,
                    ],
                    'special_price_override' => true,
                    'special_price'          => [
                        'GBP' => 12.5,
                        'USD' => 21,
                        'CAD' => 31.5,
                    ],
                ],
                [
                    // This test also checks override logic
                    sprintf(Product::ERROR_SP_BIGGER_EQUAL_THAN_NP, 'GBP', '10.500000', '12.500000'),
                    sprintf(Product::ERROR_SP_BIGGER_EQUAL_THAN_NP, 'CAD', '31.500000', '31.500000'),
                ],
            ],
            [
                'Test product',
                [
                    'description'            => 'product description',
                    'normal_price_override'  => false,
                    'normal_price'           => [
                        'GBP' => 10,
                        'USD' => 20,
                        'CAD' => 30,
                    ],
                    'special_price_override' => true,
                    'special_price'          => [
                        'GBP' => 1,
                        'USD' => 2,
                        'CAD' => 3,
                    ],
                ],
                [], // No errors
            ],
        ];
    }

    /**
     * @dataProvider saveNoCurrenciesProvider
     * @param string $code
     * @param array $attrs
     * @param array $expectedErrors
     */
    public function testSaveNoCurrencies(string $code, array $attrs, array $expectedErrors): void
    {
        $mapperMock = $this->createMock(ProductMapper::class);
        $mapperMock->method('getRates')
                   ->willThrowException(new \Exception(ProductMapper::ERROR_CURRENCIES));

        $product = new Product($mapperMock);
        $this->assertSame($expectedErrors, $product->save($code, $attrs));
    }

    /**
     * @return array
     */
    public function saveNoCurrenciesProvider(): array
    {
        return [
            [
                'test',
                [
                    'description' => '',
                ],
                [
                    ProductMapper::ERROR_CURRENCIES,
                ],
            ],
        ];
    }
}
