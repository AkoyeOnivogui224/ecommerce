<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCarte(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCarte([]);
    }

    public function add(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {

            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->saveCarte($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCarte($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }
        //Soit le produit est à 1 alors il faut simplement le supprimer.
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        //Soit le produit est plus de 1, alors il faut décrémenter.
        $cart[$id]--;

        $this->saveCarte($cart);
    }

    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->getCart() as $id => $qty) {

            $product = $this->productRepository->find($id);

            //si il n'ya pas de produit on ajoute pas un produit vide mais on continue
            if (!$product) {
                continue;
            }


            $total += $product->getPrice() * $qty / 100;
        }
        return $total;
    }

    /**
     * @return cartItem[]
     */
    public function getDetailedCartItem(): array
    {
        $detailedCard = [];
        foreach ($this->getCart() as $id => $qty) {

            $product = $this->productRepository->find($id);

            //si il n'ya pas de produit on ajoute pas un produit vide mais on continue
            if (!$product) {
                continue;
            }

            $detailedCard[] = new CartItem($product, $qty);
        }

        return $detailedCard;
    }
}
