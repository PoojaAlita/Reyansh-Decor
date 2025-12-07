@extends('layouts.master')
@section('title', 'Sliders')

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
      <h5 class="card-title mb-0">Sliders</h5>
      <button type="button" class="btn btn-primary create-new" id="createNew">
        <i class="fas fa-plus me-1"></i> Add New
      </button>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="sliders_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Title</th>
            <th>Sub Title</th>
            <th>Link</th>
            <th width="10%">Image</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($sliders as $key => $sl)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $sl->title }}</td>
            <td>{{ $sl->sub_title ?? '-' }}</td>
            <td>{{ $sl->link ?? '-' }}</td>
             <td>
              @if($sl->image)
              <img src="{{ asset('uploads/sliders/'.$sl->image) }}" width="100" height="100" class="rounded">
              @else
              -
              @endif
            </td>
            <td>

              @if($sl->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $sl->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $sl->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editSlider"
                data-id="{{ $sl->id }}" title="Edit" data-bs-toggle="tooltip">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $sl->id }}" title="Delete" data-bs-toggle="tooltip">
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
<div class="modal fade" id="sliderModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Slider</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="slider_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">

          <label class="form-label">Title</label>
          <input type="text" id="title" name="title" class="form-control mb-2">

          <label class="form-label">Sub Title</label>
          <input type="text" id="sub_title" name="sub_title" class="form-control mb-2">

          <label class="form-label">Link</label>
          <input type="text" id="link" name="link" class="form-control mb-2">

          <label class="form-label">Slider Image</label>
          <input type="file" id="image" name="image" class="form-control mb-2">

          <div id="preview_image" class="mt-2"></div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitSlider">Save</button>
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

$('#sliders_tbl').DataTable();

/* VALIDATION */
$("#slider_form").validate({
    rules: {
        title: { required: true },
    },
    messages: {
        title: { required: "Please Enter Title" },
    },
    errorElement: "div",
    errorClass: "text-danger mt-1",
    errorPlacement: function (error, element) { error.insertAfter(element); }
});

/* Create */
$('#createNew').click(function() {
    $("#slider_form").validate().resetForm();
    $('#slider_form')[0].reset();
    $('#slider_form').find('.is-invalid').removeClass('is-invalid');
    $('#id').val('');
    $('#preview_image').html('');

    new bootstrap.Modal(document.getElementById('sliderModal')).show();
});

/* Save */
$('.submitSlider').click(function(e){
    e.preventDefault();

    if($("#slider_form").valid()){
        var formData = new FormData($("#slider_form")[0]);

        $.ajax({
            url: '/sliders/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                const modal = bootstrap.Modal.getInstance(document.getElementById('sliderModal'));
                modal.hide();
                toaster_message(data.message, data.icon);
            }
        });
    }
});

/* Edit */
$(document).on('click', '.editSlider', function(){
    $.post('/sliders/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') }, 
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#title').val(res.data.title);
            $('#sub_title').val(res.data.sub_title);
            $('#link').val(res.data.link);

            if(res.data.image){
                $('#preview_image').html('<img src="/uploads/sliders/'+res.data.image+'" width="100" class="mt-2 rounded">');
            }

            new bootstrap.Modal(document.getElementById('sliderModal')).show();
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
                url: "/sliders/delete",
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
        url: '/sliders/toggle-status',
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
