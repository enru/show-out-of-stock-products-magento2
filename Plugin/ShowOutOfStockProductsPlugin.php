<?php

namespace Myweb\ShowOutOfStockProducts\Plugin;

class ShowOutOfStockProductsPlugin {

    /**
     * Get Allowed Products
     *
     * @return \Magento\Catalog\Model\Product[]
     */
    public function beforeGetAllowProducts(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject)
    {
        if (!$subject->hasAllowProducts()) {
            $allProducts = $this->loadAllProducts($subject->getProduct());
            $products = [];
            foreach ($allProducts as $product) {
                if ($product->getStatus() != \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED) {
                    $products[] = $product;
                }
            }
            $subject->setAllowProducts($products);
        }

        return [];
    }

    /**
     * from Magento\ConfigurableProduct\Model\Product\Type\Configurable::getConfiguredUsedProductCollection
     */
    private function loadAllProducts($product)
    {
        $collection = $product->getTypeInstance()->getUsedProductCollection($product);
        $collection->setFlag('has_stock_status_filter', true);

        $allProducts = array_values($collection->getItems());
        return $allProducts;
    }

}
