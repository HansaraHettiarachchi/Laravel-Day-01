<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data Form</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Insert Data</h2>
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="add" placeholder="Enter your address" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email"  placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="ps" placeholder="Enter your password" required>
            </div>
            <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
        </form>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function () {
             var email = $('#email').val();
             var ps = $('#ps').val();
             var name = $('#name').val();
             var address = $('#add').val();

            $.ajax({
                url: "/create-user",
                type:"POST",
                data: {
                    e:email,
                    ps:ps,
                    n:name,
                    add:address,
                     _token: '{{ csrf_token() }}'
                },
                success: function (rs) {
if ("User Created Successfully" == rs) {
    window.location= "/user-list";
}

                },
                error:function(e){
console.log(e);

                }
            });
             
            });
        });
    </script>
</body>
</html>