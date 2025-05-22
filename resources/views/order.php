<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাজমা কিউর - অর্ডার করুন</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $config['assets_url']; ?>/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $config['assets_url']; ?>/css/order-style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="whatsapp-banner">
        WhatsApp : <a href="https://wa.me/01608233898">01608233898</a> / <a href="https://wa.me/01314604771">01314604771</a>
    </div>
    <div class="container">
        <div class="elementor-border-style">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-widget-container">
                    <h2 class="elementor-heading-title elementor-size-default"><?php echo $order_form_info['order_form_heading'] ?? 'অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করে <span style="color:#EE5E11">Confirm Order</span> এ ক্লিক করুন:-'; ?></h2>
                </div>

                <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="/order" method="POST">
                    <h3 style="font-size: 18px; margin-bottom: 15px; color: #000;"><?php echo $order_form_info['package_select_text'] ?? 'কোন প্যাকেজটি নিতে চান সিলেক্ট করুন :'; ?></h3>
                    <div class="wcf-qty-options">
                        <div class="wcf-qty-row wcf-highlight">
                            <div class="ribbon">
                                <span><?php echo $order_form_info['best_seller_label'] ?? 'সেরাবিক্রয়'; ?></span>
                            </div>
                            <div class="wcf-item">
                                <div class="wcf-item-selector">
                                    <input type="radio" id="package1" name="package" value="1month" <?php echo (isset($data['package']) && $data['package'] === '1month') || !isset($data['package']) ? 'checked' : ''; ?>>
                                </div>
                                <div class="wcf-item-image">
                                    <img src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="নিডাস (১ মাসের প্যাকেজ)">
                                </div>
                                <div class="wcf-item-content-options">
                                    <div class="wcf-item-wrap">আজমা কিউর (১ মাসের প্যাকেজ)</div>
                                    <div class="wcf-price">
                                        <div class="wcf-display-price">1,800.00৳</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wcf-qty-row">
                            <div class="wcf-item">
                                <div class="wcf-item-selector">
                                    <input type="radio" id="package2" name="package" value="15days" <?php echo (isset($data['package']) && $data['package'] === '15days') ? 'checked' : ''; ?>>
                                </div>
                                <div class="wcf-item-image">
                                    <img src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="নিডাস (১৫ দিনের প্যাকেজ)">
                                </div>
                                <div class="wcf-item-content-options">
                                    <div class="wcf-item-wrap">আজমা কিউর (১৫ দিনের প্যাকেজ)</div>
                                    <div class="wcf-price">
                                        <div class="wcf-display-price">1,000.00৳</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wcf-qty-row">
                            <div class="ribbon economy">
                                <span>সাশ্রয়ী</span>
                            </div>
                            <div class="wcf-item">
                                <div class="wcf-item-selector">
                                    <input type="radio" id="package3" name="package" value="3month" <?php echo (isset($data['package']) && $data['package'] === '3month') ? 'checked' : ''; ?>>
                                </div>
                                <div class="wcf-item-image">
                                    <img src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="নিডাস (৩ মাসের প্যাকেজ)">
                                </div>
                                <div class="wcf-item-content-options">
                                    <div class="wcf-item-wrap">আজমা কিউর (৩ মাসের প্যাকেজ)</div>
                                    <div class="wcf-item-subtext">৫০০ গ্রাম মধু ফ্রি</div>
                                    <div class="wcf-price">
                                        <div class="wcf-display-price">5,000.00৳</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wcf-col2-set" id="customer_details">
                        <div class="wcf-col-1">
                            <div class="woocommerce-billing-fields">
                                <h3 id="billing_fields_heading">Billing details</h3>
                                <div class="woocommerce-billing-fields__field-wrapper">
                                    <p class="form-row form-row-first wcf-column-100 validate-required" id="billing_first_name_field">
                                        <label for="billing_first_name" class="required_field">আপনার নাম&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                        <span class="woocommerce-input-wrapper">
                                            <input type="text" class="input-text" name="name" id="billing_first_name" placeholder="এখানে আপনার নাম লিখুন" value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>" required>
                                        </span>
                                    </p>
                                    
                                    <p class="form-row address-field wcf-column-100 validate-required form-row-wide" id="billing_address_1_field">
                                        <label for="billing_address_1" class="required_field">আপনার সম্পূর্ণ ঠিকানা&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                        <span class="woocommerce-input-wrapper">
                                            <input type="text" class="input-text" name="address" id="billing_address_1" placeholder="এখানে আপনার সম্পূর্ণ ঠিকানা লিখুন" value="<?php echo isset($data['address']) ? htmlspecialchars($data['address']) : ''; ?>" required>
                                        </span>
                                    </p>
                                    
                                    <p class="form-row form-row-wide wcf-column-100 validate-required validate-phone" id="billing_phone_field">
                                        <label for="billing_phone" class="required_field">আপনার মোবাইল নাম্বার&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                        <span class="woocommerce-input-wrapper">
                                            <input type="tel" class="input-text" name="phone" id="billing_phone" placeholder="এখানে আপনার মোবাইল নাম্বার সঠিকভাবে লিখুন" value="<?php echo isset($data['phone']) ? htmlspecialchars($data['phone']) : ''; ?>" required>
                                        </span>
                                    </p>
                                    
                                    <p class="form-row form-row-wide address-field update_totals_on_change wcf-column-100" id="billing_country_field">
                                        <label for="billing_country">Country / Region&nbsp;<span class="optional">(optional)</span></label>
                                        <span class="woocommerce-input-wrapper">
                                            <select name="country" id="billing_country" class="country_select">
                                                <option value="Bangladesh" selected>Bangladesh</option>
                                            </select>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="wcf-shipping-methods-wrapper">
                        <h3 class="wcf-shipping-methods-title">Shipping</h3>
                        <div class="wcf-shipping-method-options">
                            <ul id="shipping_method" class="woocommerce-shipping-methods">
                                <li>
                                    <input type="radio" name="shipping_method" id="wcf_shipping_method_0_flat_rate2" value="outside_dhaka" <?php echo (!isset($data['shipping_method']) || (isset($data['shipping_method']) && $data['shipping_method'] === 'outside_dhaka')) ? 'checked' : ''; ?>>
                                    <label for="wcf_shipping_method_0_flat_rate2">ঢাকা সিটির বাহিরে: <span class="woocommerce-Price-amount amount">170.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></label>
                                </li>
                                <li>
                                    <input type="radio" name="shipping_method" id="wcf_shipping_method_0_flat_rate3" value="inside_dhaka" <?php echo (isset($data['shipping_method']) && $data['shipping_method'] === 'inside_dhaka') ? 'checked' : ''; ?>>
                                    <label for="wcf_shipping_method_0_flat_rate3">ঢাকা সিটিতে: <span class="woocommerce-Price-amount amount">60.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="wcf-order-wrap">
                        <h3 id="order_review_heading">Your order</h3>
                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <table class="shop_table woocommerce-checkout-review-order-table cartflows_table">
                                <thead>
                                    <tr>
                                        <th class="product-name">Product</th>
                                        <th class="product-total">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="cart_item">
                                        <td class="product-name">
                                            <div class="wcf-product-image">
                                                <div class="wcf-product-thumbnail">
                                                    <img width="60" height="60" src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="নিডাস (১ মাসের প্যাকেজ)">
                                                </div>
                                                <div class="wcf-product-name">১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স</div>
                                            </div>&nbsp;
                                            <strong class="product-quantity">×&nbsp;1</strong>
                                        </td>
                                        <td class="product-total">
                                            <span class="woocommerce-Price-amount amount">1,200.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td><span class="woocommerce-Price-amount amount">1,200.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></td>
                                    </tr>
                                    <tr class="cart-shipping">
                                        <th>Shipping</th>
                                        <td id="shipping-cost-display">
                                            <?php if (isset($data['shipping_method']) && $data['shipping_method'] === 'inside_dhaka'): ?>
                                                ঢাকা সিটিতে: <span class="woocommerce-Price-amount amount">60.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span>
                                            <?php else: ?>
                                                ঢাকা সিটির বাহিরে: <span class="woocommerce-Price-amount amount">170.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td id="total-amount"><strong><span class="woocommerce-Price-amount amount">
                                            <?php
                                                $productPrice = 1200.00;
                                                $insideDhakaCost = floatval($order_form_info['inside_dhaka_cost'] ?? '60.00');
                                                $outsideDhakaCost = floatval($order_form_info['outside_dhaka_cost'] ?? '170.00');
                                                $shippingCost = (isset($data['shipping_method']) && $data['shipping_method'] === 'inside_dhaka') ? $insideDhakaCost : $outsideDhakaCost;
                                                $totalAmount = $productPrice + $shippingCost;
                                                echo number_format($totalAmount, 2);
                                            ?>
                                            <span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="wcf-bump-order-grid-wrap wcf-all-bump-order-wrap wcf-after-order">
                                <div class="wcf-bump-order-wrap wcf-bump-order-style-1 wcf-after-order wcf-ob-column-100">
                                    <div class="wcf-bump-order-content wcf-bump-order-image-left">
                                        <div class="wcf-bump-order-field-wrap">
                                            <label>
                                                <input type="checkbox" id="wcf-bump-order-cb" class="wcf-bump-order-cb" name="honey_addon" value="1">
                                                <span class="wcf-bump-order-label">মধু সহ নিতে চাই</span>
                                            </label>
                                        </div>
                                        <div class="wcf-content-container">
                                            <div class="wcf-bump-order-offer-content-left wcf-bump-order-image">
                                                <img src="images/honey.png" class="wcf-image" alt="মধু">
                                            </div>
                                            <div class="wcf-bump-order-offer-content-right">
                                                <div class="wcf-bump-order-offer">
                                                    <span class="wcf-bump-order-bump-highlight">One Time Offer</span>
                                                </div>
                                                <div class="wcf-bump-order-desc">
                                                    দ্রুত ফলাফল পেতে মধুর সাথে মিক্স করে খেতে পারেন। অ্যাজমা কিউর এর সাথে মধু অর্ডার করলে ১ কেজি লিচু ফুলের মধু পাবেন মাত্র <span class="wcf-normal-price">500.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span> টাকায়।
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="payment" class="woocommerce-checkout-payment">
                                <ul class="wc_payment_methods payment_methods methods">
                                    <li class="wc_payment_method payment_method_cod">
                                        <input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" checked="checked" style="display: none;">
                                        <label for="payment_method_cod">
                                            <?php echo $order_form_info['cod_label'] ?? 'ক্যাশ অন ডেলিভারি'; ?>
                                        </label>
                                        <div class="payment_box payment_method_cod">
                                            <p><?php echo $order_form_info['cod_description'] ?? 'আমি অবশ্যই পণ্যটি রিসিভ করবো, পণ্যটি হাতে পেয়ে টাকা পরিশোধ করবো, ইনশাআল্লাহ'; ?></p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-row place-order">
                                    <button type="submit" class="button alt confirm-order-button" id="place_order" value="<?php echo $order_form_info['confirm_order_button_text'] ?? 'Confirm Order'; ?>"><?php echo $order_form_info['confirm_order_button_text'] ?? 'Confirm Order'; ?>&nbsp;&nbsp;1,370.00৳&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="elementor-widget-container" style="margin-top: 20px;">
                    <h2 class="elementor-heading-title elementor-size-default">অর্ডার কন্ফার্ম করতে Confirm Order এ ক্লিক করুন</h2>
                </div>
            </div>
        </div>

        <footer class="py-3 text-center">
            <p>&copy; <?php echo date('Y'); ?> অ্যাজমা কিউর - সর্বস্বত্ব সংরক্ষিত</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $config['assets_url']; ?>/js/script.js?v=<?php echo time(); ?>"></script>
    <script>
        // Function to update shipping cost and total amount based on selected shipping method
        function updateShippingAndTotal() {
            const insideDhakaRadio = document.getElementById('wcf_shipping_method_0_flat_rate3');
            const outsideDhakaRadio = document.getElementById('wcf_shipping_method_0_flat_rate2');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const totalAmountDisplay = document.getElementById('total-amount').querySelector('span.woocommerce-Price-amount');
            
            const productPrice = 1200.00;
            let shippingCost = 170.00; // Default to outside Dhaka
            let shippingText = 'ঢাকা সিটির বাহিরে: ';
            
            if (insideDhakaRadio && insideDhakaRadio.checked) {
                shippingCost = 60.00;
                shippingText = 'ঢাকা সিটিতে: ';
            }
            
            const totalAmount = productPrice + shippingCost;
            
            // Update shipping cost display
            if (shippingCostDisplay) {
                shippingCostDisplay.innerHTML = shippingText + '<span class="woocommerce-Price-amount amount">' + shippingCost.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span>';
            }
            
            // Update total amount display
            if (totalAmountDisplay) {
                totalAmountDisplay.innerHTML = totalAmount.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>';
            }
            
            // Update the confirm order button text
            const confirmOrderButton = document.querySelector('.confirm-order-button');
            if (confirmOrderButton) {
                confirmOrderButton.innerHTML = 'Confirm Order ' + totalAmount.toFixed(2) + '৳';
            }
        }
        
        // Add event listeners to shipping method radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const insideDhakaRadio = document.getElementById('wcf_shipping_method_0_flat_rate3');
            const outsideDhakaRadio = document.getElementById('wcf_shipping_method_0_flat_rate2');
            
            if (insideDhakaRadio) {
                insideDhakaRadio.addEventListener('change', updateShippingAndTotal);
            }
            
            if (outsideDhakaRadio) {
                outsideDhakaRadio.addEventListener('change', updateShippingAndTotal);
            }
            
            // Initial update
            updateShippingAndTotal();
        });
    </script>
</body>
</html>
