<?php

namespace App\Controller;

use App\Form\CardCredentialsType;
use App\Form\PaymentType;
use App\Services\FakePaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(Request $request)
    {
        $form =  $this->createForm(PaymentType::class);
        $this->container->get('twig');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $callbackUrl = $form->getData()['callbackUrl'];
            return new RedirectResponse("/card?callbackUrl=$callbackUrl");
        }

        return new Response($this->renderView('form.html.twig', ['form' => $form->createView()]));
    }
    
    public function cardCredentialsIndex(Request $request, FakePaymentService $paymentService)
    {
        return new Response($paymentService->getPaymentForm($request->query->get('callbackUrl')));
    }

    public function cardCredentials(Request $request, FakePaymentService $paymentService)
    {
        $form = $this->createForm(CardCredentialsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData();
            $status = $paymentService->validateCredentials((int)$formData['cardNumber'], $formData['cvv'], $formData['holder']);
            if ($status) {
                $paymentService->submitPayment();
            }
            $paymentService->sendPaymentStatus($request->query->get('callbackUrl'), $status);

            return new Response($this->renderView('status.html.twig', ['status' => $status]));
        }

        return new Response($this->renderView('form.html.twig', ['form' => $form->createView()]));
        
    }
}