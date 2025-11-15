@extends('layouts.master')
@section('title', 'User')

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
                    <h5 class="card-title mb-0">Users</h5>
                </div>

                <div class="dt-action-buttons text-end pt-3 pt-md-0 d-flex align-items-center">
                    <!-- Export Dropdown -->
                     <button class="btn btn-primary" id="addNew">Add New</button>
                </div>
            </div>

            <!-- Static Table -->
            <div class="card-datatable table-responsive pt-0">
                <table class="dt-responsive table table-bordered" id="users_tbl">
                    <thead class="table-light">
                        <tr>
                        <th style="width: 5%">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Type</th>
                        <th style="width: 5%">Pages Rights</th>
                        <th style="width: 14%">Image</th>
                        <th style="width: 3%">Status</th>
                        <th style="width: 11%">Actions</th>
                        </tr>
                    </thead>
                     <tbody>
                    @foreach($admins as $key => $a)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->email }}</td>
                        <td>{{ $a->mobile }}</td>
                        <td>{{ $a->type == 'A' ? 'Admin' : 'User' }}</td>
                        <td>
                            @if ($a->type != 'A')
                                 <button type="button" class="btn btn-primary assignButton" data-id="{{ $a->id }}" data-bs-toggle="modal" data-bs-target="#menuModal">
                                         <i class="bx bx-plus"></i> Assign
                                </button>
                            @endif
                        </td>   

                        <td>
                            @if($a->image)
                                <img src="{{ asset($a->image) }}" class="img-fluid border">
                            @endif
                        </td>
                        <td>
                            @if($a->isblock)
                                <button class="btn btn-danger btn-icon toggle-status" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to Active"
                                        data-id="{{ $a->id }}" data-status="0"
                                        style="min-width: 90px; white-space: nowrap;"> <i class="fas fa-user-slash"></i>
                                
                                </button>
                            @else
                                <button class="btn btn-success btn-icon toggle-status" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="Click here to Blocked"
                                        data-id="{{ $a->id }}" data-status="1"
                                        style="min-width: 90px; white-space: nowrap;"><i class="fas fa-user-check"></i>
                                </button>
                            @endif

                        </td>
                        <td>
                            <button class="btn btn-info btn-icon editAdmin" title="Edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="{{ $a->id }}"><i class="bx bx-pencil"></i></button>
                            <button class="btn btn-danger btn-icon deleteAdmin"  title="Delete" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="{{ $a->id }}"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>

       
          {{-- Add/Edit Modal --}}
        <div class="modal fade" id="adminModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="adminForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="adminId">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add New</h5>
                                    <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center p-0" data-bs-dismiss="modal" aria-label="Close"
                                                style="width: 32px; height: 32px;">
                                        <i class='bx bx-x fs-5'></i>
                                        </button>                        
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                                <div class="col-md-6">
                                    <label>Mobile</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" maxlength="11">
                                </div>
                                <div class="col-md-6">
                                    <label>Type</label>
                                    <select class="form-select" name="type" id="type">
                                        <option value="A">Admin</option>
                                        <option value="U">User</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="imageUpload" id="imageUpload" accept="image/*">
                                </div>
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

    </div>
    
    <!-- Menu Selection Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="menuModalLabel">Pages Rights</h5>
        <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center p-0"
                                                data-bs-dismiss="modal" aria-label="Close"
                                                style="width: 32px; height: 32px;">
                                        <i class='bx bx-x fs-5'></i>
                                        </button> 
      </div>
    <form id="rightForm">
        @csrf
      <div class="modal-body">
        <input type="hidden" id="rightId" name="id">
        <input type="hidden" id="rightAdminId" name="rightAdminId" value="">
         <input type="hidden" id="page_id" name="page_id">
        <ul class="list-group">
          @foreach ($pages->where('parent_id', 0) as $parent)
            <li class="list-group-item">
              <div class="form-check">
                <input class="form-check-input menu-checkbox" type="checkbox"
                       data-id="{{ $parent->id }}" data-name="{{ $parent->title }}">
                <label class="form-check-label fw-bold">
                  <i class="{{ $parent->icon }} me-2"></i>{{ $parent->title }}
                </label>
              </div>

              @php
                $children = $pages->where('parent_id', $parent->id);
              @endphp
              @if($children->count() > 0)
                <ul class="list-group mt-2 ms-4 border-start ps-3">
                  @foreach ($children as $child)
                    <li class="list-group-item">
                      <div class="form-check">
                        <input class="form-check-input menu-checkbox" type="checkbox"
                               data-id="{{ $child->id }}"
                               data-name="{{ $parent->title }} → {{ $child->title }}"
                               data-parent="{{ $parent->id }}"
                               data-parentname="{{ $parent->title }}">
                        <label class="form-check-label">
                          <i class="{{ $child->icon }} me-2"></i>{{ $child->title }}
                        </label>
                      </div>
                    </li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="applyMenuSelection">Apply</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

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
<script>
$(document).ready(function () {

    // DataTable setup
    $('#users_tbl').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        order: [[0, 'asc']]
    });


     $("#adminForm").validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
            },
            password: {
                 required: function(element) {
                var idVal = $('#adminId').val();
                return (idVal === '' || idVal === null);
            },
            },
            mobile: {
                required: true,
            },

        imageUpload: { 
            required: function(element) {
                var idVal = $('#adminId').val();
                return (idVal === '' || idVal === null);
            },
        }
        },
        messages: {
            name: {
                required: "Please Enter Name",
            },
            email: {
                required: "Please Enter Email",
            },
            password: {
                required: "Please Enter Password",
            },
            mobile: {
                required: "Please Enter Mobile number",
            },
            imageUpload: {
                required: "Please Select Image",
            },
        },

        errorElement: "div", // Wrap error in div instead of label
        errorClass: "text-danger mt-1", // Bootstrap styling
       errorPlacement: function (error, element) {
        // Correct placement
        error.insertAfter(element);
    },
        highlight: function (element) {
            $(element).removeClass("error");
        },
        normalizer: function (value) {
            return $.trim(value);
        },
    });

    // Open Modal
    $('#addNew').click(function() {
        $('#adminId').val('');
        $('#adminForm')[0].reset();
        $('#modalTitle').text('Add Admin');
        $('#adminModal').modal('show');
         $('#adminModal').on('shown.bs.modal', function () {
            $("#name").trigger('focus');
        });
    });

    // Save
     $('#adminForm').submit(function(e) {
         e.preventDefault();
         let formData = new FormData(this);
         if ($("#adminForm").valid()) {
                $.ajax({
                    url: '/users/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#adminModal').modal('hide');
                        $("#adminForm")[0].reset();
                        toaster_message(data.message, data.icon);
                    },
                    error: function (request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
        }
     });

    // Edit
        $(document).on('click', '.editAdmin', function() {
            let id = $(this).data('id');

            $.get('/users/edit/' + id, function(res) {
                console.log("res data");
                console.log(res);
                
                
                $('#adminId').val(res.id);
                $('#name').val(res.name);
                $('#email').val(res.email);
                $('#mobile').val(res.mobile);
                $('#type').val(res.type);
                // $('#password').val(res.password);
                // $('#modalTitle').text('Edit Admin');
                $('#adminModal').modal('show');
            });
    });


    // Delete
    $(document).on("click", ".deleteAdmin", function () {
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
                    url: aurl + "/users/delete",
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

$(document).on('click', '.toggle-status', function() {
    var btn = $(this);
    var id = btn.data('id');
    var status = btn.data('status');

    // Immediately hide/remove tooltip to prevent “stuck” issue
    btn.tooltip('hide');
    $(".tooltip").remove();

    $.ajax({
        url: '/users/toggle-status',
        type: 'POST',
        data: {
            id: id,
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if (!res.success) return;

            // Dispose old tooltip (remove cached instance)
            try { btn.tooltip('dispose'); } catch (e) {}

            // Update button UI
            if (status == 1) {
                // User currently active → block them
                btn.removeClass('btn-success')
                   .addClass('btn-danger')
                   .html('<i class="fas fa-user-slash"></i>')
                   .attr('title', 'Click here to activate user')
                   .data('status', 0);
                        btn.tooltip({ container: 'body' });
                   toaster_alert_action('User Blocked Successfully!', 'error');
            } else {
                // User currently blocked → activate them
                btn.removeClass('btn-danger')
                   .addClass('btn-success')
                   .html('<i class="fas fa-user-check"></i>')
                   .attr('title', 'Click here to block user')
                   .data('status', 1);
                        btn.tooltip({ container: 'body' });
                   toaster_alert_action('User Activated Successfully!', 'success');
            }

            // // Reinitialize tooltip cleanly
            // btn.tooltip({ container: 'body' });

            // // Trigger toast message
            // toaster_alert_action(res.message, "success");
        },
        error: function(xhr) {
            console.error(xhr);
            alert('Something went wrong. Please try again.');
        }
    });
});





//  selected menus save
  
  const checkboxes = document.querySelectorAll('.menu-checkbox');

    // Auto-select parent if child selected, deselect parent if all children unchecked
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const parentId = this.getAttribute('data-parent');
            const id = this.getAttribute('data-id');

            // ✅ If child selected → select parent
            if (this.checked && parentId) {
                const parent = document.querySelector(`.menu-checkbox[data-id="${parentId}"]`);
                if (parent) parent.checked = true;
            }

            // ✅ If child unchecked → check if all siblings are unchecked → uncheck parent
            if (!this.checked && parentId) {
                const siblings = document.querySelectorAll(`.menu-checkbox[data-parent="${parentId}"]`);
                const parent = document.querySelector(`.menu-checkbox[data-id="${parentId}"]`);
                const anyChecked = Array.from(siblings).some(sib => sib.checked);
                if (!anyChecked && parent) parent.checked = false;
            }

            // ✅ If parent checked → check all its children
            if (this.checked) {
                const children = document.querySelectorAll(`.menu-checkbox[data-parent="${id}"]`);
                children.forEach(ch => ch.checked = true);
            }

            // ✅ If parent unchecked → uncheck all its children
            if (!this.checked) {
                const children = document.querySelectorAll(`.menu-checkbox[data-parent="${id}"]`);
                children.forEach(ch => ch.checked = false);
            }
        });
    });


    let currentAssignId = null; // global variable

    $(document).on('click', '.btn-primary', function() {
        currentAssignId = $(this).data('id'); 
        console.log("Assign ID set:", currentAssignId);
    });

    $('#applyMenuSelection').click(function() {
         $('#rightAdminId').val(currentAssignId);  
        // sab checked menu IDs lo
        let selectedIds = [];
        $('.menu-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            alert("Please select at least one menu.");
            return;
        }
        
        // hidden field me daalo
        $('#page_id').val(selectedIds.join(','));

        $.ajax({
            
            url: '/users/rights-store', 
            type: 'POST',
            data: $('#rightForm').serialize(),
            success: function(res) {
                // Modal band karo
                $('#menuModal').modal('hide');

                // Success message dikhao (ya toaster)
                toaster_alert_action(res.message, res.icon);

            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong while saving.');
            }
        });
    });

    $(document).on('click', '.assignButton', function() {
    currentAssignId = $(this).data('id'); 
    console.log("Assign ID set:", currentAssignId);

    // Saare checkbox uncheck kar do pehle
    $('.menu-checkbox').prop('checked', false);

    // Backend se us admin ke assigned pages lao
    $.ajax({
        url: `/users/get-rights/${currentAssignId}`, // <-- ye route bana lena backend me
        type: 'GET',
        success: function(res) {
            if (res.page_ids && res.page_ids.length > 0) {
                res.page_ids.forEach(id => {
                    $(`.menu-checkbox[data-id="${id}"]`).prop('checked', true);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading rights:', xhr.responseText);
        }
    });
});

});
</script>
@endsection
