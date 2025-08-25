<?php
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\NotificationTrait;

class InvoicePaid implements NotificationInterface
 {
    use NotificationTrait;
    
    private $invoice;
    
    public function __construct($invoice) 
    {
        $this->invoice = $invoice;
    }

    /**
     * Prepares notification for 'mail' channel
     */
    public function exportForMail() {
        return Yii::createObject([
           'class' => '\tuyakhov\notifications\messages\MailMessage',
           'view' => ['html' => 'invoice-paid'],
           'viewData' => [
               'invoiceNumber' => $this->invoice->id,
               'amount' => $this->invoice->amount
           ]
        ])
    }
    
    /**
     * Prepares notification for 'sms' channel
     */
    public function exportForSms()
    {
        return \Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\SmsMessage',
            'text' => "Your invoice #{$this->invoice->id} has been paid"
        ]);
    }
    
    /**
     * Prepares notification for 'database' channel
     */
    public function exportForDatabase()
    {
        return \Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\DatabaseMessage',
            'subject' => "Invoice has been paid",
            'body' => "Your invoice #{$this->invoice->id} has been paid",
            'data' => [
                'actionUrl' => ['href' => '/invoice/123/view', 'label' => 'View Details']
            ]
        ]);
    }
 }