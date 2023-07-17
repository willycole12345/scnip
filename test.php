<?php

interface ProductSorterInterface
{
    public function sort(array $products): array;
}

class PriceSorter implements ProductSorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        return $products;
    }
}

class SalesPerViewSorter implements ProductSorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($a, $b) {
            $salesPerViewA = $a['sales_count'] / $a['views_count'];
            $salesPerViewB = $b['sales_count'] / $b['views_count'];

            return $salesPerViewA <=> $salesPerViewB;
        });

        return $products;
    }
}

class Catalog
{
    private $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function getProducts(ProductSorterInterface $sorter): array
    {
        return $sorter->sort($this->products);
    }
}

$products = [
    [
        'id' => 1,
        'name' => 'Alabaster Table',
        'price' => 12.99,
        'created' => '2019-01-04',
        'sales_count' => 32,
        'views_count' => 730,
    ],
    [
        'id' => 2,
        'name' => 'Zebra Table',
        'price' => 44.49,
        'created' => '2012-01-04',
        'sales_count' => 301,
        'views_count' => 3279,
    ],
    [
        'id' => 3,
        'name' => 'Coffee Table',
        'price' => 10.00,
        'created' => '2014-05-28',
        'sales_count' => 1048,
        'views_count' => 20123,
    ]
];

$productPriceSorter = new PriceSorter();
$productSalesPerViewSorter = new SalesPerViewSorter();

$catalog = new Catalog($products);

$productsSortedByPrice = $catalog->getProducts($productPriceSorter);
$productsSortedBySalesPerView = $catalog->getProducts($productSalesPerViewSorter);

echo 'Sorted by Price';
echo '<pre>';
echo json_encode($productsSortedByPrice);
echo '</pre>';

echo 'sorted by sales per view';
echo '<pre>';
echo json_encode($productsSortedBySalesPerView);
echo '</pre>';