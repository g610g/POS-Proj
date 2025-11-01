<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\Views;
use App\models\Customer;
use App\models\DB;
use App\models\Order;
use App\models\Product;
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
        $totalPrice = array_reduce($cartData ?? [], function (float $carry, array $item) {
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

    public function checkout()
    {

        $dbConnection = (new DB())->getConnection();

        //NOTE::starts database transaction
        try {

            $dbConnection->beginTransaction();

            $totalOrderAmount = 0.0;
            $cartData = Session::get('cart');

            foreach ($cartData as $product_id => $cartItem) {
                $totalOrderAmount += $cartItem['total_amount'];
            }

            $stmtItem = $dbConnection->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, unit_price, sub_total)
                VALUES (:order_id, :product_id, :quantity, :unit_price, :sub_total)
            ");

            $productModel = new Product();
            $orderModel = new Order();

            $lastInsertId = $orderModel->createOrder(['customer_name' => 'Gio Gonzales', 'total_amount' => $totalOrderAmount], $dbConnection);//NOTE::creates a new order in the database


            foreach ($cartData as $productId => $cartItem) {
                $productData = $productModel->getSingleById($productId);
                //NOTE:: check availability of requested quantity
                if ($productData['stock'] < $cartItem['quantity']) {
                    $dbConnection->rollBack();
                    Session::set('error', "Product {$productData['product_name']} has less stock than requested quantity");
                    Request::redirect('/carts');
                    return;
                }
                $stmtItem->execute([
                    ':order_id' => $lastInsertId,
                    ':product_id' => $productId,
                    ':quantity' => $cartItem['quantity'],
                    ':unit_price' => $productData['price'],
                    ':sub_total' => $cartItem['total_amount'],
                ]);

                $productModel->updateProduct([
                    'edit_product_id' => $productId,
                    'edit_product_name' => $productData['product_name'],
                    'edit_stock' => $productData['stock'] - $cartItem['quantity'],
                    'edit_price' => $productData['price'],
                ], $productData, $dbConnection);
            }
            Session::unset('cart');
            $dbConnection->commit();
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            $dbConnection->rollBack();
            Request::redirect('/carts');
            return;
        }
        Request::redirect('/shop');
    }
}
