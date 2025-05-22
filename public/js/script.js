// Main JavaScript for Asthma Cure Landing Page

document.addEventListener('DOMContentLoaded', function() {
    // Review Slider Functionality
    const sliderTrack = document.querySelector('.slider-track');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    
    // Initialize slider
    function initSlider() {
        if (!sliderTrack || slides.length === 0) return;
        
        // Set up dots click events
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                goToSlide(index);
            });
        });
        
        // Auto-advance slides every 5 seconds
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            goToSlide(currentSlide);
        }, 5000);
    }
    
    // Go to specific slide
    function goToSlide(index) {
        if (!sliderTrack || slides.length === 0) return;
        
        // Update current slide index
        currentSlide = index;
        
        // Scroll to the slide
        const slideWidth = slides[0].offsetWidth;
        sliderTrack.scrollTo({
            left: slideWidth * index,
            behavior: 'smooth'
        });
        
        // Update active dot
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
    
    // Initialize slider
    initSlider();
    // Package selection handling
    const packageOptions = document.querySelectorAll('input[name="package"]');
    const priceDisplay = document.querySelector('.total-price');
    
    // Shipping method selection handling
    const shippingOptions = document.querySelectorAll('input[name="shipping_method"]');
    
    // Update price based on selections
    function updateTotalPrice() {
        let packagePrice = 0;
        let shippingCost = 0;
        let honeyPrice = 0;
        let packageName = '';
        
        // Get selected package price
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        if (selectedPackage) {
            switch (selectedPackage.value) {
                case '1month':
                    packagePrice = 1200;
                    packageName = '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স';
                    break;
                case '3month':
                    packagePrice = 2300;
                    packageName = '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স';
                    break;
                case '15days':
                    packagePrice = 1000;
                    packageName = '৫০ টি ট্যাবলেট | ১৫ দিনের কোর্স';
                    break;
            }
        }
        
        // Get selected shipping cost
        const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
        if (selectedShipping) {
            if (selectedShipping.value === 'inside_dhaka') {
                shippingCost = window.insideDhakaCost || 60; // Inside Dhaka shipping cost
            } else {
                shippingCost = window.outsideDhakaCost || 170; // Outside Dhaka shipping cost
            }
        } else {
            // Default to outside Dhaka shipping
            shippingCost = window.outsideDhakaCost || 170;
        }
        
        // Honey add-on functionality removed
        honeyPrice = 0;
        
        // Update price display if element exists
        if (priceDisplay) {
            const totalPrice = packagePrice + shippingCost + honeyPrice;
            priceDisplay.textContent = totalPrice.toFixed(2) + '৳';
        }
        
        // Update order summary
        updateOrderSummary(packageName, packagePrice, shippingCost, honeyPrice);
    }
    
    // Update the order summary section
    function updateOrderSummary(packageName, packagePrice, shippingCost, honeyPrice) {
        // Update product name and image
        const productNameElement = document.querySelector('.wcf-product-name');
        if (productNameElement) {
            productNameElement.textContent = packageName;
        }
        
        // Update product image alt text
        const productImageElement = document.querySelector('.wcf-product-thumbnail img');
        if (productImageElement) {
            productImageElement.alt = packageName;
        }
        
        // Update product total
        const productTotalElement = document.querySelector('.product-total .woocommerce-Price-amount');
        if (productTotalElement) {
            productTotalElement.innerHTML = packagePrice.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>';
        }
        
        // Calculate subtotal (package price + honey price if selected)
        const subtotal = packagePrice + honeyPrice;
        
        // Update subtotal
        const subtotalElement = document.querySelector('.cart-subtotal .woocommerce-Price-amount');
        if (subtotalElement) {
            subtotalElement.innerHTML = subtotal.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>';
        }
        
        // Update shipping cost text
        const shippingElement = document.querySelector('.cart-shipping td');
        if (shippingElement) {
            // Get the current selected shipping method
            const selectedShippingMethod = document.querySelector('input[name="shipping_method"]:checked');
            const shippingText = selectedShippingMethod && selectedShippingMethod.value === 'inside_dhaka' ? 'ঢাকা সিটি:' : 'ঢাকা সিটির বাহিরে:';
            shippingElement.innerHTML = shippingText + ' <span class="woocommerce-Price-amount amount">' + shippingCost.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span></span>';
        }
        
        // Update total (subtotal + shipping)
        const totalElement = document.querySelector('.order-total .woocommerce-Price-amount');
        if (totalElement) {
            const total = subtotal + shippingCost;
            totalElement.innerHTML = total.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>';
        }
        
        // Update the Confirm Order button price
        const confirmOrderButton = document.querySelector('#place_order');
        if (confirmOrderButton) {
            const total = subtotal + shippingCost;
            confirmOrderButton.innerHTML = 'Confirm Order&nbsp;&nbsp;' + total.toFixed(2) + '৳&nbsp;';
        }
    }
    
    // Add event listeners to update price when selections change
    packageOptions.forEach(option => {
        option.addEventListener('change', updateTotalPrice);
    });
    
    shippingOptions.forEach(option => {
        option.addEventListener('change', updateTotalPrice);
    });
    
    // Honey add-on checkbox event listener removed
    
    // Initialize price on page load
    updateTotalPrice();
    
    // Form validation
    const orderForm = document.querySelector('form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Get form fields
            const nameField = document.getElementById('name');
            const addressField = document.getElementById('address');
            const phoneField = document.getElementById('phone');
            
            // Validate name
            if (!nameField.value.trim()) {
                isValid = false;
                nameField.classList.add('is-invalid');
            } else {
                nameField.classList.remove('is-invalid');
            }
            
            // Validate address
            if (!addressField.value.trim()) {
                isValid = false;
                addressField.classList.add('is-invalid');
            } else {
                addressField.classList.remove('is-invalid');
            }
            
            // Validate phone
            if (!phoneField.value.trim() || !/^[0-9+\s-]{10,15}$/.test(phoneField.value.trim())) {
                isValid = false;
                phoneField.classList.add('is-invalid');
            } else {
                phoneField.classList.remove('is-invalid');
            }
            
            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
                
                // Scroll to the first invalid field
                const firstInvalidField = document.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
