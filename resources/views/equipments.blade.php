<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title>Document</title>
</head>

<body>

    <div class="container-fluid">
        <h2 class="text-success mt-4 ms-4">Equipments</h2>
        <div class="col-8 offset-2 my-5">
            {!! Form::open(['url' => 'insert-equips', 'method' => 'POST']) !!}
            <div class="row g-3">
                <div class="col">
                    <label for="inputEmail4" class="form-label fw-4">Code</label>
                    <input type="text" class="form-control" id="inputEmail4" name="code" required>
                </div>
                <div class="col">
                    <label for="inputPassword4" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputPassword4" name="name" required>
                </div>
                <div class="col">
                    <label for="inputAddress" class="form-label">Price</label>
                    <input type="number" class="form-control" id="inputAddress" name="price" required>
                </div>
            </div>

            <div class="row my-4">
                <label for="product">Selete Product</label>
                <div class="col-10">
                    <select id="productSelect" class="form-control">
                        <option value="0" data-id='0' selected>Select</option>
                        @foreach ($products as $item)
                            <option value="{{ $item->id }}" data-id="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-dark float-end" id="addBtn" type="button">Add</button>
                </div>
            </div>

            <div class="row my-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Cost</th>
                            <th scope="col">SubTotal</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbBodyData">

                    </tbody>

                    <tfoot>
                        <tr>
                            <th class="text-danger fs-5 ">Total</th>
                            <td></td>
                            <td></td>
                            <td id="totQty"></td>
                            <td></td>
                            <td id="totSubCost"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>



            <div class="col-12">
                <button class="btn btn-danger fs-5 float-end" id="submit">Submit</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(() => {

            var rowCount = 0;

            $('#productSelect').select2();

            $('#addBtn').click(() => {
                const select_id = $('#productSelect option:selected').data('id');

                if (select_id != "0" || select_id != 0) {
                    $.ajax({
                        url: "/get-product-by-id?id=" + select_id,
                        type: "GET",
                        success: (rs) => {
                            const data = JSON.parse(rs);

                            if ($(`#tbRow_${data.id}`).length) {

                                $(`#tbRow_${data.id} .rQty`).val(parseFloat($(
                                        `#tbRow_${data.id} .rQty`)
                                    .val()) + 1);
                                calTot();
                                // alert("This product is already in the table");
                                return;
                            }

                            if ("Procut not found" != rs) {
                                rowCount++;

                                $('#tbBodyData').append(

                                    `
                                    <tr id = 'tbRow_${data.id}'>
                                        <th scope="row">
                                            ${rowCount}
                                            </th>
                                    <td>
                                      <input type="hidden" name = 'rPId[]' value='${data.id}'>
                                        
                                        ${data.name}</td>
                                    <td>${data.code}</td>
                                    <td><input type="number" class="form-control rQty text-center" value='1' name = "rQty[]">
                                        </td>
                                    <td><input type="number" class="form-control rcost text-center" name = "rCost[]"
                                          value='${data.cost}'>
                                    </td>
                                    <td class="subTot text-center" >
                                        
                                        ${data.cost}</td>
                                    <td>
                                         <button type="button" class="form-control btn btn-danger rBtn" data-id='tbRow_${data.id}'>Remove</button>
                                        
                                        </td>
                        </tr>
            `
                                );

                                calTot();
                            }

                        },
                        error: (e) => {
                            console.log(e);

                        }

                    })
                } else {
                    alert("Please select product first");
                }

            });

            $(document).on('input', '.rQty', function() {
                const qty = $(this).val();
                const cost = $(this).closest('tr').find('.rcost').val();
                const subtotal = (qty * cost).toFixed(2);

                $(this).closest('tr').find('.subTot').text(subtotal);

                calTot();
            });

            $(document).on('input', '.rcost', function() {
                const cost = $(this).val();
                const qty = $(this).closest('tr').find('.rQty').val();
                const subtotal = (qty * cost).toFixed(2);

                $(this).closest('tr').find('.subTot').text(subtotal);

                calTot();
            });



            $('#tbBodyData').on('click', '.rBtn', function() {
                $('#' + $(this).data('id')).remove();

                calTot();
                reNumber();
            });

            const calTot = () => {

                let totalCost = 0;
                let totalQty = 0;

                $('.subTot').each(function() {
                    totalCost += parseFloat($(this).text());
                });

                $('.rQty').each(function() {
                    totalQty += parseFloat($(this).val());
                });


                $('#totSubCost').text(totalCost.toFixed(2));
                $('#totQty').text(totalQty);

            }

            const reNumber = () => {
                let rowCount = 0;

                $('#tbBodyData tr').each(function(index) {
                    rowCount++;
                    $(this).find('th:first').text(rowCount);
                });
            }

            $('#submit').click(function() {

            });
        });
    </script>
</body>

</html>
