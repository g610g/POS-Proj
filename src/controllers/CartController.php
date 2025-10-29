<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\Views;
use Exception;
use Respect\Validation\Validator;

class CartController
{
    private $request;

    public function __construct()
    {
        $this->request = new Request();
    }
    public function index(): void
    {

        $cartData = Session::get('cart', []);
        $totalPrice = array_reduce($cartData, function (float $carry, array $item) {
            return $carry + $item['total_amount'];
        }, 0.0);
        Views::render('cart.php', ['total' => $totalPrice]);
    }

    public function removeCartItem(): void
    {
        $success = $this->request->validate([
            'product_id' => Validator::stringVal()->min(1), //NOTE::product_id is given as string type
        ]);
        if (!$success) {
            Session::set('error', 'Missing Product Id');
            Request::redirect('/cart');
            return;
        }
        $validatedData = $this->request->validated();
        $cartData = Session::get('cart', []);


        try {

            unset($cartData[$validatedData['product_id']]); //NOTE:: remove cart item
            Session::set('cart', $cartData);

        } catch (Exception $e) {
            consoleLog("Error removing cart item" . $e->getMessage());
            Session::set('error', $e->getMessage());
            Request::redirect('/cart');
            return;
        }

        Request::redirect('/cart');

    }
}
