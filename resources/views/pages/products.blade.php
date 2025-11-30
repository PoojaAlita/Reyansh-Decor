@extends('layouts.master')
@section('title', 'Products')

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
      <h5 class="card-title mb-0">Products</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="product_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Image</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Child Category</th>
            <th>Name</th>
            <th>SKU</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($products as $key => $p)
          <tr>
            <td>{{ $key + 1 }}</td>

            <td>
              @if($p->main_image)
                <img src="{{ asset('products/'.$p->main_image) }}" width="50" height="50" class="rounded">
              @else
                -
              @endif
            </td>

            <td>{{ $p->category->name ?? '-' }}</td>
            <td>{{ $p->subcategory->subcat_name ?? '-' }}</td>
            <td>{{ $p->childCategory->name ?? '-' }}</td>

            <td>{{ ucfirst($p->name) }}</td>
            <td>{{ strtoupper($p->sku) }}</td>

            <td>

              @if($p->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $p->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $p->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editProduct"
                data-id="{{ $p->id }}" title="Edit" data-bs-toggle="tooltip">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $p->id }}" title="Delete" data-bs-toggle="tooltip">
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
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Product</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="product_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body row">

          <div class="col-md-4 mb-2">
            <label class="form-label">Category</label>
            <select id="category_id" name="category_id" class="form-control select2">
              <option value="" disabled selected>Select Category</option>
              @foreach($categories as $c)
              <option value="{{ $c->id }}">{{ ucfirst($c->name) }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 mb-2">
            <label class="form-label">Sub Category</label>
                <select id="subcategory_id" name="subcategory_id" class="form-control select2">
                    <option value="">Select SubCategory</option>
                </select>
          </div>

          <div class="col-md-4 mb-2">
            <label class="form-label">Child Category</label>
                <select id="child_category_id" name="child_category_id" class="form-control select2">
                    <option value="">Select Child SubCategory</option>
                </select>
          </div>

          <div class="col-md-4 mb-2">
            <label class="form-label">Product Name</label>
            <input type="text" id="name" name="name" class="form-control">
          </div>
          

          <div class="col-md-4 mb-2">
            <label class="form-label">Price</label>
            <input type="number" id="price" name="price" class="form-control">
          </div>

          <div class="col-md-4 mb-2">
            <label class="form-label">Sale Price</label>
            <input type="number" id="sale_price" name="sale_price" class="form-control">
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label">Stock</label>
            <input type="number" id="stock" name="stock" class="form-control">
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label">Main Image</label>
            <input type="file" id="main_image" name="main_image" class="form-control">
          </div>

          <div class="col-md-12 mb-2">
            <label class="form-label">Description</label>
            <textarea id="description" name="description" rows="2" class="form-control"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitProduct">Save</button>
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
    dropdownParent: $('#productModal')
});

$('#product_tbl').DataTable();

/* VALIDATION */
$("#product_form").validate({
    rules: {
        category_id: { required: true },
        // subcategory_id: { required: true },
        name: { required: true, product_check: true },
        sku: { required: true,sku_check: true },
        price: { required: true },
        stock: { required: true }
    },
    messages: {
        category_id: { required: "Please Select Category" },
        // subcategory_id: { required: "Please Select Sub Category" },
        name: { required: "Please Enter Product Name" },
        sku: { required: "Please Enter SKU" }
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
    "product_check",
    function(value) {
        var id = $("#id").val();

        var exist = $.ajax({
            url: "/product/check-name",
            type: "POST",
            async: false,
            data: { name: value, id: id },
        }).responseText;

        return exist != 1;
    },
    "Product Already Exists"
);


/* Create */
$('#createNew').click(function() {
    $("#product_form").validate().resetForm();
    $('#product_form')[0].reset();
    $('#product_form').find('.is-invalid').removeClass('is-invalid');
    $(".select2").val('').trigger('change');
    $('#id').val('');

    new bootstrap.Modal(document.getElementById('productModal')).show();
});

/* Save */
$('.submitProduct').click(function(e){
    e.preventDefault();

    if($("#product_form").valid()){
        let formData = new FormData($("#product_form")[0]);

        $.ajax({
            url: "/product/store",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                modal.hide();
                toaster_message(data.message, data.icon);
            }
        });
    }
});

/* Edit */
$(document).on('click', '.editProduct', function(){
    $.post('/product/edit',
     { id: $(this).data('id'), _token: $('meta[name=\"csrf-token\"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#category_id').val(res.data.category_id).trigger('change', [res.data.subcategory_id, res.data.child_category_id]);
            $('#name').val(res.data.name);
            $('#description').val(res.data.description);
            $('#price').val(res.data.price);
            $('#sale_price').val(res.data.sale_price);
            $('#stock').val(res.data.stock);

            new bootstrap.Modal(document.getElementById('productModal')).show();
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
                url: "/product/delete",
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
        url: '/product/toggle-status',
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

$('#category_id').on('change', function(e, selected_subcategory, selected_childcategory) {
    let id = $(this).val();
    $('#subcategory_id').html('<option value="">Select SubCategory</option>');
    $('#child_category_id').html('<option value="">Select Child SubCategory</option>');

    if (id) {
        $.ajax({
            url: "/product/get-subcategories",
            type: "POST",
            data: { category_id: id, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                $.each(data, function(i, item) {
                    $('#subcategory_id').append(`<option value="${item.id}">${item.subcat_name}</option>`);
                });

                // 🟢 set subcategory after loading
                if (selected_subcategory) {
                    $('#subcategory_id').val(selected_subcategory).trigger('change', selected_childcategory);
                }
            }
        });
    }
});

$('#subcategory_id').on('change', function(e, selected_childcategory) {
    let id = $(this).val();
    $('#child_category_id').html('<option value="">Select Child SubCategory</option>');

    if (id) {
        $.ajax({
            url: "/product/get-childcategories",
            type: "POST",
            data: { subcategory_id: id, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(data) {
                $.each(data, function(i, item) {
                    $('#child_category_id').append(`<option value="${item.id}">${item.name}</option>`);
                });

                // 🟢 set child category after load complete
                if (selected_childcategory) {
                    $('#child_category_id').val(selected_childcategory);
                }
            }
        });
    }
});



</script>

@endsection
