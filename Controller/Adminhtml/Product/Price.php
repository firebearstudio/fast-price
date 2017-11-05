<?php

namespace Firebear\FastPrice\Controller\Adminhtml\Product;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ProductRepository as Product;

class Price extends \Magento\Backend\App\Action
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
        $data = $this->getRequest()->getParams();

        if (!empty($data['selected'])) {
            foreach ($data['selected'] as $productId) {
                $product = $this->product->getById($productId);
                try {
                    $data['price'] = $data['input'];
                    $product->setData(array_merge($product->getData(), $data));
                    $extendedAttributes = $product->getExtensionAttributes();
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
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('catalog/*/');
        return $resultRedirect;
    }
}
