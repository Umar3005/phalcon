<?php declare(strict_types=1);

namespace Product\Helper;

class ProductTax
{
    public function calculateProductTax($product)
    {
        /**
         * @TODO: Refactor this method, when add tax calculating
         */
        $originalPrice = $product['price'];
        $finalPrice = $product['final_price'] ?? $product['special_price'];

        $product['original_price'] = $originalPrice;
        $product['price'] = $finalPrice;
        $product['original_price_tax'] = 0;
        $product['original_price_incl_tax'] = $originalPrice;
        $product['originalPrice'] = $originalPrice;
        $product['originalPriceTax'] = 0;
        $product['originalPriceInclTax'] = $originalPrice;
        $product['original_special_price'] = $finalPrice;
        $product['price_tax'] = 0;
        $product['price_incl_tax'] = $finalPrice;
        $product['priceTax'] = 0;
        $product['priceInclTax'] = $finalPrice;
        $product['special_price_tax'] = 0;
        $product['special_price_incl_tax'] = 0;
        $product['specialPrice'] = $finalPrice;
        $product['specialPriceTax'] = 0;
        $product['specialPriceInclTax'] = 0;

        if ($product['final_price']) {
            $product['original_final_price'] = $finalPrice;
            $product['final_price_tax'] = 0;
            $product['final_price_incl_tax'] = $finalPrice;
            $product['finalPrice'] = $finalPrice;
            $product['finalPriceTax'] = 0;
            $product['finalPriceInclTax'] = $finalPrice;
        }

        return $product;
    }
}
