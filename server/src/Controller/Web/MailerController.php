<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MailerController extends Controller
{
      
    /** 
     * @Route("/email")
     */
    public function emailReceipt(\Swift_Mailer $mailer)
    {
    $message = (new \Swift_Message('Hello Email'))
        ->setFrom('aaronleehartnell@gmail.com')
        ->setTo('aaronleehartnell@gmail.com')
        ->setBody(
            '<html><head></head><body>Test email</body></html>',
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

    $mailer->send($message);
    return new Response(
        '<html><body>Lucky number: </body></html>'
    );
  }
}

/* public function contactAction(Request $request)
    {

        $data = $request->request->all();

        // Send mail
        if ($this->sendEmail($data)) {

            // Everything OK, redirect to wherever you want ! :

            return $this->render('MNHNPortailBundle:Contact:success.html.twig');
        } else {
            // An error ocurred, handle
            // TODO
            //dump("Errooooor 😞");
        }
        
    }
 */
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