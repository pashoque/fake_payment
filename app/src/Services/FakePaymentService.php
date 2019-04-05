<?php

namespace App\Services;



use App\Form\CardCredentialsType;
use GuzzleHttp\Client;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class FakePaymentService implements PaymentServiceInterface
{
    const FAKE_SUCCESS_CARD = 1111111111111111;
    const FAKE_FAIL_CARD = 2222222222222222;

    private $formFactory;
    private $twig;
    private $client;
    private $router;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->client = new Client();
        $this->router = $router;
    }

    public function createPayment(float $amount, string $currency): bool
    {
        return true;
    }

    public function getPaymentForm(string $callbackUrl): string
    {
        return $this->twig->render('form.html.twig', [
            'form' => $this->formFactory->create(CardCredentialsType::class, null, [
                'action' => $this->router->generate('cardSubmit') . '?callbackUrl=' . $callbackUrl
            ])->createView()
        ]);

    }

    public function submitPayment(): bool
    {
        return true;
    }

    public function validateCredentials(int $cardNumber, string $cvv, string $holder): bool
    {
        return $cardNumber === self::FAKE_SUCCESS_CARD;
    }

    public function sendPaymentStatus(string $callbackUrl, bool $status): void
    {
        try {
            $this->client->request('GET', $callbackUrl . "?success=$status");
        } catch (\Exception $e) {
            // log exception
        }

    }
}