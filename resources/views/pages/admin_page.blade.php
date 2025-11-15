@extends('layouts.master')
@section('title', 'Admin Page')

@section('plugin-stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jstree/jstree.css') }}" />
    <!-- Form Validation -->
    <link href="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/themes/default/style.min.css" rel="stylesheet">

    <style>
        #sortingSection {
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        #btnSorting.btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        #viewSection .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        #menuTree {
            background: #fff;
            font-size: 14px;
            padding-left: 10px;
            border-radius: 6px;
        }

        #menuTree .jstree-default .jstree-anchor {
            padding: 4px 6px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            color: #4a4a4a;
        }

        #menuTree .jstree-default .jstree-anchor:hover {
            background-color: #eef2ff;
            color: #2c3e50;
        }

        #menuTree .jstree-icon {
            color: #607d8b !important;
        }

        #menuTree .jstree-wholerow-hovered {
            background-color: #eef2ff !important;
        }

        #menuTree .jstree-wholerow-clicked {
            background-color: #e3e9ff !important;
        }

        .btn-primary.btn-sm {
            background-color: #4e73df;
            border: none;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-primary.btn-sm:hover {
            background-color: #375ac2;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">

            {{-- ✅ Table Section (Default Visible) --}}
            <div class="col-md-8" id="tableSection">
                <div class="card mt-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">Admin Page Records</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" id="btnAddNew">
                                <i class="fas fa-plus"></i> Add New
                            </button>

                        </div>
                    </div>

                    <div class="card-datatable table-responsive pt-0">
                        <table class="dt-responsive table-bordered table" id="admin-pages_tbl">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Parent</th>
                                    <th style="width: 5%">Icon</th>
                                    <th style="width: 22%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    // Helper recursive function
                                    function renderPageRows($pages, $parentId = 0, $level = 0)
                                    {
                                        foreach (
                                            $pages->where('parent_id', $parentId)->sortBy('sortorder')
                                            as $key => $p
                                        ) {
                                            echo '<tr>';
                                            echo '<td>' . $p->id . '</td>';
                                            echo '<td>' .
                                                str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) .
                                                ($level > 0 ? '↳ ' : '') .
                                                e($p->title) .
                                                '</td>';
                                            echo '<td>' . e($p->url) . '</td>';
                                            echo '<td>' .
                                                ($p->parent_id
                                                    ? e(
                                                        optional($pages->firstWhere('id', $p->parent_id))->title ??
                                                            'Main',
                                                    )
                                                    : 'Main') .
                                                '</td>';
                                            echo '<td><i class="' . e($p->icon) . '"></i></td>';
                                            echo '<td>
                                                    <button class="btn btn-icon toggle-status ' .
                                                ($p->isshown ? 'btn-success' : 'btn-danger') .
                                                '" 
                                                        data-id="' .
                                                $p->id .
                                                '" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                                        data-status="' .
                                                ($p->isshown ? 0 : 1) .
                                                '"  title="' .
                                                ($p->isshown ? 'Click here to disable' : 'Click here to enable') .
                                                '">
                                                        <i class="fas ' .
                                                ($p->isshown ? 'fa-eye' : 'fa-eye-slash') .
                                                '"></i>
                                                    </button>
                                                    <button  class="btn btn-info btn-icon editPage" title="Edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="' .
                                                $p->id .
                                                '">
                                                        <i class="bx bx-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-icon delete"  title="Delete" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-id="' .
                                                $p->id .
                                                '">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>';
                                            echo '</tr>';

                                            // Recursive call for child pages
                                            renderPageRows($pages, $p->id, $level + 1);
                                        }
                                    }
                                @endphp

                                {{-- Start recursion for root level (parent_id = 0) --}}
                                @php renderPageRows($pages, 0, 0); @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ✅ Right Section (View Tree) --}}

            <div class="col-md-4" id="viewSection">
                <div class="card rounded-3 mt-4 border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 bg-white pb-0">
                        <h6 class="fw-semibold text-secondary mb-0">View:</h6>
                        <button class="btn btn-primary" id="btnSortingRight">
                            <i class="fas fa-sort me-1"></i> Sorting
                        </button>
                    </div>

                    <div class="card-body pt-3">
                        <div id="menuTree">
                            <ul>
                                @foreach ($pages->where('parent_id', 0) as $menu)
                                    <li data-jstree='{"icon" : "{{ $menu->icon ?? 'bx bx-folder' }}"}'>
                                        {{ $menu->title }}
                                        @php $children = $pages->where('parent_id', $menu->id); @endphp
                                        @if ($children->count() > 0)
                                            <ul>
                                                @foreach ($children as $child)
                                                    <li data-jstree='{"icon" : "{{ $child->icon ?? 'bx bx-file' }}"}'>
                                                        {{ $child->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ✅ Sorting Section (Hidden by Default) --}}
            <div class="col-6" id="sortingSection" style="display:none;">
                <div class="card mt-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Sorting Order</h5>
                        <hr />

                        <button type="button" id="btnCloseSort"
                            class="btn btn-danger d-flex align-items-center justify-content-center p-0"
                            data-bs-dismiss="modal" aria-label="Close" style="width: 32px; height: 32px;">
                            <i class='bx bx-x fs-5'></i>
                        </button>
                    </div>
                    <div class="card-body row">

                        <div class="col-md-8">

                            <select id="ddlMenuForSorting" class="form-select mb-3">
                                <option value="" disabled selected> Select Menu</option>
                                <option value="0">Root Menu</option>
                                @foreach ($pages->where('parent_id', 0) as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                            <ul id="sortable" class="list-group mb-3"></ul>
                            <button class="btn btn-primary" id="btnSaveSort">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ Add/Edit Form Section (Hidden by Default) --}}
            <div class="col-12" id="formSection" style="display:none;">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong id="formTitle">Add New Page</strong>

                        <button id="btnCancel" type="button"
                            class="btn btn-danger d-flex align-items-center justify-content-center p-0"
                            data-bs-dismiss="modal" aria-label="Close" style="width: 32px; height: 32px;">
                            <i class='bx bx-x fs-5'></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="pageForm" method="POST" action="{{ route('admin.pages.store') }}">
                            @csrf
                            <input type="hidden" name="hId" id="hId" value="0">

                            <div class="row mb-3">

                                <div class="col-md-4">
                                    <label>Menu</label>
                                    <select class="form-select" name="ddlMenu" id="ddlMenu">
                                        <option value="0">Root Menu</option>

                                        @foreach ($pages->where('parent_id', 0) as $parent)
                                            <option value="{{ $parent->id }}" style="font-weight:bold; color: #0d6efd;">
                                                {{ $parent->title }}
                                            </option>

                                            @php
                                                $children = $pages->where('parent_id', $parent->id);
                                            @endphp

                                            @foreach ($children as $child)
                                                <option value="{{ $child->id }}">--> {{ $child->title }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="txtName" id="txtName" required>
                                </div>
                                <div class="col-md-4">
                                    <label>URL</label>
                                    <input type="text" class="form-control" name="txtUrl" id="txtUrl">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Icon</label>
                                    <select class="form-select" name="ddlIcon" id="ddlIcon">
                                        <option value="">Select Icon</option>
                                        @foreach ($icons as $icon)
                                            <option value="{{ $icon->class }}">{{ $icon->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="iconPreview" class="mt-3" style="display:none;">
                                <i id="previewIcon" class="fs-1"></i>
                                <div class="text-muted small mt-1">Icon Preview</div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">Save</button>
                                <button type="button" class="btn btn-danger" id="btnCancelForm">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
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
    <script src="{{ asset('assets/vendor/libs/jstree/jstree.js') }}"></script>
    {{-- ✅ DataTables + JSTree + jQuery UI --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/jstree.min.js"></script>
    @if (session('success'))
        <script>
            toaster_message(@json(session('success')), "success");
        </script>
    @endif

    @if (session('error'))
        <script>
            toaster_message(@json(session('error')), "error");
        </script>
    @endif

    <script>
        $(document).ready(function() {

            // DataTable setup
            $('#admin-pages_tbl').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                lengthChange: true,
                order: [
                    [0, 'asc']
                ]
            });

            // Add button
            $('#btnAddNew').click(() => {
                $('#formSection').slideDown();
                $('#formTitle').text('Add New Page');
                $('#hId').val(0);
                $('#pageForm')[0].reset();

                $("#ddlMenu").trigger('focus');
            });

            $('#btnCancel').click(() => $('#formSection').slideUp());


            // Edit
            $(document).on('click', '.editPage', function() {
                let id = $(this).data('id');
                $.post("{{ route('admin.pages.edit') }}", {
                    id,
                    _token: '{{ csrf_token() }}'
                }, function(data) {

                    showForm();

                    // Now populate the form
                    $('#formTitle').text('Edit Page');
                    $('#hId').val(data.id);
                    $('#ddlMenu').val(data.parent_id);
                    $('#txtName').val(data.title);
                    $('#txtUrl').val(data.url);
                    $('#ddlIcon').val(data.icon);
                });
            });


            // Delete icon
            $(document).on("click", ".delete", function() {
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
                            url: aurl + "/admin-pages/delete",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                if (data.status) {
                                    toaster_message(data.message, data.icon, data
                                        .redirect_url, aurl);
                                } else {
                                    toaster_message(data.message, data.icon, data
                                        .redirect_url, aurl);
                                }

                            },
                            error: function(error) {
                                swalWithBootstrapButtons.fire('Cancelled',
                                    'this data is not available :)', 'error')
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

    // Hide & remove any active tooltip to prevent stacking
    btn.tooltip('hide');
    $(".tooltip").remove();

    $.ajax({
        url: '/admin-pages/toggle-status',
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

            // Dispose old tooltip instance if any
            try { btn.tooltip('dispose'); } catch (e) {}

            // Toggle button state and tooltip title
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

            // Reinitialize tooltip on the same button
            btn.tooltip({ container: 'body' });

            // Show toaster alert
            toaster_alert_action(response.message, response.icon);
        },
        error: function (xhr) {
            console.error(xhr);
            alert('Request failed. Check console for details.');
        }
    });
});


            // Sorting
            $('#ddlMenuForSorting').change(function() {
                let parentid = $(this).val();

                $.post('/admin-pages/get-sorting', {
                    parentid,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#sortable').empty();

                    if (data) {
                        let arr = data.split('^');
                        arr.forEach((item, i) => {
                            let parts = item.split('-');

                            $('#sortable').append(
                                '<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center" ' +
                                'data-id="' + parts[0] + '">' +
                                '<span><i class="fas fa-sort"></i> ' + (i + 1) + '. ' +
                                parts[1] + '</span>' +
                                '</li>'
                            );
                        });

                        // Enable jQuery UI Sortable
                        $('#sortable').sortable();
                    }
                });
            });


            $('#btnSaveSort').click(function() {
                let order = [];
                $('#sortable li').each(function(i, li) {
                    order.push((i + 1) + '^' + $(li).data('id'));
                });

                $.post('/admin-pages/save-sorting', {
                    order,
                    _token: '{{ csrf_token() }}'
                }, function() {
                    $('#sortingSection').hide();
                    toaster_alert_action('Sorting updated successfully!', 'success');
                    $('#tableSection').show();
                    $('#viewSection').show();

                });
            });

            // JSTree
            $('#menuTree').jstree({
                core: {
                    themes: {
                        responsive: true,
                        "variant": "large",
                        "dots": true,
                        "icons": true
                    }
                },
            });

            // auto open first-level menus
            $('#menuTree').on('ready.jstree', function() {
                $('#menuTree').jstree('open_all');
            });


            // ✅ Hide/Show Logic
            function showTable() {
                $('#formSection, #sortingSection').hide();
                $('#tableSection, #viewSection').fadeIn(300);
            }

            function showForm() {
                $('#tableSection, #viewSection, #sortingSection').hide();
                $('#formSection').fadeIn(300);
            }

            function showSorting() {
                $('#tableSection, #viewSection').hide();
                $('#sortingSection').fadeIn(300);
            }

            // Button actions
            $('#btnAddNew').click(showForm);
            $('.editPage').click(showForm);
            $('#btnCancel, #btnCancelForm').click(showTable);
            $('#btnSorting, #btnSortingRight').click(showSorting);
            $('#btnCloseSort').click(showTable);

        });

        //Icon Preview
        $('#ddlIcon').on('input', function() {
            const iconClass = $(this).val().trim();

            if (iconClass) {
                $('#previewIcon')
                    .attr('class', iconClass + ' fs-1'); // update icon + keep large size
                $('#iconPreview').show();
            } else {
                $('#iconPreview').hide();
            }
        });
    </script>

@endsection
