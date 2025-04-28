<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="text-center mb-4 text-success">
                            Update Product
                            <div class="mx-auto bg-success" style="height: 3px; width: 80px;"></div>
                        </h1>

                        {!! Form::open(['url' => 'update-products', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate' , 'enctype' => 'multipart/form-data']) !!}

                        <div class="row g-3">
                            <div class="col-md-6">
                                {!! Form::label('name', 'Product Name', ['class' => 'form-label']) !!}

                                {!! Form::hidden('id', $data->id, [
                                    'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                    'required',
                                ]) !!}
                                {!! Form::text('name', $data->name, [
                                    'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('code', 'Product Code', ['class' => 'form-label']) !!}
                                {!! Form::text('code', $data->code, [
                                    'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('cost', 'Product Cost ($)', ['class' => 'form-label']) !!}
                                {!! Form::number('cost', $data->cost, [
                                    'class' => 'form-control' . ($errors->has('cost') ? ' is-invalid' : ''),
                                    'step' => '0.01',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('price', 'Product Price ($)', ['class' => 'form-label']) !!}
                                {!! Form::number('price', $data->price, [
                                    'class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''),
                                    'step' => '0.01',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="mb-3">
                                {!! Form::label('file', 'Choose a File:', ['class' => 'form-label']) !!}
                                {!! Form::file('file', ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            {!! Form::button('<i class="fas fa-undo me-2"></i>Reset', [
                                'type' => 'reset',
                                'class' => 'btn btn-outline-secondary px-4',
                            ]) !!}

                            {!! Form::button('<i class="fas fa-plus-circle me-2"></i>Update Product', [
                                'type' => 'submit',
                                'class' => 'btn btn-success px-4',
                            ]) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form Validation -->
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>
