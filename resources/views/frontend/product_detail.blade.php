@extends('layouts.user')

@section('content')
<section class="container" style="padding: 100px 0; min-height: 80vh;">
    <div style="display: flex; gap: 80px; flex-wrap: wrap; margin-bottom: 120px;">
        <!-- Product Images Gallery -->
        <div style="flex: 1.2; min-width: 450px;">
            <div style="position: sticky; top: 120px;">
                <div style="background: white; padding: 20px; border-radius: 35px; box-shadow: 0 30px 70px rgba(0,0,0,0.06); overflow: hidden;">
                    <div style="position: relative; border-radius: 25px; overflow: hidden; background: #fbfbfb;">
                         <img src="{{ str_starts_with($product->main_image, 'http') ? $product->main_image : asset('storage/' . $product->main_image) }}" style="width: 100%; border-radius: 25px; transition: transform 0.4s ease; mix-blend-mode: multiply;" id="mainProductImage">
                         <div style="position: absolute; top: 25px; left: 25px; display: flex; flex-direction: column; gap: 10px;">
                            <span style="background: var(--primary); color: white; padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Handcrafted</span>
                            <span style="background: var(--accent); color: white; padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Limited Edition</span>
                         </div>
                    </div>
                    
                    <div style="display: flex; gap: 20px; margin-top: 30px; overflow-x: auto; padding-bottom: 15px; scrollbar-width: none;" class="thumb-container">
                        <div style="min-width: 110px; height: 110px; border-radius: 20px; overflow: hidden; border: 3px solid var(--accent); cursor: pointer; transition: all 0.3s; background: #f9f9f9;" onclick="changeMainImage(this, '{{ str_starts_with($product->main_image, 'http') ? $product->main_image : asset('storage/' . $product->main_image) }}')">
                            <img src="{{ str_starts_with($product->main_image, 'http') ? $product->main_image : asset('storage/' . $product->main_image) }}" style="width: 100%; height: 100%; object-fit: cover; mix-blend-mode: multiply;">
                        </div>
                        @foreach($product->images as $img)
                            <div style="min-width: 110px; height: 110px; border-radius: 20px; overflow: hidden; border: 3px solid transparent; cursor: pointer; transition: all 0.3s; background: #f9f9f9;" onclick="changeMainImage(this, '{{ str_starts_with($img->image, 'http') ? $img->image : asset('storage/' . $img->image) }}')">
                                <img src="{{ str_starts_with($img->image, 'http') ? $img->image : asset('storage/' . $img->image) }}" style="width: 100%; height: 100%; object-fit: cover; mix-blend-mode: multiply;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div style="flex: 1; min-width: 380px;">
            <div style="padding-top: 20px;">
                <nav style="position: static; background: transparent; backdrop-filter: none; border: none; height: auto; margin-bottom: 25px; padding: 0;">
                    <a href="{{ route('home') }}" style="color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500;">Home</a>
                    <span style="color: #ddd; margin: 0 12px;">/</span>
                    <a href="{{ route('frontend.category', $product->category_id) }}" style="color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500;">{{ $product->category->name ?? 'Collection' }}</a>
                </nav>

                <h1 style="font-size: 56px; margin-bottom: 20px; font-weight: 900; line-height: 1.1; color: var(--primary);">{{ $product->name }}</h1>
                
                <div style="margin-bottom: 40px; display: flex; align-items: baseline; gap: 20px;">
                    @if($product->sale_price)
                        <span style="font-size: 42px; font-weight: 900; color: var(--accent);">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span style="font-size: 24px; color: #ccc; text-decoration: line-through; font-weight: 500;">₹{{ number_format($product->price, 2) }}</span>
                        <span style="background: #fff1f0; color: var(--accent); padding: 7px 18px; border-radius: 50px; font-size: 14px; font-weight: 800;">SAVE {{ round((($product->price - $product->sale_price)/$product->price)*100) }}%</span>
                    @else
                        <span style="font-size: 42px; font-weight: 900; color: var(--primary);">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Color/Size Selection -->
                <form id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    @if(isset($product->variants) && $product->variants->count() > 0)
                    <div style="margin-bottom: 40px;">
                        <h4 style="font-size: 13px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; font-weight: 800; color: var(--muted);">Select Configuration</h4>
                        <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                            @foreach($product->variants as $index => $variant)
                                <label style="display: flex; align-items: center; justify-content: space-between; padding: 20px 25px; border: 2px solid {{ $index == 0 ? 'var(--accent)' : '#f0f0f0' }}; border-radius: 18px; cursor: pointer; transition: all 0.3s; background: {{ $index == 0 ? '#fffdfd' : 'white' }};" class="variant-label">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}" {{ $index == 0 ? 'checked' : '' }} style="accent-color: var(--accent); width: 20px; height: 20px;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 16px;">{{ $variant->size }}</div>
                                            <div style="font-size: 13px; color: var(--muted);">{{ $variant->material }} • {{ $variant->color }}</div>
                                        </div>
                                    </div>
                                    <div style="font-weight: 800; font-size: 18px; color: {{ $index == 0 ? 'var(--accent)' : 'var(--primary)' }}">₹{{ number_format($variant->price, 2) }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quantity & Add to Cart -->
                    <div style="display: flex; gap: 20px; margin-bottom: 50px;">
                        <div style="flex: 0 0 150px;">
                             <div style="display: flex; align-items: center; background: #f5f5f5; border-radius: 50px; height: 65px; padding: 0 10px;">
                                <button type="button" onclick="document.getElementById('qty').stepDown()" style="width: 45px; height: 45px; border-radius: 50%; background: white; border: none; font-size: 20px; cursor: pointer; box-shadow: 0 5px 10px rgba(0,0,0,0.05);">-</button>
                                <input type="number" id="qty" name="quantity" value="1" min="1" max="99" style="flex: 1; text-align: center; border: none; background: transparent; font-size: 18px; font-weight: 700; outline: none;">
                                <button type="button" onclick="document.getElementById('qty').stepUp()" style="width: 45px; height: 45px; border-radius: 50%; background: white; border: none; font-size: 20px; cursor: pointer; box-shadow: 0 5px 10px rgba(0,0,0,0.05);">+</button>
                             </div>
                        </div>
                        <button type="submit" id="submitBtn" style="flex: 1; border: none; border-radius: 50px; background: var(--primary); color: white; height: 65px; font-size: 18px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: all 0.3s; box-shadow: 0 20px 40px rgba(0,0,0,0.12);" onmouseover="this.style.background='var(--accent)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">Add to Shopping Bag</button>
                    </div>
                </form>

                <!-- Information Tabs -->
                <div style="border-top: 1px solid #eee; padding-top: 50px;">
                    <div style="display: flex; gap: 40px; margin-bottom: 25px; border-bottom: 1px solid #f9f9f9;">
                        <button class="tab-btn active" onclick="openTab(event, 'description')" style="background:none; border:none; padding: 15px 0; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; border-bottom: 2px solid var(--accent); color: var(--primary);">Description</button>
                        <button class="tab-btn" onclick="openTab(event, 'shipping')" style="background:none; border:none; padding: 15px 0; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; color: var(--muted);">Shipping</button>
                    </div>
                    <div id="description" class="tab-content" style="display: block; animation: fadeIn 0.5s ease;">
                        <p style="color: var(--muted); line-height: 1.8; font-size: 16px;">{{ $product->description ?: 'This premium decorator piece is crafted from the finest materials to ensure a luxurious look and feel. Designed for modern living spaces that demand both style and durability.' }}</p>
                    </div>
                    <div id="shipping" class="tab-content" style="display: none; animation: fadeIn 0.5s ease;">
                        <p style="color: var(--muted); line-height: 1.8; font-size: 16px;">Free premium shipping on all orders over ₹2000. Delivered in eco-friendly packaging within 5-7 business days across India.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    @if($relatedProducts->count() > 0)
        <div style="margin-top: 150px;">
            <div style="text-align: center; margin-bottom: 70px;">
                <h2 style="font-size: 42px; font-weight: 900;">Complete the Collection</h2>
                <div style="width: 60px; height: 3px; background: var(--accent); margin: 20px auto;"></div>
            </div>
            <div class="product-grid" style="gap: 40px;">
                @foreach($relatedProducts as $rel)
                    <div class="product-card" style="background: white; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: none; overflow: hidden; transition: all 0.4s ease;">
                        <a href="{{ route('frontend.product', $rel->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="product-image" style="height: 380px; overflow: hidden; background: #f9f9f9;">
                                <img src="{{ str_starts_with($rel->main_image, 'http') ? $rel->main_image : asset('storage/' . $rel->main_image) }}" alt="{{ $rel->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; mix-blend-mode: multiply;">
                            </div>
                            <div class="product-info" style="padding: 25px;">
                                <h3 style="font-size: 17px; margin-bottom: 12px; font-weight: 700; height: 42px; overflow: hidden;">{{ $rel->name }}</h3>
                                <div style="font-size: 20px; color: var(--accent); font-weight: 800;">₹{{ number_format($rel->sale_price ?: $rel->price, 2) }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .thumb-container::-webkit-scrollbar { display: none; }
    .variant-label:hover { border-color: var(--accent) !important; transform: translateX(5px); }
    .product-card:hover { transform: translateY(-12px); box-shadow: 0 40px 80px rgba(0,0,0,0.08); }
    .product-card:hover .product-image img { transform: scale(1.1); }
</style>

@endsection

@section('scripts')
<script>
function changeMainImage(el, src) {
    document.getElementById('mainProductImage').src = src;
    const thumbs = document.querySelectorAll('.thumb-container > div');
    thumbs.forEach(t => t.style.borderColor = 'transparent');
    el.style.borderColor = 'var(--accent)';
}

function openTab(evt, tabName) {
    var i, tabContent, tabBtns;
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) tabContent[i].style.display = "none";
    tabBtns = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tabBtns.length; i++) {
        tabBtns[i].className = tabBtns[i].className.replace(" active", "");
        tabBtns[i].style.color = "var(--muted)";
        tabBtns[i].style.borderBottom = "none";
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    evt.currentTarget.style.color = "var(--primary)";
    evt.currentTarget.style.borderBottom = "2px solid var(--accent)";
}

$(document).ready(function() {
    $('.variant-label').on('click', function() {
        $('.variant-label').css({'border-color': '#f0f0f0', 'background': 'white'});
        $('.variant-label .product-price').css('color', 'var(--primary)');
        $(this).css({'border-color': 'var(--accent)', 'background': '#fffdfd'});
        $(this).find('.product-price').css('color', 'var(--accent)');
    });

    $('#addToCartForm').on('submit', function(e) {
        e.preventDefault();
        let submitBtn = $('#submitBtn');
        let originalText = submitBtn.text();
        submitBtn.html('<span class="spinner"></span> Processing...').prop('disabled', true);

        $.ajax({
            url: "{{ route('cart.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if(response.status) {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Elegantly added to your cart!',
                        showConfirmButton: false, timer: 3000, timerProgressBar: true,
                    });
                    let currentCount = parseInt($('#cartBadgeCount').text()) || 0;
                    let addedQty = parseInt($('#qty').val()) || 1;
                    updateCartCount(currentCount + addedQty);
                } else {
                    Swal.fire({ title: 'Notice', text: response.message, icon: 'info' });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Welcome', text: 'Please sign in to store your decor selections.',
                    icon: 'info', showCancelButton: true, confirmButtonText: 'Sign In',
                    confirmButtonColor: 'var(--primary)'
                }).then((r) => { if (r.isConfirmed) window.location.href = "{{ route('login') }}"; });
            },
            complete: function() { submitBtn.text(originalText).prop('disabled', false); }
        });
    });
});
</script>
<style>
    .spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection
