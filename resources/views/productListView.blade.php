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
    <div class="container my-4">
        <div>
            <button class="btn btn-warning float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#productModal">Import</button>
        </div>
        <table class="table" id="productTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Cost</th>
                    <th>Price</th>
                    <th>Picture</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="">

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="productModalLabel">Import Products</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="rounded-5 border border-2 mx-4 d">
                            <div class="d-flex align-items-center justify-content-center" id="imagePreview"
                                style="height: 200px">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            </div>
                            <input type="file" class="form-control d-none" id="imageUpload" name ="imageUpload"
                                accept=".csv, text/csv" required>
                        </div>

                        <div class="" id="errorTable">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning ms-3 btnSubmitImport"
                            id="btnSubmitImport">Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Submitting In Laravel --}}
    {{-- <div style=" border-radius: 20px;" class="modal-content">
        {{ Form::open(['route' => ['test', 1], 'method' => 'POST', 'files' => true]) }}

        <div class="modal-header">
            <b style="color: #002250;">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('Edit & Update this Category') }}</h5>
            </b>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i
                        class="dripicons-cross"></i></span></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>{{ trans('file.name') }} *</label>
                    {{ Form::text('name', null, ['required' => 'required', 'class' => 'form-control']) }}
                </div>
                <input type="hidden" name="category_id">
                <div class="col-md-6 form-group">
                    <label>{{ trans('file.Image') }}</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label>{{ trans('file.Parent Category') }}</label>
                    <select data-live-search="true" data-live-search-style="begins" name="parent_id"
                        class="form-control selectpicker" id="parent">
                        <option value="" id="noParentCategoryId">No {{ trans('file.parent') }}</option>
                        @foreach ($categories_list as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input style="border-radius: 15px; background-color: #1c5cbd;" type="submit"
                    value="{{ trans('Submit Category Update') }}" class="btn btn-primary">
            </div>
        </div>
        {{ Form::close() }}

    </div> --}}

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

            $('#productModal').on('show.bs.modal', function(e) {
                $('#errorTable').html('');
                $('#imageUpload').val('');
            });

            $('.btnSubmitImport').on('click', function() {

                const file = $('#imageUpload')[0].files[0];

                if (file == null) {
                    alert("Please select file");
                    return;
                }

                var formData = new FormData();
                formData.append('data', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "/import-products",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        data = JSON.parse(rs);


                        if (data.status == 'error') {
                            $('#errorTable').html(data.data);
                        } else if (data.status == 'success') {
                            alert(data.data);
                            window.location.reload();
                        } else {
                            alert("An Error Conducted");
                        }

                    },
                    error: function(e) {
                        console.log(e);

                    }
                });
            });

        });
    </script>
    <script src="{{ asset('/js/script.js') }}"></script>
</body>

</html>
