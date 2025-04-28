<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Bootstrap 5 Table Example</h2>
        <table class="table table-bordered table-hover" id="tbData">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Address</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($userList as $user) 

                @if ($user->status == 'Active')
                <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->password }}</td>
                <td>{{ $user->address }}</td>
                <td><button class="btn btn-danger editBtn" data-id="{{ $user->id }}" >Edit</button></td>
                <td><button class="btn btn-danger deleteBtn" data-id="{{ $user->id }}" >Delete</button></td>
                </tr>
                
                @endif
                @endforeach --}}
            </tbody>
        </table>


    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //         $(document).ready( function () {

        //             $('#tbData').DataTable(
        //             );

        //             $(".editBtn").click( function () {
        //                 const user_id =$(this).data('id');

        //                 window.location = "/edit-user?id="+user_id;

        //             });

        //            $(".deleteBtn").click(function () {
        //     const user_id = $(this).data('id');

        //     $.ajax({
        //         url: `/delete-user?id=${user_id}`,
        //         type: "get",
        //         success: function (rs) {
        //             $(`.deleteBtn[data-id="${user_id}"]`).closest('tr').remove();
        //         },
        //         error: function (e) {
        //             alert('Failed to delete. Please try again later.');
        //             console.log(e);
        //         }
        //     });
        // });



        // });

        $(document).ready(function() {
            $('#tbData').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/user-data",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: "Index"
                    },
                    {
                        data: "Name"
                    },
                    {
                        data: "Email"
                    },
                    {
                        data: "Password"
                    },
                    {
                        data: "Address"
                    },
                    {
                        data: "Action",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "Status",
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: [5, 6],
                    className: 'text-center'
                }],
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ]
            });

            // Edit button handler (using event delegation)
            $('#tbData').on('click', '.editBtn', function() {
                const user_id = $(this).data('id');
                window.location = "/edit-user?id=" + user_id;
            });

            // Delete button handler
            $('#tbData').on('click', '.deleteBtn', function() {
                const user_id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.ajax({
                        url: `/delete-user?id=${user_id}`,
                        type: "GET",
                        success: function(response) {
                            $('#tbData').DataTable().ajax.reload();
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
