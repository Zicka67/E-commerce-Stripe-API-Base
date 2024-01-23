<?php
namespace App\Service;

use Stripe\Price;
use App\Entity\Product;
use Stripe\StripeClient;
use App\Model\ShoppingCart;
use Stripe\Checkout\Session;

class StripeService
{
    private StripeClient $stripe;

    /** 
    * @throws ApiErrorException
    */

    public function createProduct(Product $product): \Stripe\Product
    {
        return $this->getStripe()->products->create([
            'name'=>$product->getName(),
            'description'=>$product->getDescription(),
            'active'=>$product->isActive()
        ]);
    }

    public function createPrice(Product $product): Price
    {
        return $this->getStripe()->prices->create([
            'unit_amount'=>$product->getPrice(),
            'currency'=>'EUR',
            'product'=>$product->getStripeProductId(),
        ]);
    }
    
    public function updateProduct(Product $product)
    {
        return $this->getStripe()->products->update($product->getStripeProductId(), [
            'name'=>$product->getName(),
            'description'=>$product->getDescription(),
            'active'=>$product->isActive()
        ]);
    }

    public function createCheckoutSession(ShoppingCart $shoppingCart): Session
    {
        $lineItems = [];

        foreach ($shoppingCart->items as $item) {
            $lineItems[]= [
                'price' => $item->product->getStripePriceId(),
                'quantity' => $item->quantity
            ];
        }

        return $this->getStripe()->checkout->sessions->create([
            'currency' => 'EUR',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'https://localhost:8000/stripe/success?session_id={CHECKOUT_SESSION_ID}',
            'payment_method_types' => ['card'],
        ]);
    }

    public function getCheckoutSession(string $sessionId): Session
    {
        return $this->getStripe()->checkout->sessions->retrieve($sessionId);
    }

    private function getStripe(): StripeClient
    {
        return $this->stripe ??= new StripeClient(
            $_ENV['STRIPE_API_SECRET']
        );
    }
}