<?php

namespace Vendor\PrintButton\Plugin\Controller\Adminhtml\Invoice\AbstractInvoice;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Invoice\AbstractInvoice\PrintAction;
use Psr\Log\LoggerInterface;

/**
 * Class PrintPlugin
 * @package Vendor\PrintButton\Plugin\Controller\Adminhtml\Invoice\AbstractInvoice
 */
class PrintPlugin
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PrintPlugin constructor.
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        LoggerInterface $logger
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->logger = $logger;
    }

    /**
     * After plugin for print invoice action
     * @param PrintAction $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(PrintAction $subject, $result)
    {
        if ($invoiceId = $subject->getRequest()->getParam('invoice_id')) {
            try {
                $invoice = $this->invoiceRepository->get($invoiceId);
                if ($invoice && !$invoice->getPrinted()) {
                    $invoice->setPrinted(1);
                    $this->invoiceRepository->save($invoice);
                }
            } catch (\Exception $e) {
                $this->logger->exception('Print plugin action error: ', ['exception' => $e]);
            }
        }
        return $result;
    }
}
