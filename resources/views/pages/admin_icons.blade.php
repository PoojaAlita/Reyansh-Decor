@extends('layouts.master')
@section('title', 'Admin Icons')

@section('plugin-stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <!-- Form Validation -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Card with Export + Add New Buttons -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header flex-column flex-md-row d-flex justify-content-between align-items-center">
                <div class="head-label text-center mb-2 mb-md-0">
                    <h5 class="card-title mb-0">Icons </h5>
                </div>

                <div class="dt-action-buttons text-end pt-3 pt-md-0 d-flex align-items-center">
                    <!-- Export Dropdown -->
                     <button class="btn btn-primary" id="addNew">Add New</button>
                     
                </div>
            </div>

            <!-- Static Table -->
          <div class="card-datatable table-responsive pt-0">
                <table class="dt-responsive table table-bordered" id="icons_tabl">
                    <thead class="table-light">
                        <tr>
                        <th style="width: 2%">#</th>
                        <th style="width: 5%">Icon</th>
                        <th>Icon Title</th>
                        <th>Icon Class</th>
                        <th style="width: 14%">Actions</th>
                        </tr>
                    </thead>
                     <tbody>
                    @foreach ($icons as $icon)
                    <tr id="row{{ $icon->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><i class="{{ $icon->class }} fs-4"></i></td>
                        <td>{{ $icon->title }}</td>
                        <td>{{ $icon->class }}</td>
                        <td>
                            @if($icon->isshown)
                                <button class="btn btn-icon btn-success toggle-status" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to disable" data-id="{{ $icon->id }}" data-status="0">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <button class="btn btn-icon btn-dark toggle-status" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to enable" data-id="{{ $icon->id }}" data-status="1">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            @endif

                            <button class="btn btn-icon btn-primary edit-icon" title="Edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="{{ $icon->id }}">
                                <i class="bx bx-pencil"></i>
                            </button>

                            <button class="btn btn-icon btn-danger delete"  title="Delete" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="{{ $icon->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Form Modal -->
            <div class="modal fade" id="iconFormModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.icons.store') }}" id="iconForm" class="modal-content">
                        @csrf
                        <input type="hidden" name="hId" id="hId" value="0">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Icon</h5>
                             <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center p-0" data-bs-dismiss="modal" aria-label="Close" style="width: 32px; height: 32px;">
                                    <i class='bx bx-x fs-5'></i>
                            </button> 
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Icon Title</label>
                                <input type="text" class="form-control" name="txtName" id="txtName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Icon Class</label>
                                <input type="text" class="form-control" name="txtClass" id="txtClass" required>
                                <small class="text-muted">Example: <code>bx bx-chart</code></small>
                            </div>

                             <div id="iconPreview" class="mt-3" style="display:none;">
                                <i id="previewIcon" class="fs-1"></i>
                                <div class="text-muted small mt-1">Icon Preview</div>
                             </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    
@endsection


@section('plugin-script')
 <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <!-- Form Validation -->
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

@if (session('success'))
<script>
    toaster_message(@json(session('success')), "success");
</script>
@endif

@if (session('error'))
<script>
    toaster_message(@json(session('error')), "error",);
</script>
@endif
<script>

    
$(document).ready(function () {

        // DataTable setup
        $('#icons_tabl').DataTable({
            pageLength: 10,
            ordering: true,
            searching: true,
            lengthChange: true,
            order: [[0, 'asc']]
        });

        // Add new icon
        $('#addNew').click(function () {
            $('#iconFormModal .modal-title').text('Add Icon');
            $('#iconForm')[0].reset();
            $('#hId').val(0);
            $('#iconFormModal').modal('show');
             $('#iconFormModal').on('shown.bs.modal', function () {
            $("#txtName").trigger('focus');
        });

        });

        // Edit icon
        $('.edit-icon').click(function () {
            var id = $(this).data('id');
            $.get('/admin-icons/edit', { id: id }, function (data) {
                $('#iconFormModal .modal-title').text('Edit Icon');
                $('#hId').val(data.id);
                $('#txtName').val(data.title);
                $('#txtClass').val(data.class);
                $('#iconFormModal').modal('show');
            });
        });

        // Delete icon
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
                        url: aurl + "/admin-icons/delete",
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

 $(document).on('click', '.toggle-status', function () {
    var btn = $(this);
    var id = btn.data('id');
    var status = btn.data('status');

    // Immediately remove/hide tooltip to prevent overlap
    btn.tooltip('hide');
    $(".tooltip").remove();

    $.ajax({
        url: '/admin-icons/toggle-status',
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


    //Icon Preview
    $('#txtClass').on('input', function () {
        const iconClass = $(this).val().trim();

        if (iconClass) {
            $('#previewIcon')
                .attr('class', iconClass + ' fs-1'); // update icon + keep large size
            $('#iconPreview').show();
        } else {
            $('#iconPreview').hide();
        }
    });
    
});
</script>
@endsection
