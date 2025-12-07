@extends('layouts.master')
@section('title', 'Cart')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y" id="tableSection">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Cart's</h5>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary create-new" id="createNew">
          <i class="fas fa-plus me-1"></i> Add New
        </button>
      </div>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="cart_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
        @foreach (\App\Models\Cart::with(['product','variant'])->latest()->cursor() as $key => $c)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ optional($c->product)->name ?? '-' }}</td>
            <td>{{ optional($c->variant)->material ?? '-' }}</td>
            <td>{{ $c->quantity }}</td>
            <td>
              @if($c->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $c->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $c->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editCart"
                data-id="{{ $c->id }}" title="Edit" data-bs-toggle="tooltip">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $c->id }}" title="Delete" data-bs-toggle="tooltip">
                <i class="fas fa-trash-alt"></i>
              </button>

            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Cart Item</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="cart_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">

          <label class="form-label">Product</label>
          <select id="product_id" name="product_id" class="form-select mb-2 select2">
            <option value="" disabled selected>-- Select Product --</option>

            @foreach (\App\Models\Product::select(['id','name'])->latest('id')->cursor() as $p)
              <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
          </select>

          <label class="form-label">Variant</label>
          <select id="variant_id" name="variant_id" class="form-select mb-2 select2">
            <option value="">-- Select Variant (if any) --</option>
            {{-- populated via AJAX --}}
          </select>

          <label class="form-label">Quantity</label>
          <input type="number" id="quantity" name="quantity" class="form-control mb-2" min="1" value="1">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitCart">Save</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection


@section('plugin-script')

<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script>

$('#cart_tbl').DataTable();

$('.select2').select2({
    dropdownParent: $('#cartModal')
});

/* VALIDATION */
$("#cart_form").validate({
    rules: {
        product_id: { required: true },
        quantity: { required: true, min: 1 },
        // unique check will be added by custom method
    },
    messages: {
        product_id: { required: "Please Select Product" },
        quantity: { required: "Please enter quantity", min: "Minimum 1" }
    },
    errorElement: "div",
    errorClass: "text-danger mt-1",
    errorPlacement: function (error, element) { error.insertAfter(element); }
});

/* Unique Check: product + variant */
$.validator.addMethod(
    "cart_unique",
    function(value, element) {
        var id = $("#id").val();
        var product_id = $("#product_id").val();
        var variant_id = $("#variant_id").val();

        var exist = $.ajax({
            url: "/cart/check-unique",
            type: "POST",
            async: false,
            data: { product_id: product_id, variant_id: (variant_id ? variant_id : ''), id: id, _token: $('meta[name="csrf-token"]').attr('content') },
        }).responseText;

        // HomeVideo's check returned true if exists => JS expected exist != 1. We match that logic:
        return exist != 1;
    },
    "This product (and variant) already in cart"
);

/* Create */
$('#createNew').click(function() {
    $("#cart_form").validate().resetForm();
    $('#cart_form')[0].reset();

    $('#id').val('');
    $('#variant_id').empty().append('<option value="">-- Select Variant (if any) --</option>');

    new bootstrap.Modal(document.getElementById('cartModal')).show();
    $('#cartModal').on('shown.bs.modal', function () { $("#product_id").trigger('focus'); });
});

/* When product changes - fetch variants */
$('#product_id').change(function() {
    var product_id = $(this).val();
    $('#variant_id').empty().append('<option value="">Loading...</option>');
    $.post('/cart/get-variants', { product_id: product_id, _token: $('meta[name="csrf-token"]').attr('content') }, function(res) {
        $('#variant_id').empty().append('<option value="">-- Select Variant (if any) --</option>');
        if (res.success && res.variants.length) {
            res.variants.forEach(function(v) {
                    $('#variant_id').append(
                        `<option value="${v.id}">${v.material} - ${v.size} - ${v.color} (₹${v.price})</option>`
                    );
            });
        }
        // refresh select2
        $('#variant_id').trigger('change.select2');
    }, 'json').fail(function(){
        $('#variant_id').empty().append('<option value="">-- Select Variant (if any) --</option>');
    });
});

/* Save */
$('.submitCart').click(function(e){
    e.preventDefault();

    if($("#cart_form").valid()){
        // also run unique method check by forcing validation for custom rule
        // Attach rule on the fly
        $("#product_id").rules("add", { cart_unique: true });

        if (!$("#cart_form").valid()) return;

        var form = $('#cart_form')[0];
        var data = new FormData(form);

        $.ajax({
            url: '/cart/store',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(data){
                const modal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
                modal.hide();
                toaster_message(data.message, data.icon);
            },
            error: function (xhr) {
                toaster_message('Something went wrong!', 'error');
            }
        });
    }
});

/* Edit */
$(document).on('click', '.editCart', function(){
    $.post('/cart/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') },
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#quantity').val(res.data.quantity);
            $('#product_id').val(res.data.product_id).trigger('change'); // will load variants

            // After variants loaded via change -> we need to set variant value. Wait a bit or handle callback:
            setTimeout(function(){
                $('#variant_id').val(res.data.variant_id).trigger('change.select2');
            }, 400);

            new bootstrap.Modal(document.getElementById('cartModal')).show();
        }
    }, 'json');
});

/* Delete */
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: { confirmButton: 'btn btn-success', cancelButton: 'btn btn-danger me-2' },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: (typeof aurl !== 'undefined' ? aurl : '') + "/cart/delete",
                type: "POST",
                dataType: "JSON",
                data: { id: id, _token: $('meta[name=\"csrf-token\"]').attr('content') },
                success: function (data) { toaster_message(data.message, data.icon);},
                error: function () { swalWithBootstrapButtons.fire('Cancelled', 'this data is not available :)', 'error') }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire('Cancelled', 'Your data is safe :)', 'error')
        }
    });
});

/* Toggle Status */

$(document).on('click', '.toggle-status', function () {
    var btn = $(this);
    var id = btn.data('id');
    var status = btn.data('status');

    btn.tooltip('hide'); $(".tooltip").remove();

    $.ajax({
        url: '/cart/toggle-status',
        type: 'POST',
        data: { id: id, status: status, _token: $('meta[name=\"csrf-token\"]').attr('content') },
        success: function (response) {

            if (!response.success) { alert(response.message || 'Something went wrong'); return; }

            try { btn.tooltip('dispose'); } catch (e) {}

            if (status == 0) {
                btn.removeClass('btn-success').addClass('btn-danger').html('<i class="bx bx-hide"></i>')
                   .attr('title', 'Click here to enable').data('status', 1);
            } else {
                btn.removeClass('btn-danger').addClass('btn-success').html('<i class="bx bx-show"></i>')
                   .attr('title', 'Click here to disable').data('status', 0);
            }

            btn.tooltip({ container: 'body' });
            toaster_alert_action(response.message, response.icon);
        },
        error: function (xhr) { console.error(xhr); alert('Request failed. Check console for details.'); }
    });
});

</script>

@endsection
