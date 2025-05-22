<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নিডাস - Prachin Bangla Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $config['assets_url']; ?>/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $config['assets_url']; ?>/css/order-style.css?v=<?php echo time(); ?>">
    
    <!-- Fallback CSS if the above doesn't load -->
    <style>
        /* Basic fallback styles to ensure content is readable if main CSS fails to load */
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .site-container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
        .header-section { text-align: center; padding: 20px 0; }
        .elementor-heading-title { font-size: 24px; margin-bottom: 20px; }
        .notification-box { background-color: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .pricing-item { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .price { color: #e63946; font-weight: bold; }
        .delivery-info { margin-top: 20px; font-weight: bold; }
        .order-button-wrapper { margin: 20px 0; }
        .order-more-button { display: inline-block; background-color: #EE5E11; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
    
    <?php if (!empty($meta_pixel_code)): ?>
    <!-- Meta Pixel Code -->
    <?php echo $meta_pixel_code; ?>
    <!-- End Meta Pixel Code -->
    <?php endif; ?>
</head>
<body>
    <div class="site-container full-width">
        <div class="header-section">
            <h2 class="elementor-heading-title elementor-size-default"><?php echo $main_heading ?? 'হার্ট, কিডনি, লিভার, স্কিন, চক্ষু — সুস্থ জীবনের প্রাকৃতিক টনিক <b style="color:#EE5E11">নিডাস</b>'; ?></h2>
            
            <div class="notification-box">
                <p class="notification-text"><?php echo $notification_text ?? 'নিয়মিত নিডাস খেলেই ইনশাআল্লাহ ইনফেকশন, কোলেস্টেরল, ডায়াবেটিস, ও গ্যাস্ট্রিক থেকে মুক্তি!'; ?></p>
            </div>
            
            <!-- Debug info -->
            <?php if(isset($button_text)): ?>
                <!-- <div style="background: yellow; padding: 5px;">Button text value: <?php echo htmlspecialchars($button_text); ?></div> -->
            <?php endif; ?>
            
            <div class="button-container">
                <a href="#testimonials" class="elementor-button elementor-size-sm" role="button">
                    <span class="elementor-button-text"><?php echo isset($button_text) ? $button_text : 'আমাদের সম্মানিত কাস্টমারদের মতামত'; ?></span>
                    <span class="elementor-button-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="circle-down-icon"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg>
                    </span>
                </a>
            </div>
        </div>

        <main class="site-main">
            <!-- Debug info -->
            <?php if(isset($youtube_url)): ?>
                <!-- <div style="background: yellow; padding: 5px;">YouTube URL value: <?php echo htmlspecialchars($youtube_url); ?></div> -->
            <?php endif; ?>
            
            <section class="youtube-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="video-container">
                                <iframe width="560" height="315" src="<?php echo isset($youtube_url) ? $youtube_url : 'https://www.youtube.com/embed/Eod9gvxhHuU'; ?>" title="১৮ বছরের শ্বাসকষ্টের যন্ত্রণায় মুক্তি—একটি গল্প যা বদলে দিয়েছে জীবনের মানে!" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
 

        <section id="testimonials" class="testimonials-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <h2 class="section-title">কাস্টমার রিভিউ</h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
                            <!-- Indicators -->
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
                                <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="5" aria-label="Slide 6"></button>
                            </div>
                            
                            <!-- Slides -->
                            <div class="carousel-inner">
                                <?php if (isset($customer_reviews) && is_array($customer_reviews)): ?>
                                    <?php foreach ($customer_reviews as $index => $review): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <div class="review-slide">
                                                <img src="<?php echo $config['base_url']; ?>/public/images/<?php echo htmlspecialchars($review['image']); ?>" alt="<?php echo htmlspecialchars($review['alt']); ?>" class="review-image">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Default reviews if none are set -->
                                    <div class="carousel-item active">
                                        <div class="review-slide">
                                            <img src="<?php echo $config['base_url']; ?>/public/images/CustomerReview1.png" alt="Customer Review 1" class="review-image">
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="review-slide">
                                            <img src="<?php echo $config['base_url']; ?>/public/images/CustomerReview2.jpg" alt="Customer Review 2" class="review-image">
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="review-slide">
                                            <img src="<?php echo $config['base_url']; ?>/public/images/CustomerReview3.jpg" alt="Customer Review 3" class="review-image">
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="review-slide">
                                            <img src="<?php echo $config['base_url']; ?>/public/images/CustomerReview4.jpg" alt="Customer Review 4" class="review-image">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                           <section class="product-section">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12 text-center">
                                    <div class="order-button-wrapper">
                                        <a href="#order" class="order-more-button">
                                            <span><?php echo $order_button_text ?? 'অর্ডার করতে চাই'; ?></span>
                                            <i class="fas fa-arrow-circle-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
            <section class="pricing-section" id="pricing">
            <div class="pricing-container">
                <div class="pricing-item">
                <?php echo $pricing_info['product1_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট ১ মাস ১৫ দিনের কোর্স='; ?> <span class="price"><?php echo $pricing_info['product1_price'] ?? 'অফারে মাত্র 1200/='; ?></span> টাকা
                </div>
                <div class="pricing-item">
                <?php echo $pricing_info['product2_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স ='; ?> <span class="price"><?php echo $pricing_info['product2_price'] ?? 'অফারে মাত্র 2300/='; ?></span> টাকা
                </div>
                <!-- <div class="pricing-item">
                    ১৫ দিনের জন্য ৫০ গ্রাম দৈনিক ২ বেলা = <span class="price">১০০০/=</span> টাকা
                </div> -->
                <div class="delivery-info">
                    <?php echo $pricing_info['delivery_info'] ?? 'সারা বাংলাদেশে সম্পূর্ণ ফ্রি ডেলিভারি!'; ?>
                </div>
                <div class="contact-info">
                    সরাসরি কথা বলতে ক্লিক করুন <a href="tel: <?php echo $pricing_info['phone_number'] ?? '880 1990-888222'; ?>"> <?php echo $pricing_info['phone_number'] ?? '880 1990-888222'; ?></a>
                </div>
                <div class="contact-info">
                WhatsApp : <?php echo $pricing_info['whatsapp_number'] ?? '880 1990-888222'; ?> <a href="https://wa.me/<?php echo str_replace(' ', '', $pricing_info['whatsapp_number'] ?? '01990888222'); ?>"> <?php echo $pricing_info['whatsapp_number'] ?? '880 1990-888222'; ?></a>
                </div>

            </div>
        </section>

        <section id="order" class="order-section">
            
            <div class="container">
                <div class="elementor-border-style">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default"><?php echo $order_form_info['order_form_heading'] ?? 'অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করে <span style="color:#EE5E11">Confirm Order</span> এ ক্লিক করুন:-'; ?></h2>
                        </div>

                        <form action="<?php echo $config['order_page_url']; ?>" method="POST">
                            <h3 style="font-size: 18px; margin-bottom: 15px; color: #000;"><?php echo $order_form_info['package_select_text'] ?? 'কোন প্যাকেজটি নিতে চান সিলেক্ট করুন :'; ?></h3>
                            <div class="wcf-qty-options">
                                <div class="wcf-qty-row wcf-highlight">
                                    <div class="ribbon">
                                        <span><?php echo $order_form_info['best_seller_label'] ?? 'সেরাবিক্রয়'; ?></span>
                                    </div>
                                    <div class="wcf-item">
                                        <div class="wcf-item-selector">
                                            <input type="radio" id="package1" name="package" value="1month" checked>
                                        </div>
                                        <div class="wcf-item-image">
                                            <img src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="<?php echo $order_form_info['product1_full_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স'; ?>">
                                        </div>
                                        <div class="wcf-item-content-options">
                                            <div class="wcf-item-wrap"><?php echo $order_form_info['product1_full_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স'; ?></div>
                                            <div class="wcf-display-price">
                                            <span style="text-decoration: line-through; color: #999; font-size: 16px;"><?php echo $order_form_info['product1_regular_price'] ?? '১৩৫০'; ?></span>
                                            <span style="color: #e63946; font-size: 20px; font-weight: bold; margin-left: 10px;"><?php echo $order_form_info['product1_sale_price'] ?? '১২০০'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="wcf-qty-row">
                                    <div class="wcf-item">
                                        <div class="wcf-item-selector">
                                            <input type="radio" id="package2" name="package" value="15days">
                                        </div>
                                        <div class="wcf-item-image">
                                            <img src="https://naturivabd.com/wp-content/uploads/2025/01/WhatsApp-Image-2024-03-04-at-17.17.50-300x300.jpeg" alt="আজমা কিউর (১৫ দিনের প্যাকেজ)">
                                        </div>
                                        <div class="wcf-item-content-options">
                                            <div class="wcf-item-wrap">আজমা কিউর (১৫ দিনের প্যাকেজ)</div>
                                            <div class="wcf-price">
                                                <div class="wcf-display-price">1,000.00৳</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="wcf-qty-row">
                                    <div class="ribbon economy">
                                        <span><?php echo $order_form_info['economy_label'] ?? 'সাশ্রয়ী'; ?></span>
                                    </div>
                                    <div class="wcf-item">
                                        <div class="wcf-item-selector">
                                            <input type="radio" id="package3" name="package" value="3month">
                                        </div>
                                        <div class="wcf-item-image">
                                            <img src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="<?php echo $order_form_info['product2_full_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স'; ?>">
                                        </div>
                                        <div class="wcf-item-content-options">
                                            <div class="wcf-item-wrap"><?php echo $order_form_info['product2_full_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স'; ?></div>
                                            <!-- <div class="wcf-item-subtext">৫০০ গ্রাম মধু ফ্রি</div> -->
                                            <div class="wcf-display-price">
                                            <span style="text-decoration: line-through; color: #999; font-size: 16px;"><?php echo $order_form_info['product2_regular_price'] ?? '২৭০০'; ?></span>
                                            <span style="color: #e63946; font-size: 20px; font-weight: bold; margin-left: 10px;"><?php echo $order_form_info['product2_sale_price'] ?? '২৩০০'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wcf-col2-set" id="customer_details">
                                <div class="wcf-col-1">
                                    <div class="woocommerce-billing-fields">
                                        <h3 id="billing_fields_heading"><?php echo $order_form_info['billing_details_heading'] ?? 'Billing details'; ?></h3>
                                        <div class="woocommerce-billing-fields__field-wrapper">
                                            <p class="form-row form-row-first wcf-column-100 validate-required" id="billing_first_name_field">
                                                <label for="billing_first_name" class="required_field"><?php echo $order_form_info['name_label'] ?? 'আপনার নাম'; ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                                <span class="woocommerce-input-wrapper">
                                                    <input type="text" class="input-text" name="name" id="billing_first_name" placeholder="এখানে আপনার নাম লিখুন" value="" required>
                                                </span>
                                            </p>
                                            
                                            <p class="form-row address-field wcf-column-100 validate-required form-row-wide" id="billing_address_1_field">
                                                <label for="billing_address_1" class="required_field"><?php echo $order_form_info['address_label'] ?? 'আপনার সম্পূর্ণ ঠিকানা'; ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                                <span class="woocommerce-input-wrapper">
                                                    <input type="text" class="input-text" name="address" id="billing_address_1" placeholder="এখানে আপনার সম্পূর্ণ ঠিকানা লিখুন" value="" required>
                                                </span>
                                            </p>
                                            
                                            <p class="form-row form-row-wide wcf-column-100 validate-required validate-phone" id="billing_phone_field">
                                                <label for="billing_phone" class="required_field"><?php echo $order_form_info['phone_label'] ?? 'আপনার মোবাইল নাম্বার'; ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                                <span class="woocommerce-input-wrapper">
                                                    <input type="tel" class="input-text" name="phone" id="billing_phone" placeholder="এখানে আপনার মোবাইল নাম্বার সঠিকভাবে লিখুন" value="" required>
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
                                <h3 class="wcf-shipping-methods-title"><?php echo $order_form_info['shipping_heading'] ?? 'Shipping'; ?></h3>
                                <div class="wcf-shipping-method-options">
                                    <ul id="shipping_method" class="woocommerce-shipping-methods">
                                        <li>
                                            <input type="radio" name="shipping_method" id="wcf_shipping_method_0_flat_rate2" value="outside_dhaka" checked>
                                            <label for="wcf_shipping_method_0_flat_rate2"><?php echo $order_form_info['outside_dhaka_label'] ?? 'ঢাকা সিটির বাহিরে'; ?>: <span class="woocommerce-Price-amount amount"><?php echo $order_form_info['outside_dhaka_cost'] ?? '0.00'; ?><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></label>
                                        </li>
                                        <li>
                                            <input type="radio" name="shipping_method" id="wcf_shipping_method_0_flat_rate3" value="inside_dhaka">
                                            <label for="wcf_shipping_method_0_flat_rate3"><?php echo $order_form_info['inside_dhaka_label'] ?? 'ঢাকা সিটিতে'; ?>: <span class="woocommerce-Price-amount amount"><?php echo $order_form_info['inside_dhaka_cost'] ?? '0.00'; ?><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="wcf-order-wrap">
                                <h3 id="order_review_heading"><?php echo $order_form_info['order_summary_heading'] ?? 'Your order'; ?></h3>
                                <div id="order_review" class="woocommerce-checkout-review-order">
                                    <table class="shop_table woocommerce-checkout-review-order-table cartflows_table">
                                        <thead>
                                            <tr>
                                                <th class="product-name"><?php echo $order_form_info['product_column_heading'] ?? 'Product'; ?></th>
                                                <th class="product-total"><?php echo $order_form_info['subtotal_column_heading'] ?? 'Subtotal'; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cart_item">
                                                <td class="product-name">
                                                    <div class="wcf-product-image">
                                                        <div class="wcf-product-thumbnail">
                                                            <img width="60" height="60" src="<?php echo $config['assets_url']; ?>/images/product.webp" alt="১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স">
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
                                                <td>ঢাকা সিটির বাহিরে: <span class="woocommerce-Price-amount amount">0.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Total</th>
                                                <td><strong><span class="woocommerce-Price-amount amount">1,200.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div class="wcf-bump-order-grid-wrap wcf-all-bump-order-wrap wcf-after-order">
                                        <!-- <div class="wcf-bump-order-wrap wcf-bump-order-style-1 wcf-after-order wcf-ob-column-100">
                                            <div class="wcf-bump-order-field-wrap">
                                                <label>
                                                    <input type="checkbox" id="wcf-bump-order-cb" class="wcf-bump-order-cb" name="honey_addon" value="1">
                                                    <span class="wcf-bump-order-label">মধু সহ নিতে চাই</span>
                                                </label>
                                            </div>
                                            <div class="honey-offer-container">
                                                <div class="honey-image-container">
                                                    <img src="https://naturivabd.com/wp-content/uploads/2025/02/Mustard-Honey.png" class="honey-image" alt="মধু সহ">
                                                </div>
                                                <div class="honey-content">
                                                    <div class="wcf-bump-order-offer">
                                                        <span class="wcf-bump-order-bump-highlight">One Time Offer</span>
                                                    </div>
                                                    <div class="wcf-bump-order-desc">
                                                        দ্রুত ফলাফল পেতে মধুর সাথে মিক্স করে খেতে পারেন।<br>
                                                        অ্যাজমা কিউর এর সাথে মধু অর্ডার করলে ১ কেজি লিচু ফুলের মধু পাবেন মাত্র <span class="wcf-normal-price">500.00<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span> টাকায়।
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>

                                    <div id="payment" class="woocommerce-checkout-payment">
                                        <ul class="wc_payment_methods payment_methods methods">
                                            <li class="wc_payment_method payment_method_cod">
                                                <input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" checked="checked" style="display: none;">
                                                <label for="payment_method_cod">
                                                    ক্যাশ অন ডেলিভারি
                                                </label>
                                                <div class="payment_box payment_method_cod">
                                                    <p>আমি অবশ্যই পণ্যটি রিসিভ করবো, পণ্যটি হাতে পেয়ে টাকা পরিশোধ করবো, ইনশাআল্লাহ</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="form-row place-order">
                                            <button type="submit" class="button alt" id="place_order" value="Confirm Order">Confirm Order&nbsp;&nbsp;1,200.00৳&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="elementor-widget-container" style="margin-top: 20px;">
                            <h2 class="elementor-heading-title elementor-size-default"><?php echo $order_form_info['order_confirmation_text'] ?? 'অর্ডার কন্ফার্ম করতে Confirm Order এ ক্লিক করুন'; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-3 text-center">
            <div class="footer-copyright">
                <p>&copy; <?php echo date('Y'); ?> 
                    <a href="<?php echo $settings['footer_link'] ?? 'https://prachinebangla.com/'; ?>" target="_blank" style="text-decoration: none; color: inherit;">
                        <?php echo $settings['footer_copyright'] ?? 'Prachin Bangla Limited'; ?>
                    </a>
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $config['assets_url']; ?>/js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
