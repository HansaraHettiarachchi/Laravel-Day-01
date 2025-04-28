$(document).ready(function () {
    $('#imagePreview').click(() => {
        $('#imageUpload').click();
    });

    $('#createBtn').click((e) => {
        e.preventDefault();

        var formData = new FormData($('#productForm')[0]);

        // formData.append('img', $('#imageUpload')[0].files[0]);

        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            type: "POST",
            url: "/save-product",
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response == "Product Already exist with this code") {
                    alert(response);
                } else if (response == "Success") {
                    window.location.href = '/product-list';
                }
            },
            error: (error) => {
                console.log(error);
                alert('Something went wrong');
            }
        });
    });

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
        columns: [
            { data: "No" },
            { data: "Name" },
            { data: "Code" },
            { data: "Cost" },
            { data: "Price" },
            { data: "Picture" },
            { data: "Action" },
            { data: "Delete" }

        ],
        columnDefs: [
            { targets: [4], className: 'text-center text-danger' }
        ],
        dom: 'Bfrtip',
        buttons: [
            'print'
        ]
    });

    $('#productTable').on('click', '.editProductBtn', function () {
        window.location = 'edit-products?id=' + $(this).data('id');
    });

    $('#productTable').on('click', '.deleteProductBtn', function () {

        const user_id = $(this).data('id');
        if (confirm('Are you sure....?')) {
            $.ajax({
                url: `/delete-products?id=${user_id}`,
                type: "GET",
                success: function (response) {
                    if (response == "Product Deleted Succesfully") {
                        alert(response);
                        $('#productTable').DataTable().ajax.reload();
                    }
                },
                error: function (xhr) {
                    alert('Failed to delete. Please try again later.');
                }
            });
        }
    });
});