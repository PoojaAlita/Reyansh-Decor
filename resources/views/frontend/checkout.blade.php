@extends('layouts.user')

@section('content')
<section class="container" style="margin-top: 120px; margin-bottom: 100px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 42px; font-weight: 700;">Checkout</h1>
        <p style="color: var(--muted); font-size: 16px;">Complete your premium order</p>
    </div>

    <form id="checkoutForm" onsubmit="event.preventDefault(); simulatePayment();">
        <div style="display: flex; gap: 40px; flex-wrap: wrap;">
            
            <div style="flex: 2; min-width: 300px; display: flex; flex-direction: column; gap: 30px;">
                <!-- Billing Details -->
                <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px;">
                    <h3 style="font-size: 24px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Billing Details</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">First Name</label>
                            <input type="text" value="{{ Auth::user()->name ?? '' }}" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Last Name</label>
                            <input type="text" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Email Address</label>
                        <input type="email" value="{{ Auth::user()->email ?? '' }}" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Phone Number</label>
                        <input type="tel" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Street Address</label>
                        <input type="text" placeholder="House number and street name" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit; margin-bottom: 15px;">
                        <input type="text" placeholder="Apartment, suite, unit, etc. (optional)" style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">City</label>
                            <input type="text" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px;">PIN Code</label>
                            <input type="text" required style="width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px;">
                    <h3 style="font-size: 24px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Payment Method</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <label style="display: flex; align-items: center; padding: 20px; border: 1px solid #ddd; border-radius: 12px; cursor: pointer; transition: all 0.3s;" class="payment-option">
                            <input type="radio" name="payment_method" value="razorpay" required checked style="width: 20px; height: 20px; margin-right: 15px; accent-color: var(--accent);">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 16px;">Credit/Debit Card or UPI</div>
                                <div style="color: var(--muted); font-size: 13px; margin-top: 5px;">Secure payment via Razorpay</div>
                            </div>
                            <img src="https://razorpay.com/assets/razorpay-glyph.svg" style="height: 24px; opacity: 0.8;">
                        </label>
                        
                        <label style="display: flex; align-items: center; padding: 20px; border: 1px solid #ddd; border-radius: 12px; cursor: pointer; transition: all 0.3s;" class="payment-option">
                            <input type="radio" name="payment_method" value="cod" required style="width: 20px; height: 20px; margin-right: 15px; accent-color: var(--accent);">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 16px;">Cash on Delivery</div>
                                <div style="color: var(--muted); font-size: 13px; margin-top: 5px;">Pay when you receive your order</div>
                            </div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity: 0.5;"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div style="flex: 1; min-width: 300px;">
                <div style="background: var(--primary); color: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 40px; position: sticky; top: 100px;">
                    <h3 style="font-size: 24px; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; font-family: 'Playfair Display', serif;">Your Order</h3>
                    
                    <div style="margin-bottom: 30px; display: flex; flex-direction: column; gap: 15px;">
                        @php $subtotal = 0; @endphp
                        @foreach($carts as $cart)
                            @php 
                                $price = $cart->product->sale_price ?: $cart->product->price;
                                if($cart->variant) $price += $cart->variant->price;
                                $itemTotal = $price * $cart->quantity;
                                $subtotal += $itemTotal;
                            @endphp
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                                <div style="flex: 1; padding-right: 15px;">
                                    <div style="font-size: 14px; font-weight: 500; margin-bottom: 5px; opacity: 0.9;">{{ $cart->product->name }}</div>
                                    <div style="font-size: 12px; opacity: 0.6;">Qty: {{ $cart->quantity }} @if($cart->variant) • {{ $cart->variant->color }} @endif</div>
                                </div>
                                <div style="font-weight: 500; opacity: 0.9;">₹{{ number_format($itemTotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; opacity: 0.8;">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 15px; opacity: 0.8;">
                        <span>Shipping</span>
                        <span style="color: #4ade80;">Free</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 40px; font-size: 22px; font-weight: 700; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                        <span>Total</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <button type="submit" id="placeOrderBtn" style="width: 100%; padding: 20px; background: white; color: var(--primary); border: none; border-radius: 12px; font-weight: 700; font-family: 'Outfit', sans-serif; cursor: pointer; font-size: 16px; transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                        Place Order • ₹{{ number_format($subtotal, 2) }}
                    </button>
                    
                    <div style="text-align: center; margin-top: 20px; font-size: 12px; opacity: 0.5;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Secure 256-bit SSL Encryption
                    </div>
                </div>
            </div>

        </div>
    </form>
</section>
@endsection

@section('scripts')
<style>
    .payment-option:has(input:checked) {
        border-color: var(--accent) !important;
        background: rgba(245, 48, 3, 0.02);
    }
</style>
<script>
    function simulatePayment() {
        let btn = document.getElementById('placeOrderBtn');
        let originalText = btn.innerHTML;
        
        btn.innerHTML = '<span style="opacity: 0.7;">Processing Payment...</span>';
        btn.style.pointerEvents = 'none';
        
        // Simulate a delay for payment processing
        setTimeout(() => {
            Swal.fire({
                title: 'Order Placed Successfully!',
                text: 'Thank you for your premium purchase with Reyansh Decor.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
                confirmButtonText: 'View Orders',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('home') }}"; // Redirect to home or orders page
                }
            });
        }, 2000);
    }
</script>
@endsection
