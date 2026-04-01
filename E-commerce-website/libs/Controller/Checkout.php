<?php

class Controller_Checkout extends Controller_Abstract
{
    public function execute()
    {
        // Login required for order placement
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = ['text' => 'Please login to complete your order.', 'type' => 'info'];
            $this->redirect('login');
        }

        require_once ROOT_PATH . '/src/OrderDAO.php';
        require_once ROOT_PATH . '/data.php';

        // Get cart data
        $cartItems = $_SESSION['cart'] ?? [];

        // Redirect if cart is empty
        if (empty($cartItems)) {
            $this->redirect('cart');
        }

        // Subtotal calculation and rule logic
        $subtotal = 0;
        $hasFreight = false;
        $expressTotal = 0;

        // Load products for current cart items
        $db = Core_Connection::getInstance();
        $productIds = array_keys($cartItems);
        $products = [];
        if ($productIds) {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $db->prepare("SELECT * FROM catalog_product_entity WHERE entity_id IN ($placeholders)");
            $stmt->execute($productIds);
            while ($row = $stmt->fetch()) {
                $products[$row['entity_id']] = $row;
            }
        }

        foreach ($cartItems as $item) {
            $p = $products[$item['product_id']] ?? null;
            if ($p) {
                $subtotal += ($p['price'] * $item['quantity']);
                if ($p['shipping_type'] == 1)
                    $hasFreight = true;
                else
                    $expressTotal += ($p['price'] * $item['quantity']);
            }
        }

        // Shipping costs configuration
        $shippingCosts = [
            'standard' => calculate_shipping_cost('standard', $subtotal),
            'express' => calculate_shipping_cost('express', $subtotal),
            'white_glove' => calculate_shipping_cost('white_glove', $subtotal),
            'freight' => calculate_shipping_cost('freight', $subtotal)
        ];

        // Shipping restrictions
        $disabledMethods = ($hasFreight || $expressTotal > 300) ? ['standard', 'express'] : ['white_glove', 'freight'];

        $selectedMethod = $this->getRequest('shipping') ?: ($_SESSION['shipping_method'] ?: (in_array('standard', $disabledMethods) ? 'freight' : 'standard'));
        $_SESSION['shipping_method'] = $selectedMethod;

        // Calculate current shipping cost
        $currentShippingCost = $shippingCosts[$selectedMethod] ?? 350;

        // Coupon logic
        $validCoupons = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15];
        $couponCode = $_SESSION['coupon']['code'] ?? '';
        $couponPercent = $_SESSION['coupon']['percent'] ?? 0;
        $couponDiscount = 0;
        if ($couponCode && isset($validCoupons[$couponCode])) {
            $couponPercent = $validCoupons[$couponCode];
            $couponDiscount = (int) round($subtotal * ($couponPercent / 100));
        }

        // Tax calculation
        $tax = calculate_tax($subtotal - $couponDiscount);
        $finalTotal = ($subtotal - $couponDiscount) + $currentShippingCost + $tax;

        // Handle POST actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->getRequest('apply_coupon') !== null) {
                $appliedCode = strtoupper(trim((string) $this->getRequest('coupon')));
                if (isset($validCoupons[$appliedCode])) {
                    $_SESSION['coupon'] = ['code' => $appliedCode, 'percent' => $validCoupons[$appliedCode]];
                    $_SESSION['flash_message'] = ['text' => "Coupon applied!", 'type' => 'success'];
                } else {
                    unset($_SESSION['coupon']);
                    $_SESSION['flash_message'] = ['text' => "Invalid coupon", 'type' => 'error'];
                }
                $this->redirect('checkout');
            } elseif ($this->getRequest('place_order') !== null) {
                // Place order
                $orderDAO = new OrderDAO();
                $orderData = [
                    'user_id' => $_SESSION['user_id'] ?? null,
                    'cart_id' => $_SESSION['cart_id'] ?? null,
                    'order_number' => 'ORD-' . strtoupper(substr(uniqid('', true), -6)),
                    'subtotal' => (int) round($subtotal),
                    'shipping_type' => $selectedMethod,
                    'shipping_cost' => $currentShippingCost,
                    'tax' => $tax,
                    'final_amount' => $finalTotal,
                    'shipping_name' => ($this->getRequest('firstName') ?: '') . ' ' . ($this->getRequest('lastName') ?: ''),
                    'shipping_email' => $this->getRequest('email') ?: '',
                    'shipping_phone'   => $this->getRequest('phone') ?: '',
                    'shipping_address' => ($this->getRequest('street') ?: '') . ', ' . ($this->getRequest('city') ?: '') . ', ' . ($this->getRequest('state') ?: '') . ', ' . ($this->getRequest('zip') ?: '') . ', ' . ($this->getRequest('country') ?: ''),
                    'items'            => array_values($cartItems),
                    'payment_method'   => $this->getRequest('payment') ?: 'COD',
                    'payment_status'   => ($this->getRequest('payment') === 'cod') ? 'Pending' : 'Paid',
                    'transaction_id'   => $this->getRequest('transaction_id') ?: ('TXN-' . strtoupper(substr(uniqid(), -8))),
                    'razorpay_order_id'=> $this->getRequest('razorpay_order_id') ?: ('RZP-' . strtoupper(substr(uniqid(), -8)))
                ];

                try {
                    if ($orderDAO->createOrder($orderData)) {
                        unset($_SESSION['cart'], $_SESSION['cart_id'], $_SESSION['coupon']);
                        $_SESSION['flash_message'] = ['text' => 'Order placed successfully!', 'type' => 'success'];
                        $this->redirect('my-orders');
                    }
                } catch (Exception $e) {
                    $_SESSION['flash_message'] = ['text' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
                }
            }
        }

        $view = new View_Default();
        $view->setTemplate('checkout/main');
        $view->page_title = 'Secure Checkout - EasyCart';
        $view->page_css = 'checkout.css';

        // Pass data to view
        $view->cartItems = $cartItems;
        $view->subtotal = $subtotal;
        $view->tax = $tax;
        $view->shippingCost = $currentShippingCost;
        $view->couponDiscount = $couponDiscount;
        $view->couponCode = $couponCode;
        $view->couponPercent = $couponPercent;
        $view->disabledMethods = $disabledMethods;
        $view->selectedMethod = $selectedMethod;
        $view->shippingCosts = $shippingCosts;
        $view->total = $finalTotal;

        echo $view->toHtml();
    }
}
