<?php

namespace Vendor\PrintButton\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Printed
 * @package Vendor\PrintButton\Ui\Component\Listing\Column
 */
class Printed extends Column
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * Status constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        InvoiceRepositoryInterface $invoiceRepository,
        array $components = [],
        array $data = []
    ) {
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Retrieve data for printed column
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $invoice = $this->invoiceRepository->get($item['entity_id']);
                if (isset($item[$this->getData('name')])) {
                    $item[$this->getData('name')] = $invoice->getData('printed');
                }
            }
        }
        return $dataSource;
    }
}
