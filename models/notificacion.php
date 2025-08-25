<?php
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\NotificationTrait;

class Trabajadormaquina implements NotificationInterface
 {
    use NotificationTrait;
    
    private $trabajadormaquina;
    
    public function __construct($trabajadormaquina) 
    {
        $this->trabajadormaquina = $trabajadormaquina;
    }

    /**
     * Prepares notification for 'mail' channel
     */
    public function exportForMail() {
        return Yii::createObject([
           'class' => '\tuyakhov\notifications\messages\MailMessage',
           'view' => ['html' => 'invoice-paid'],
           'viewData' => [
               'status' => $this->trabajadormaquina->status_trabajo,
               'trabajador' => $this->trabajadormaquina->id_trabajador,
               'maquina' => $this->trabajadormaquina->id_maquina,
               'fecha_inicio' => $this->trabajadormaquina->fecha_inicio,
               'fecha_fin' => $this->trabajadormaquina->fecha_fin
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