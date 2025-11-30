@extends('layouts.master')
@section('title', 'Product Variants')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Product Variants</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="productvariants_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Product</th>
            <th>Size</th>
            <th>Color</th>
            <th>Material</th>
            <th>Price</th>
            <th>Stock</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($product_variants as $key => $pv)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $pv->product->name ?? '-' }}</td>
            <td>{{ $pv->size ?? '-' }}</td>
            <td>{{ $pv->color ?? '-' }}</td>
            <td>{{ $pv->material ?? '-' }}</td>
            <td>{{ $pv->price ?? '-' }}</td>
            <td>{{ $pv->stock ?? '-' }}</td>
            <td>

              @if($pv->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $pv->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $pv->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editProductVariant"
                data-id="{{ $pv->id }}" title="Edit" data-bs-toggle="tooltip">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $pv->id }}" title="Delete" data-bs-toggle="tooltip">
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
<div class="modal fade" id="productvariantModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Product Variant</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="productvariant_form">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">

          <label class="form-label">Product</label>
          <select id="product_id" name="product_id" class="form-control select2 mb-2">
            <option value="" disabled selected>Select Product</option>
            @foreach($products as $p)
            <option value="{{ $p->id }}">{{ ucfirst($p->name) }}</option>
            @endforeach
          </select>

          <label class="form-label">Size</label>
          <input type="text" id="size" name="size" class="form-control mb-2">

          <label class="form-label">Color</label>
          <input type="text" id="color" name="color" class="form-control mb-2">

          <label class="form-label">Material</label>
          <input type="text" id="material" name="material" class="form-control mb-2">

          <label class="form-label">Price</label>
          <input type="number" id="price" name="price" step="0.01" class="form-control mb-2">

          <label class="form-label">Stock</label>
          <input type="number" id="stock" name="stock" class="form-control mb-2">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitProductVariant">Save</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection



@section('plugin-script')

<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script>

$('.select2').select2({
    dropdownParent: $('#productvariantModal')
});

$('#productvariants_tbl').DataTable();

/* VALIDATION */
$("#productvariant_form").validate({
    rules: {
        product_id: { required: true },
        size: { required: true, productvariant_check: true },
        // keep slug rule from original removed (not needed here)
    },
    messages: {
        product_id: { required: "Please Select Product" },
        size: { required: "Please Enter Size" },
    },
   errorElement: "div",
    errorClass: "text-danger",

    errorPlacement: function(error, element) {

        if (element.hasClass("select2-hidden-accessible")) {
            error.insertAfter(element.next('.select2'));  
        } else {
            error.insertAfter(element);
        }
    },

    highlight: function (element) {
        if ($(element).hasClass("select2-hidden-accessible")) {
            $(element).next('.select2').find('.select2-selection').addClass("is-invalid");
        } else {
            $(element).addClass("is-invalid");
        }
    },
    unhighlight: function (element) {
        if ($(element).hasClass("select2-hidden-accessible")) {
            $(element).next('.select2').find('.select2-selection').removeClass("is-invalid");
        } else {
            $(element).removeClass("is-invalid");
        }
    }
});

/* Unique Check */
$.validator.addMethod(
    "productvariant_check",
    function(value) {
        var id = $("#id").val();
        var product = $("#product_id").val();
        var size = $("#size").val();
        var color = $("#color").val();
        var material = $("#material").val();

        var exist = $.ajax({
            url: "/productvariants/check-variant",
            type: "POST",
            async: false,
            data: { name: value, id: id, product_id: product, size: size, color: color, material: material },
        }).responseText;

        return exist != 1;
    },
    "Product Variant Already Exists"
);

/* Create */
$('#createNew').click(function() {
    $("#productvariant_form").validate().resetForm();
    $('#productvariant_form')[0].reset();

    $(".select2").val('').trigger('change');
    $('#id').val('');

    $('#productvariantModal select').each(function() {
        $(this).val(null).trigger('change');
    });

    new bootstrap.Modal(document.getElementById('productvariantModal')).show();
    $('#productvariantModal').on('shown.bs.modal', function () { $("#product_id").trigger('focus'); });

});

/* Save */
$('.submitProductVariant').click(function(e){
    e.preventDefault();

    if($("#productvariant_form").valid()){
        $.post('/productvariants/store', $("#productvariant_form").serialize(), function(data){
            const modal = bootstrap.Modal.getInstance(document.getElementById('productvariantModal'));
            modal.hide();
            toaster_message(data.message, data.icon);
        });
    }
});

/* Edit */
$(document).on('click', '.editProductVariant', function(){
    $.post('/productvariants/edit',
     { id: $(this).data('id'), _token: $('meta[name=\"csrf-token\"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#product_id').val(res.data.product_id).trigger('change');
            $('#size').val(res.data.size);
            $('#color').val(res.data.color);
            $('#material').val(res.data.material);
            $('#price').val(res.data.price);
            $('#stock').val(res.data.stock);
            new bootstrap.Modal(document.getElementById('productvariantModal')).show();
        }
    });
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
                url: (typeof aurl !== 'undefined' ? aurl : '') + "/productvariants/delete",
                type: "POST",
                dataType: "JSON",
                data: { id: id, _token: $('meta[name=\"csrf-token\"]').attr('content') },
                success: function (data) { toaster_message(data.message, data.icon); },
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
        url: '/productvariants/toggle-status',
        type: 'POST',
        data: { id: id, status: status, _token: $('meta[name=\"csrf-token\"]').attr('content') },
        success: function (response) {

            if (!response.success) { alert(response.message || 'Something went wrong'); return; }

            try { btn.tooltip('dispose'); } catch (e) {}

            if (status == 0) {
                btn.removeClass('btn-success').addClass('btn-danger').html('<i class=\"bx bx-hide\"></i>')
                   .attr('title', 'Click here to enable').data('status', 1);
            } else {
                btn.removeClass('btn-danger').addClass('btn-success').html('<i class=\"bx bx-show\"></i>')
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
