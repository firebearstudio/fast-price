<?php

namespace Firebear\FastPrice\Controller\Adminhtml\Product;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ProductRepository as Product;

class InlineEdit extends \Magento\Backend\App\Action
{

    /**
     * @var Product
     */
    private $product;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Product $product
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Product $product
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->product = $product;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (empty($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $productId) {
                    $product = $this->product->getById($productId);
                    try {
                        $data['quantity_and_stock_status'] = $product->getData('quantity_and_stock_status');
                        $data['quantity_and_stock_status']['qty'] = $postItems[$productId]['qty'];
                        $data['price'] = $postItems[$productId]['price'];
                        $product->setData(array_merge($product->getData(), $data));
                        $product->setQty($postItems[$productId]['qty']);
                        $extendedAttributes = $product->getExtensionAttributes();
                        $stockItem = $extendedAttributes->getStockItem();
                        $stockItem->setQty($postItems[$productId]['qty']);
                        $this->product->save($product);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithProductId(
                            $product,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * @param $product
     * @param $errorText
     *
     * @return string
     */
    private function getErrorWithProductId($product, $errorText)
    {
        return '[Product ID: ' . $product->getId() . '] ' . $errorText;
    }
}
