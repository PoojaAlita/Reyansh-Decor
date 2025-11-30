@extends('layouts.master')
@section('title', 'Sub Category')

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
      <h5 class="card-title mb-0">Sub Categories</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="subcategory_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Category</th>
            <th>Name</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($subcategories as $key => $sc)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $sc->category->name ?? '-' }}</td>
            <td>{{ ucfirst($sc->subcat_name) }}</td>

            <td>

              @if($sc->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $sc->id }}" data-status="0" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $sc->id }}" data-status="1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editSubCategory"
                data-id="{{ $sc->id }}" title="Edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $sc->id }}" title="Delete" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top">
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
<div class="modal fade" id="subcategoryModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Sub Category</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="subcategory_form">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">

          <label class="form-label">Category</label>
          <select id="category_id" name="category_id" class="form-control select2 mb-2">
            <option value="" disabled selected>Select Category</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ ucfirst($cat->name) }}</option>
            @endforeach
          </select>

          <label class="form-label">Sub Category Name</label>
          <input type="text" id="name" name="name" class="form-control mb-2">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitSubCategory">Save</button>
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
    dropdownParent: $('#subcategoryModal')
});

$('#subcategory_tbl').DataTable();

/* VALIDATION */
$("#subcategory_form").validate({
    rules: {
        category_id: { required: true },
        name: { required: true, subcategory_check: true }
    },
    messages: {
        category_id: { required: "Please Select Category" },
        name: { required: "Please Enter Sub Category Name" }
    },
    errorElement: "div",
    errorClass: "text-danger mt-1",
    errorPlacement: function (error, element) { error.insertAfter(element); }
});

/* Unique Check */
$.validator.addMethod(
    "subcategory_check",
    function(value) {
        var id = $("#id").val();
        var exist = $.ajax({
            url: "/subcategory/check-name",
            type: "POST",
            async: false,
            data: { name: value, id: id },
        }).responseText;

        return exist != 1;
    },
    "Sub Category Already Exists"
);

/* Create */
$('#createNew').click(function() {
    $("#subcategory_form").validate().resetForm();
    $('#subcategory_form')[0].reset();
    $(".select2").val('').trigger('change');
    $('#id').val('');
     $('#subcategoryModal select').each(function() {
        $(this).val(null).trigger('change');
    });
    new bootstrap.Modal(document.getElementById('subcategoryModal')).show();
    $('#subcategoryModal').on('shown.bs.modal', function () { $("#category_id").trigger('focus'); });

});

/* Save */
$('.submitSubCategory').click(function(e){
    e.preventDefault();

    if($("#subcategory_form").valid()){
        $.post('/subcategory/store', $("#subcategory_form").serialize(), function(data){
            const modal = bootstrap.Modal.getInstance(document.getElementById('subcategoryModal'));
            modal.hide();
            toaster_message(data.message, data.icon);
        });
    }
});

/* Edit */
$(document).on('click', '.editSubCategory', function(){
    $.post('/subcategory/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#category_id').val(res.data.category_id).trigger('change');
            $('#name').val(res.data.subcat_name);
            new bootstrap.Modal(document.getElementById('subcategoryModal')).show();
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
        if (result.value) {
            $.ajax({
                url: aurl + "/subcategory/delete",
                type: "POST",
                dataType: "JSON",
                data: { id: id },
                success: function (data) { toaster_message(data.message, data.icon); },
                error: function (error) { swalWithBootstrapButtons.fire('Cancelled', 'this data is not available :)', 'error') }
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
        url: '/subcategory/toggle-status',
        type: 'POST',
        data: { id: id, status: status, _token: $('meta[name="csrf-token"]').attr('content') },
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
