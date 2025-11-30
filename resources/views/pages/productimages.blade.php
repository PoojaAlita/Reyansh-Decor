@extends('layouts.master')
@section('title', 'Product Images')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Product Images</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="productimages_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Product</th>
            <th width="10%">Image</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($product_images as $key => $pi)
          <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $pi->product->name ?? '-' }}</td>
            <td>
              <img src="{{ asset('uploads/product_images/'.$pi->image) }}" width="60" height="60" style="object-fit:cover;">
            </td>
            <td>

              @if($pi->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $pi->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $pi->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editProductImage"
                data-id="{{ $pi->id }}" data-bs-toggle="tooltip" title="Edit">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $pi->id }}" data-bs-toggle="tooltip" title="Delete">
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
<div class="modal fade" id="productImageModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Product Image</h5>
        <button type="button" class="btn btn-danger p-0"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="productimage_form" enctype="multipart/form-data">
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

          <label class="form-label">Image</label>
          <input type="file" id="image" name="image" class="form-control mb-2">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitProductImage">Save</button>
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

$('.select2').select2({
    dropdownParent: $('#productImageModal')
});

$('#productimages_tbl').DataTable();

/* jQuery Validation */
$("#productimage_form").validate({
    rules: {
        product_id: {
            required: true
        },
        image: {
            required: function () {
                return $('#id').val() == "";  // Add mode → image required
            },
            extension: "jpg|jpeg|png|webp"
        }
    },
    messages: {
        product_id: {
            required: "Please select a product"
        },
        image: {
            required: "Please upload an image",
            extension: "Only JPG, JPEG, PNG, WEBP allowed"
        }
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



/* Create Modal */
$('#createNew').click(function() {
    $("#productimage_form").validate().resetForm();
    $('#productimage_form')[0].reset();
    $(".select2").val('').trigger('change');
    $('#productimage_form').find('.is-invalid').removeClass('is-invalid');
    $('#id').val('');

    new bootstrap.Modal(document.getElementById('productImageModal')).show();
});


/* Save */
$('.submitProductImage').click(function(e){
    e.preventDefault();

  if($("#productimage_form").valid()){
    var formData = new FormData($('#productimage_form')[0]);

    $.ajax({
        url: "/productimages/store",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            const modal = bootstrap.Modal.getInstance(document.getElementById('productImageModal'));
            modal.hide();
            toaster_message(data.message, data.icon);
        }
    });
  }
});


/* Edit */
$(document).on('click', '.editProductImage', function(){
    $.post('/productimages/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#product_id').val(res.data.product_id).trigger('change');
            new bootstrap.Modal(document.getElementById('productImageModal')).show();
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
                url: "/productimages/delete",
                type: "POST",
                dataType: "JSON",
                data: { id: id, _token: $('meta[name=\"csrf-token\"]').attr('content') },
                success: function (data) { toaster_message(data.message, data.icon); }
            });
        } else {
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
        url: '/productimages/toggle-status',
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
        }
    });
});

</script>

@endsection
