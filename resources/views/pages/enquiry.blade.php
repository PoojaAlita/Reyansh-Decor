@extends('layouts.master')
@section('title', 'Enquiries')

@section('plugin-stylesheet')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/comman/buttons.dataTables.min.css') }}">
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mt-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Enquiries</h5>
      <div class="d-flex align-items-center">
        <!-- Export Dropdown -->
        <div class="dropdown">
          <button class="btn btn-dark" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-download me-1"></i>
            Export
          </button>
          <ul class="dropdown-menu" aria-labelledby="exportDropdown">
            <li><a class="dropdown-item export-btn" data-export="copy" href="#">Copy</a></li>
            <li><a class="dropdown-item export-btn" data-export="csv" href="#">CSV</a></li>
            <li><a class="dropdown-item export-btn" data-export="excel" href="#">Excel</a></li>
            <li><a class="dropdown-item export-btn" data-export="pdf" href="#">PDF</a></li>
            <li><a class="dropdown-item export-btn" data-export="print" href="#">Print</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="card-datatable table-responsive pt-0">
      <table class="dt-responsive table table-bordered" id="enquiry_tbl">
        <thead class="table-light">
          <tr>
            <th width="5%">#</th>
            <th>Date</th>
            <th>Customer Info</th>
            <th>Product</th>
            <th>Message</th>
            <th>Status</th>
            <th width="10%">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach($enquiries as $key => $enquiry)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $enquiry->created_at->format('d-M-Y H:i') }}</td>
            <td>
                <strong>{{ ucfirst($enquiry->name) }}</strong><br>
                <small>{{ $enquiry->email }}</small><br>
                <small>{{ $enquiry->phone }}</small>
            </td>
            <td>
                @if($enquiry->product)
                    <a href="{{ route('frontend.product', $enquiry->product->id) }}" target="_blank">
                        {{ $enquiry->product->name }}
                    </a>
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($enquiry->subject)
                    <strong>{{ $enquiry->subject }}</strong><br>
                @endif
                {{ Str::limit($enquiry->message, 100) }}
            </td>
            <td>
                <select class="form-select form-select-sm toggle-status" data-id="{{ $enquiry->id }}">
                    <option value="pending" {{ $enquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="responded" {{ $enquiry->status == 'responded' ? 'selected' : '' }}>Responded</option>
                    <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </td>
            <td>
              <button class="btn btn-danger btn-sm btn-icon delete"
                data-id="{{ $enquiry->id }}" title="Delete" data-bs-toggle="tooltip">
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

@endsection

@section('plugin-script')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/comman/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('assets/comman/jszip.min.js')}}"></script>
<script src="{{ asset('assets/comman/pdfmake.min.js')}}"></script>
<script src="{{ asset('assets/comman/vfs_fonts.js')}}"></script>
<script src="{{ asset('assets/comman/buttons.html5.min.js')}}"></script>
<script src="{{ asset('assets/comman/buttons.print.min.js')}}"></script>
<script>

var table = $('#enquiry_tbl').DataTable({
      dom:
          "<'row align-items-center mb-3'<'col-md-6 d-flex align-items-center'l><'col-md-6 text-end'f>>" +
          "rt" +
          "<'row mt-3'<'col-md-6'i><'col-md-6 text-end'p>>",

      buttons: [
          { extend: 'copy', className: 'd-none', name: 'copy', exportOptions: { columns: ':not(:last-child)' } },
          { extend: 'csv', className: 'd-none', name: 'csv', exportOptions: { columns: ':not(:last-child)' } },
          { extend: 'excel', className: 'd-none', name: 'excel', exportOptions: { columns: ':not(:last-child)' } },
          { 
            extend: 'pdf', 
            className: 'd-none', 
            name: 'pdf', 
            exportOptions: { columns: ':not(:last-child)' },
            customize: function (doc) {
                doc.content[0].alignment = 'center';
                doc.content[0].margin = [0, 0, 0, 15];
                var table = doc.content[1].table;
                var body = table.body;
                var colCount = body[0].length;
                table.widths = new Array(colCount).fill('*');
                body.forEach(function (row) {
                    row.forEach(function (cell) {
                        if (typeof cell === 'string') cell = { text: cell };
                        cell.border = [true, true, true, true];  
                        cell.margin = [5, 4, 5, 4];
                    });
                });
                doc.content[1].layout = {
                    hLineWidth: function () { return 0.8; },
                    vLineWidth: function () { return 0.8; },
                    hLineColor: function () { return '#000'; },
                    vLineColor: function () { return '#000'; },
                };
                doc.styles.tableHeader.fillColor = '#2C3E50';
                doc.styles.tableHeader.color = 'white';
                doc.styles.tableHeader.bold = true;
                doc.styles.tableHeader.fontSize = 11;
                doc.defaultStyle.fontSize = 10;
                doc.pageMargins = [20, 40, 20, 40];
            }
          },
          { extend: 'print', className: 'd-none', name: 'print', exportOptions: { columns: ':not(:last-child)' } }
      ]
});

$('.export-btn').on('click', function(e) {
    e.preventDefault();
    var type = $(this).data('export');
    table.button(type + ':name').trigger();
});

/* Delete */
$(document).on("click", ".delete", function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('enquiry.delete') }}",
                type: "POST",
                data: { id: id, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    if (data.status == 'success') {
                        toaster_message(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toaster_message(data.message, 'error');
                    }
                }
            });
        }
    })
});

/* Toggle Status */
$(document).on('change', '.toggle-status', function () {
    var id = $(this).data('id');
    var status = $(this).val();

    $.ajax({
        url: "{{ route('enquiry.toggleStatus') }}",
        type: 'POST',
        data: {
            id: id,
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status == 'success') {
                toaster_message(response.message, 'success');
            } else {
                toaster_message(response.message, 'error');
            }
        }
    });
});

</script>
@endsection
