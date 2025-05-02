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
    <title>Create Product</title>
    <style>
        .form-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .form-title {
            color: #28a745;
            margin-bottom: 25px;
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
        }

        .form-title:after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 80px;
            height: 3px;
            background-color: #28a745;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .upload-icon {
            font-size: 50px;
            color: #6c757d;
        }

        .btn-custom {
            background-color: #28a745;
            border-color: #28a745;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-custom:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>

<body>

    @yield('content')


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <h1 class="text-center form-title">Create New Product</h1>

                    <form class="row g-3" id="productForm" method="POST">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">Product Code</label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cost" class="form-label">Product Cost ($)</label>
                            <input type="number" step="0.01" class="form-control" name="cost" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Product Price ($)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Product Images</label>
                            <div class="image-preview" id="imagePreview">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            </div>
                            <input type="file" class="form-control d-none" id="imageUpload" name ="imageUpload"
                                accept="image/*" multiple>
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-4">
                            <button type="reset" class="btn btn-outline-secondary">Reset Form</button>
                            <button type="button" class="btn btn-custom" id="createBtn">
                                <i class="fas fa-plus-circle me-2"></i>Create Product
                            </button>
                        </div>

                    </form>
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

    <script src="{{ asset('/js/script.js') }}"></script>
    <script src="{{ asset('/js/logout.js') }}"></script>

</body>

</html>
