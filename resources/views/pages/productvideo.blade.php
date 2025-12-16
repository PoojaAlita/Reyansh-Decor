@extends('layouts.master')
@section('title', 'Product Videos')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y" id="tableSection">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Product Videos</h5>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary create-new" id="createNew">
          <i class="fas fa-plus me-1"></i> Add New
        </button>

      </div>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="productvideo_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Title</th>
            <th>Video URL</th>
            <th width="15%">Thumbnail</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\ProductVideo::latest()->cursor() as $key => $pv)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ ucfirst($pv->title) }}</td>
            <td style="max-width:300px;word-break:break-all;">{{ $pv->video_url }}</td>
            <td>
              @if($pv->thumbnail)
                <img src="{{ asset('uploads/product_videos/'.$pv->thumbnail) }}" width="100" height="100" class="rounded">
              @else
                -
              @endif
            </td>
            <td>
              @if($pv->ishown)
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

              <button class="btn btn-info btn-icon editProductVideo"
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
<div class="modal fade" id="productvideoModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title">Add Product Video</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
                data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="productvideo_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">
          <label class="form-label">Product</label>
          <select id="product_id" name="product_id" class="form-select mb-2">
            <option value="" disabled selected>Select Product</option>
            @foreach(\App\Models\Product::latest()->cursor() as $product)
              <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
          </select>

          <label class="form-label">Title</label>
          <input type="text" id="title" name="title" class="form-control mb-2">

          <label class="form-label">Video URL</label>
          <input type="text" id="video_url" name="video_url" class="form-control mb-2">

          <label class="form-label">Thumbnail</label>
          <input type="file" id="thumbnail" name="thumbnail" class="form-control mb-2">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitProductVideo">Save</button>
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
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script>
$('#productvideo_tbl').DataTable();

$('.select2').select2({ dropdownParent: $('#productvideoModal') });

$(document).on("change", ".select2-hidden-accessible", function () {
                    $(this).valid(); 
                });

/* VALIDATION */
$("#productvideo_form").validate({
    rules: {
        product_id: { required: true },
        title: { required: true, productvideo_check: true },
        video_url: { required: true },
    },
    messages: {
        product_id: { required: "Please select product" },
        title: { required: "Please Enter Title" },
        video_url: { required: "Please Enter Video URL" }
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
    "productvideo_check",
    function(value) {
        var id = $("#id").val();
        var exist = $.ajax({
            url: "/productvideo/check-name",
            type: "POST",
            async: false,
            data: { title: value, id: id },
        }).responseText;
        return exist != 1;
    },
    "Title Already Exists"
);

/* Create */
$('#createNew').click(function() {
    $("#productvideo_form").validate().resetForm();
    $('#productvideo_form')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('#id').val('');
    new bootstrap.Modal(document.getElementById('productvideoModal')).show();
    $('#productvideoModal').on('shown.bs.modal', function () { $("#product_id").trigger('focus'); });
});

/* Save */
$('.submitProductVideo').click(function(e){
    e.preventDefault();
    if($("#productvideo_form").valid()){
        var form = $('#productvideo_form')[0];
        var data = new FormData(form);
        $.ajax({
            url: '/productvideo/store',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(data){
                const modal = bootstrap.Modal.getInstance(document.getElementById('productvideoModal'));
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
$(document).on('click', '.editProductVideo', function(){
    $.post('/productvideo/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') },
    function(res){
        if(res.status){
            $("#productvideo_form").validate().resetForm();
            $('#productvideo_form')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('#id').val(res.data.id);
            $('#product_id').val(res.data.product_id);
            $('#title').val(res.data.title);
            $('#video_url').val(res.data.video_url);
            new bootstrap.Modal(document.getElementById('productvideoModal')).show();
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
                url: "/productvideo/delete",
                type: "POST",
                dataType: "JSON",
                data: { id: id, _token: $('meta[name="csrf-token"]').attr('content') },
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
        url: '/productvideo/toggle-status',
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
