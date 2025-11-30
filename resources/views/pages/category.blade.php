@extends('layouts.master')
@section('title', 'Category')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Categories</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered"  id="category_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Name</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($categories as $key => $cat)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ ucfirst($cat->name) }}</td>

            <td>
              @if($cat->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                title="Click to Disable" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to disable" data-id="{{ $cat->id }}" data-status="0">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                title="Click to Enable" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to enable" data-id="{{ $cat->id }}" data-status="1">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editCategory"
                data-id="{{ $cat->id }}" title="Edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $cat->id }}" title="Delete" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top">
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
<div class="modal fade" id="categoryModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="category_form">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">
          <label class="form-label">Category Name</label>
          <input type="text" id="name" name="name" class="form-control mb-2">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitCategory">Save</button>
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

<script>

$('#category_tbl').DataTable();

/* VALIDATION */
$("#category_form").validate({
    rules: {
        name: { required: true, category_check: true }
    },
    messages: {
        name: { required: "Please Enter Category Name" }
    },
    errorElement: "div",
    errorClass: "text-danger mt-1",
    errorPlacement: function (error, element) { error.insertAfter(element); },
    highlight: function (element) { $(element).addClass("is-invalid"); },
    unhighlight: function (element) { $(element).removeClass("is-invalid"); }
});

/* Unique Category Check */
$.validator.addMethod(
    "category_check",
    function(value) {
        var id = $("#id").val();
        var exist = $.ajax({
            url: "/category/check-name",
            type: "POST",
            async: false,
            data: { name: value, id: id },
        }).responseText;

        return exist != 1;
    },
    "Category Already Exists"
);

/* Create */
$('#createNew').click(function() {
    $("#category_form").validate().resetForm();
    $('#category_form')[0].reset();
    $('#id').val('');
    new bootstrap.Modal(document.getElementById('categoryModal')).show();
    $('#categoryModal').on('shown.bs.modal', function () { $("#name").trigger('focus'); });

});

/* Save */
$('.submitCategory').click(function(e){
    e.preventDefault();

    if($("#category_form").valid()){
        $.post('/category/store', $("#category_form").serialize(), function(data){
            const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
            modal.hide();
            toaster_message(data.message, data.icon);
        });
    }
});

/* Edit */
$(document).on('click', '.editCategory', function(){
    $.post('/category/edit', { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#name').val(res.data.name);
            new bootstrap.Modal(document.getElementById('categoryModal')).show();
        }
    });
});


/* Delete */
  $(document).on("click", ".delete", function () {
            var id = $(this).data('id');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger me-2'
                },
                buttonsStyling: false,
            })
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
                        url: aurl + "/category/delete",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.status) {
                                toaster_message(data.message, data.icon, data.redirect_url, aurl);
                            } else {
                                toaster_message(data.message, data.icon, data.redirect_url, aurl);
                            }

                        },
                        error: function (error) {
                            swalWithBootstrapButtons.fire('Cancelled', 'this data is not available :)', 'error')
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire('Cancelled', 'Your data is safe :)', 'error')
                }
            })
        });

/* Toggle Status */

 $(document).on('click', '.toggle-status', function () {
    var btn = $(this);
    var id = btn.data('id');
    var status = btn.data('status');

    // Immediately remove/hide tooltip to prevent overlap
    btn.tooltip('hide');
    $(".tooltip").remove();

    $.ajax({
        url: '/category/toggle-status',
        type: 'POST',
        data: {
            id: id,
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (!response.success) {
                alert(response.message || 'Something went wrong');
                return;
            }

            // Dispose any existing tooltip instance
            try { btn.tooltip('dispose'); } catch (e) {}

            // Toggle UI state (button color, icon, title, and data-status)
            if (status == 0) {
                btn.removeClass('btn-success')
                   .addClass('btn-danger')
                   .html('<i class="bx bx-hide"></i>')
                   .attr('title', 'Click here to enable')
                   .data('status', 1);
            } else {
                btn.removeClass('btn-danger')
                   .addClass('btn-success')
                   .html('<i class="bx bx-show"></i>')
                   .attr('title', 'Click here to disable')
                   .data('status', 0);
            }

            // Reinitialize tooltip (attach to body to avoid clipping)
            btn.tooltip({ container: 'body' });

            // Toast success message
            toaster_alert_action(response.message, response.icon);
        },
        error: function (xhr) {
            console.error(xhr);
            alert('Request failed. Check console for details.');
        }
    });
});

</script>
@endsection
