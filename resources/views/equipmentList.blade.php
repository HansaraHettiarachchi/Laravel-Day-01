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
</head>

<body>
    <div class="container-fluid">
        <table class="table" id="productTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
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
            $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/all-products",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
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
                buttons: [
                    'print'
                ]
            });

            $('#productTable').on('click', '.editProductBtn', function() {
                window.location = 'edit-products?id=' + $(this).data('id');
            });

            $('#productTable').on('click', '.deleteProductBtn', function() {

                const user_id = $(this).data('id');
                if (confirm('Are you sure....?')) {
                    $.ajax({
                        url: `/delete-products?id=${user_id}`,
                        type: "GET",
                        success: function(response) {
                            if (response == "Product Deleted Succesfully") {
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
        });
    </script>
</body>

</html>
