@extends('layouts.master')
@section('title', 'Home Banners')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y" id="tableSection">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Home Banners</h5>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary create-new" id="createNew">
          <i class="fas fa-plus me-1"></i> Add New
        </button>

        <button class="btn btn-primary" id="btnSortingRight">
          <i class="fas fa-sort me-1"></i> Sorting
        </button>
      </div>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="homebanner_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Title</th>
            <th>Link</th>
            <th width="15%">Image</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach(\App\Models\HomeBanner::latest()->cursor() as $key => $hb)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ ucfirst($hb->title) }}</td>
            <td style="max-width:300px;word-break:break-all;">{{ $hb->link ?? '-' }}</td>
            <td>
              @if($hb->image)
              <img src="{{ asset('uploads/home_banners/'.$hb->image) }}" width="100" height="100" class="rounded">
              @else
              -
              @endif
            </td>
            <td>
              @if($hb->isshown)
              <button class="btn btn-success btn-icon toggle-status"
                data-id="{{ $hb->id }}" data-status="0" data-bs-toggle="tooltip" title="Click here to disable">
                <i class="fas fa-eye"></i>
              </button>
              @else
              <button class="btn btn-danger btn-icon toggle-status"
                data-id="{{ $hb->id }}" data-status="1" data-bs-toggle="tooltip" title="Click here to enable">
                <i class="fas fa-eye-slash"></i>
              </button>
              @endif

              <button class="btn btn-info btn-icon editHomeBanner"
                data-id="{{ $hb->id }}" title="Edit" data-bs-toggle="tooltip">
                <i class="bx bx-pencil"></i>
              </button>

              <button class="btn btn-danger btn-icon delete"
                data-id="{{ $hb->id }}" title="Delete" data-bs-toggle="tooltip">
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

{{-- Sorting Section (Hidden by Default) --}}
<div class="col-5 mx-auto" id="sortingSection" style="display:none;">
  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5>Sorting Order</h5>

      <button type="button" id="btnCloseSort"
        class="btn btn-danger d-flex align-items-center justify-content-center p-0"
        style="width: 32px; height: 32px;">
        <i class='bx bx-x fs-5'></i>
      </button>
    </div>

    <div class="card-body row">
      <div class="col-md-8">
        <select id="ddlMenuForSorting" class="form-select mb-3 option-gap">
          <option value="" disabled selected>-- Select --</option>
          <option value="0">Select Banner Sort Menu</option>
        </select>
        <br/>
        <ul id="sortable" class="list-group mb-2"></ul>

        <button class="btn btn-primary" id="btnSaveSort">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="homebannerModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-sm">

      <div class="modal-header">
        <h5 class="modal-title">Add Home Banner</h5>
        <button type="button" class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
          data-bs-dismiss="modal" style="width:32px;height:32px;">
          <i class='bx bx-x fs-5'></i>
        </button>
      </div>

      <form id="homebanner_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="modal-body">

          <label class="form-label">Title</label>
          <input type="text" id="title" name="title" class="form-control mb-2">

          <label class="form-label">Link</label>
          <input type="text" id="link" name="link" class="form-control mb-2">

          <label class="form-label">Image</label>
          <input type="file" id="image" name="image" class="form-control mb-2">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary submitHomeBanner">Save</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection


@section('plugin-script')

<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script>

$('#homebanner_tbl').DataTable();

/* VALIDATION */
$("#homebanner_form").validate({
    rules: {
        title: { required: true, homebanner_check: true },
        // image: { required: true } // optional on edit
    },
    messages: {
        title: { required: "Please Enter Title" }
    },
    errorElement: "div",
    errorClass: "text-danger mt-1",
    errorPlacement: function (error, element) { error.insertAfter(element); }
});

/* Unique Check */
$.validator.addMethod(
    "homebanner_check",
    function(value) {
        var id = $("#id").val();

        var exist = $.ajax({
            url: "/homebanner/check-name",
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
    $("#homebanner_form").validate().resetForm();
    $('#homebanner_form')[0].reset();

    $('#id').val('');

    new bootstrap.Modal(document.getElementById('homebannerModal')).show();
    $('#homebannerModal').on('shown.bs.modal', function () { $("#title").trigger('focus'); });

});

/* Save */
$('.submitHomeBanner').click(function(e){
    e.preventDefault();

    if($("#homebanner_form").valid()){
        var form = $('#homebanner_form')[0];
        var data = new FormData(form);

        $.ajax({
            url: '/homebanner/store',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(data){
                const modal = bootstrap.Modal.getInstance(document.getElementById('homebannerModal'));
                if(modal) modal.hide();
                toaster_message(data.message, data.icon);
            },
            error: function (xhr) {
                toaster_message('Something went wrong!', 'error');
            }
        });
    }
});

/* Edit */
$(document).on('click', '.editHomeBanner', function(){
    $.post('/homebanner/edit',
     { id: $(this).data('id'), _token: $('meta[name="csrf-token"]').attr('content') },
    function(res){
        if(res.status){
            $('#id').val(res.data.id);
            $('#title').val(res.data.title);
            $('#link').val(res.data.link);
            // $('#position').val(res.data.position);
            new bootstrap.Modal(document.getElementById('homebannerModal')).show();
        } else {
            toaster_message(res.message || 'Not found', 'error');
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
                url: (typeof aurl !== 'undefined' ? aurl : '') + "/homebanner/delete",
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
        url: '/homebanner/toggle-status',
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

$(document).on('click', '#btnCloseSort', function () {

    // Hide the section
    $('#sortingSection').hide();

    // Reset the select dropdown
    $('#ddlMenuForSorting').val("");

    // Clear sortable list
    $('#sortable').empty();
});


// Sorting
// Hide/Show Logic
function showTable() {
    $('#formSection, #sortingSection').hide();
    $('#tableSection, #viewSection').fadeIn(300);
}

function showForm() 
{
    $('#tableSection, #viewSection, #sortingSection').hide();
    $('#formSection').fadeIn(300);
}

function showSorting() {
    $('#tableSection, #viewSection').hide();
    $('#sortingSection').fadeIn(300);
}

// Button actions
$('#btnCancel, #btnCancelForm').click(showTable);
$('#btnSorting, #btnSortingRight').click(showSorting);
$('#btnCloseSort').click(showTable);

$('#ddlMenuForSorting').change(function () {
      let parentid = $(this).val();
      $('#sortable').empty();

      // If Root Menu is selected → load all items
      if (parentid == 0) {
          @foreach ($home_banners as $menu)
              $('#sortable').append(
                  '<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center" data-id="{{ $menu->id }}">' +
                  '<span><i class="fas fa-sort"></i> {{ $loop->iteration }}. {{ $menu->title }}</span>' +
                  '</li>'
              );
          @endforeach

          $('#sortable').sortable();
          return;
      }

      $.post('/homebanner/get-sorting', {
          parentid,
          _token: '{{ csrf_token() }}'
      }, function (data) {

          if (data) {
              let arr = data.split('^');

              arr.forEach((item, i) => {
                  let parts = item.split('-');

                  $('#sortable').append(
                      '<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center" ' +
                      'data-id="' + parts[0] + '">' +
                      '<span><i class="fas fa-sort"></i> ' + (i + 1) + '. ' + parts[1] + '</span>' +
                      '</li>'
                  );
              });

              $('#sortable').sortable();
          }
      });
  });



$('#btnSaveSort').click(function() {
    let order = [];
    $('#sortable li').each(function(i, li) {
        order.push((i + 1) + '^' + $(li).data('id'));
    });

    $.post('/homebanner/save-sorting', {
        order,
        _token: '{{ csrf_token() }}'
    }, function() {
        $('#sortingSection').hide();
        toaster_alert_action('Sorting updated successfully!', 'success');
        $('#tableSection').show();
    });
});

</script>

@endsection
