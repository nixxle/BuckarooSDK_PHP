<?php

namespace Buckaroo\PaymentMethods\CreditManagement;

use Buckaroo\PaymentMethods\CreditManagement\Models\CreditNote;
use Buckaroo\PaymentMethods\CreditManagement\Models\Debtor;
use Buckaroo\PaymentMethods\CreditManagement\Models\DebtorInfo;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\CreditManagement\Models\MultipleInvoiceInfo;
use Buckaroo\PaymentMethods\CreditManagement\Models\PaymentPlan;
use Buckaroo\PaymentMethods\PaymentMethod;

class CreditManagement extends PaymentMethod
{
    protected string $paymentName = 'CreditManagement3';
    protected array $requiredConfigFields = ['currency'];

    public function createInvoice()
    {
        $invoice = new Invoice($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CreateInvoice', $invoice);

        return $this->dataRequest();
    }

    public function createCreditNote()
    {
        $creditNote = new CreditNote($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CreateCreditNote', $creditNote);

        return $this->dataRequest();
    }

    public function addOrUpdateDebtor()
    {
        $debtor = new Debtor($this->payload);

        $this->setServiceList('AddOrUpdateDebtor', $debtor);

        return $this->dataRequest();
    }

    public function createPaymentPlan()
    {
        $paymentPlan = new PaymentPlan($this->payload);

        $this->setServiceList('CreatePaymentPlan', $paymentPlan);

        $this->request->setData('Description', $this->payload['description'] ?? null);

        return $this->dataRequest();
    }

    public function terminatePaymentPlan()
    {
        $paymentPlan = new PaymentPlan($this->payload);

        $this->setServiceList('TerminatePaymentPlan', $paymentPlan);

        return $this->dataRequest();
    }

    public function pauseInvoice()
    {
        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('PauseInvoice');

        return $this->dataRequest();
    }

    public function unpauseInvoice()
    {
        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('UnPauseInvoice');

        return $this->dataRequest();
    }

    public function invoiceInfo()
    {
        $multipleInvoices = new MultipleInvoiceInfo($this->payload);

        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('InvoiceInfo', $multipleInvoices);

        return $this->dataRequest();
    }

    public function debtorInfo()
    {
        $debtorInfo = new DebtorInfo($this->payload);

        $this->setServiceList('DebtorInfo', $debtorInfo);

        return $this->dataRequest();
    }

}