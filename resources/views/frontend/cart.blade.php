@extends('layouts.user')

@section('content')
<section class="container" style="margin-top: 120px; margin-bottom: 100px; min-height: 50vh;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 42px; font-weight: 700;">Shopping Cart</h1>
        <p style="color: var(--muted); font-size: 16px;">Review your premium selections</p>
    </div>

    @if($carts->count() > 0)
    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- Cart Items -->
        <div style="flex: 2; min-width: 300px;">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden;">
                @php $subtotal = 0; @endphp
                @foreach($carts as $cart)
                    @php 
                        $price = $cart->product->sale_price ?: $cart->product->price;
                        if($cart->variant) {
                            $price += $cart->variant->price;
                        }
                        $itemTotal = $price * $cart->quantity;
                        $subtotal += $itemTotal;
                    @endphp
                    <div style="display: flex; gap: 20px; padding: 25px; border-bottom: 1px solid #eee; align-items: center;" id="cart-item-{{ $cart->id }}">
                        <img src="{{ asset('storage/' . $cart->product->main_image) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                        
                        <div style="flex: 1;">
                            <h3 style="font-size: 18px; margin: 0 0 5px; font-weight: 600;">{{ $cart->product->name }}</h3>
                            @if($cart->variant)
                                <p style="color: var(--muted); font-size: 14px; margin: 0 0 10px;">{{ $cart->variant->material }} - {{ $cart->variant->color }} ({{ $cart->variant->size }})</p>
                            @else
                                <p style="color: var(--muted); font-size: 14px; margin: 0 0 10px;">Standard</p>
                            @endif
                            <div style="font-weight: 700; color: var(--accent);">₹{{ number_format($price, 2) }}</div>
                        </div>

                        <div style="display: flex; align-items: center; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                            <button type="button" onclick="updateQty({{ $cart->id }}, -1, {{ $cart->product_id }}, {{ $cart->variant_id ? $cart->variant_id : 'null' }})" style="padding: 8px 12px; background: transparent; border: none; font-size: 16px; cursor: pointer;">-</button>
                            <input type="number" id="qty-{{ $cart->id }}" value="{{ $cart->quantity }}" readonly style="width: 40px; text-align: center; border: none; font-size: 14px; outline: none;">
                            <button type="button" onclick="updateQty({{ $cart->id }}, 1, {{ $cart->product_id }}, {{ $cart->variant_id ? $cart->variant_id : 'null' }})" style="padding: 8px 12px; background: transparent; border: none; font-size: 16px; cursor: pointer;">+</button>
                        </div>

                        <div style="font-weight: 700; font-size: 18px; width: 100px; text-align: right;">
                            ₹<span id="total-{{ $cart->id }}">{{ number_format($itemTotal, 2) }}</span>
                        </div>

                        <button onclick="removeCartItem({{ $cart->id }})" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 10px; opacity: 0.6; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Summary -->
        <div style="flex: 1; min-width: 300px;">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 30px; position: sticky; top: 100px;">
                <h3 style="font-size: 22px; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Order Summary</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px;">
                    <span style="color: var(--muted);">Subtotal</span>
                    <span style="font-weight: 600;">₹<span id="cart-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 16px;">
                    <span style="color: var(--muted);">Shipping</span>
                    <span style="font-weight: 600; color: #28a745;">Free</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 30px; font-size: 20px; font-weight: 700; border-top: 1px solid #eee; padding-top: 20px;">
                    <span>Total</span>
                    <span>₹<span id="cart-total">{{ number_format($subtotal, 2) }}</span></span>
                </div>

                <a href="{{ route('frontend.checkout') }}" style="display: block; width: 100%; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; font-size: 16px; transition: background 0.3s;" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">Proceed to Checkout</a>
            </div>
        </div>
    </div>
    @else
    <div style="text-align: center; padding: 50px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1" style="margin-bottom: 20px;">
            <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <h2 style="font-size: 24px; margin-bottom: 15px;">Your cart is empty</h2>
        <a href="{{ route('home') }}" style="display: inline-block; padding: 12px 30px; background: var(--primary); color: white; border-radius: 8px; text-decoration: none; font-weight: 500;">Continue Shopping</a>
    </div>
    @endif
</section>
@endsection

@section('scripts')
<script>
    function updateQty(cartId, change, productId, variantId) {
        let input = $('#qty-' + cartId);
        let current = parseInt(input.val());
        let newQty = current + change;
        
        if (newQty < 1) return;
        
        input.val(newQty);
        
        $.ajax({
            url: "{{ route('cart.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: cartId,
                quantity: newQty,
                product_id: productId,
                variant_id: variantId
            },
            success: function(res) {
                if(res.status) {
                    location.reload(); 
                }
            }
        });
    }

    function removeCartItem(cartId) {
        Swal.fire({
            title: 'Remove item?',
            text: "Do you want to remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: 'var(--muted)',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('cart.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: cartId
                    },
                    success: function(res) {
                        if(res.status) {
                            $('#cart-item-' + cartId).fadeOut(300, function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
