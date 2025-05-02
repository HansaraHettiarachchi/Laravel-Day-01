@include('header')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.css">
    <title>View Product</title>
    <style>
        tr {
            cursor: pointer;
        }
    </style>
</head>

<body>
    @yield('content')

    <div class="container">

        <div class="my-4 row">
            <div class="col-4">
                <label for="catName" class="form-label">Price</label>
                <select name="catName" id="catName" class="form-control">
                    <option value="0" data-id='0' selected>Select</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}" data-id="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <button class="btn btn-danger my-4" id="filterBtn">Filter</button>
            </div>
            <div class="col-2">
                <button class="btn btn-success my-4" id="createEquipment">Create Equipment</button>
            </div>
        </div>

        <table class="table" id="productTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <!-- Product Detail Modal -->
    <div class="">

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="productModalLabel">Product Details</h1>
                        <button type="button" class="btn btn-warning ms-3" id="printModalBtn">Print</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <div class="col-12 row my-3" id="dataDiv">

                        </div>

                        <table class="table border border-2" id="equipItems">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Cost</th>
                                    <th scope="col">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody id="equipItemsBody">

                            </tbody>

                            {{-- <tfoot>
                                <tr>
                                    <th class="text-danger fs-5 ">Total</th>
                                    <td></td>
                                    <td></td>
                                    <td id="totQty"></td>
                                    <td></td>
                                    <td id="totSubCost"></td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"
        integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/load-equips",
                    type: "POST",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.cat_id = $('#catName').val();
                    }
                },
                columns: [{
                        data: "No"
                    },
                    {
                        data: "Name"
                    },
                    {
                        data: "Code"
                    },
                    {
                        data: "Price"
                    },
                    {
                        data: "Quantity"
                    },
                    {
                        data: "Category"
                    },
                    {
                        data: "Action"
                    },
                    {
                        data: "Delete"
                    }
                ],
                columnDefs: [{
                    targets: [3],
                    className: 'text-center text-danger'
                }],
                dom: 'Bfrtip',
                buttons: ['print']
            });

            $('#productTable tbody').on('click', 'tr', function(e) {

                if ($(e.target).hasClass('editProductBtn') ||
                    $(e.target).hasClass('deleteProductBtn') ||
                    $(e.target).closest('.editProductBtn').length ||
                    $(e.target).closest('.deleteProductBtn').length) {
                    return;
                }

                var data = table.row(this).data();
                if (data) {

                    $('#dataDiv').html(
                        `
                        <div class="col fs-6">
                            <h5>Equipment Name : ${data.Name}</h5>
                            <h5>Equipment Code : ${data.Code}</h5>
                            <h5>Equipment Price : ${data.Price}</h5>
                        </div>
                        <div class="col">
                            <h5>Total Product Quantity : ${data.Quantity}</h5>
                            <h5>Category : ${data.Category}</h5>
                        </div>
                        `
                    );


                    $.ajax({
                        url: "load-equips-items",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: data.Code
                        },
                        success: function(rs) {
                            var data = JSON.parse(rs);
                            $('#equipItemsBody ').empty();
                            for (const item of data) {
                                $('#equipItemsBody').append(
                                    `
                                <tr>
                                <th>${item.No}</th>
                                <td>${item.name}</td>
                                <td>${item.code}</td>
                                <td>${item.qty}</td>
                                <td>${item.cost}</td>
                                <td>${item.subtot}</td>
                                 
                            </tr>
                                    `
                                );
                            }

                        },
                        error: function(e) {
                            console.log(e);

                        }
                    });

                    var modal = new bootstrap.Modal($('#productModal'));
                    modal.show();
                }
            });


            $('#filterBtn').click(function() {
                table.ajax.reload();
            });

            $('#productTable').on('click', '.editProductBtn', function(e) {
                e.stopPropagation();
                window.location = 'edit-equip?id=' + $(this).data('id');
            });

            $('#productTable').on('click', '.deleteProductBtn', function(e) {
                e.stopPropagation();
                const user_id = $(this).data('id');
                if (confirm('Are you sure....?')) {
                    $.ajax({
                        url: `/delete-equip?id=${user_id}`,
                        type: "GET",
                        success: function(response) {
                            if (response == "Equip Deleted Succesfully") {
                                alert(response);
                                $('#productTable').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            alert('Failed to delete. Please try again later.');
                        }
                    });
                }
            });

            $("#createEquipment").click(function() {
                window.location = "/equipments-create";
            });

            $('#printModalBtn').click(function() {
                const modalContent = $('.modal-body').clone();

                const printStyles = `
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                .print-content, .print-content * {
                    visibility: visible;
                }
                .print-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 20px;
                }
                table {
                    border-collapse: collapse !important;
                    width: 100% !important;
                }
                table, th, td {
                    border: 1px solid #000 !important;
                }
                .border, .border-2 {
                    border-width: 2px !important;
                }
                #dataDiv {
                    margin: 15px 0;
                    padding: 10px;
                    border: 1px solid #000 !important;
                }
            }
            @page {
                size: auto;
                margin: 10mm;
            }
        </style>
    `;

                const printContainer = $('<div class="print-content">')
                    .append(printStyles)
                    .append(modalContent);

                $('body').append(printContainer);
                window.print();

                setTimeout(function() {
                    printContainer.remove();
                }, 500);
            });

        });
    </script>
    <script src="{{ asset('/js/logout.js') }}"></script>

</body>

</html>
