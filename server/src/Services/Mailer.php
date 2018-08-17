<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class Mailer
{

    private $mailer;
    private $templating;
    private $sender;
    private $em;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, EntityManagerInterface $em, $sender) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->sender = $sender;
        $this->em = $em;
    }

    public function emailReceipt($commandId)
    {

    $command = $this->em->getRepository('App:Commande')->find($commandId);
    $customer= $command->getCustomer()->getEmail();
    $ecommerceConfig = $this->em->getRepository('App:EcommerceConfig')->find(1);
    $tva = $ecommerceConfig->getTva();

    $message = (new \Swift_Message('Votre commande de chocolats'))
        ->setFrom($this->sender)
        ->setTo($customer)
        ->setSubject('Commande Julien Gayraud Chocolatier Confiseur')
        ->setBody(
             $this->templating->render(
                // templates/emails/registration.html.twig
                'emails/bill.html.twig',
                array('command' => $command, 'tva' => $tva)
            ),
            'text/html'
        )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
    ;

    // Envoie du récap au client
    $this->mailer->send($message);

    // Envoie du récap à Julien pour faire la commande
    $message->setTo($this->sender);
    $this->mailer->send($message);

    return 'ok';


   /*  return new Response(
        '<html><body>Lucky number: </body></html>'
    ); */
  }
}
    /* private function sendEmail($data)
    {
        $container = $this->container;
        $myappSendMail = $container->getParameter('mailer_user');
        $mailer_adress_alias = $container->getParameter('mailer_address_alias');
        $mailer_name_alias = $container->getParameter('mailer_name_alias');
        if($this->getUser()){
            $myappContactMail = $container->getParameter('mailer_user_contact_pro');
        }else{
            $myappContactMail = $container->getParameter('mailer_user_contact');
        }
        $myappContactPassword = $container->getParameter('mailer_password');
        $mailerSmtpTransport = $container->getParameter('mailer_host');
        $mailerPort = $container->getParameter('mailer_port');
        $mailerEncryption = $container->getParameter('mailer_encryption');
        
        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance($mailerSmtpTransport, $mailerPort, $mailerEncryption )
            ->setUsername($myappSendMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("Message de contact OPEN de " . $data["name"] . ": " . $data["object"])
            ->setFrom([$mailer_adress_alias => $mailer_name_alias])
            ->setTo($myappContactMail)
            ->setContentType("text/html")
            ->setBody($data["message"] . '<br><br> De : ' . $data["name"] . ' (' . $data['email'] . ')');

        return $mailer->send($message);
    } */