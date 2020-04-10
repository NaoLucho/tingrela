<?php

use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

// $_SERVER['APP_ENV']='prod';
// $_SERVER['APP_SECRET']='d9d57f145367de6ad154938d340df2dc';
// $_SERVER['DATABASE_URL']='mysql://root:@127.0.0.1:3306/gjchoc';
// $_SERVER['STRIPE_API_KEY']='sk_test_XpQIM8XGNO2BoGr3mpdjNWsm';
// $_SERVER['MAILER_USER']='aaronleehartnell@gmail.com';
// $_SERVER['MAILER_PASS']='3cur3u1l3cur3u1l';
// $_SERVER['MAILER_HOST']='smtp.gmail.com';
// $_SERVER['MAILER_PORT']=465;
// $_SERVER['CORS_ALLOW_ORIGIN']='^https?://localhost(:[0-9]+)?$';

// The check is to ensure we don't use .env in production
if (!isset($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv())->load(__DIR__.'/../.env');
}

$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts(explode(',', $trustedHosts));
}

$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
