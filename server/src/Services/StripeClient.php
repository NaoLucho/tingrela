<?php

/*
<?php

namespace App\Services;

use Flosch\Bundle\StripeBundle\Stripe\StripeClient as BaseStripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Stripe extends BaseStripeClient
{
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $stripeApiKey = $this->params->get('stripe_api_key');

        parent::__construct($stripeApiKey);

        return $this;
    }
}
*/

namespace App\Services;
 
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;
 
class StripeClient
{
  private $config;
  private $logger;
 
  public function __construct(ParameterBagInterface $params)
  {
    $this->params = $params;
    $stripeApiKey = $this->params->get('stripe_api_key');
    \Stripe\Stripe::setApiKey($stripeApiKey);
  }
 
  public function createCharge($token, $amount, $email)
  {
    try {
      $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'eur',
        'source' => $token,
        'receipt_email' => $email
      ]);
      return $charge;
    } catch (\Stripe\Error\Base $e) {
      /* $this->logger->error(sprintf('%s Problème rencontré lors du paiement: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]); */
      
      throw $e;
    }
  }
}