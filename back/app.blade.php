<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TeleDoctor | @yield('title')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('panel/css/styles.css') }}">


    <link rel="shortcut icon" href="{{ asset('assets/images/fav.png') }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Common Stylesheet -->
<link rel="stylesheet" href="{{ asset('css/common-style.css') }}">


    @yield('css')

</head>

<body class="sb-nav-fixed">



    @include('layouts.back.header')

    @include('layouts.back.sidebar')

    @yield('content')


    @include('layouts.back.footer')



    

    @include('layouts.back.script')

    @yield('js')


    <script>
    @if (Session::has('success'))
      successtoast('{{ Session::get('success') }}');
    @endif
    @if (Session::has('error'))
      errortoast('{{ Session::get('error') }}');
    @endif

    $(document).on('click', '.mark_as_read', function (e) {
        e.preventDefault();
        var href = $(this).attr('data-href');
        var this_el = $(this);

        $.ajax({
            type: 'POST',
            url: href,
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function (data) {
                // console.log(data);
                if (data.code == '200') {
                    successtoast(data.message);
                    $(this_el).closest('.notification_tr').hide();
                    $('.note').text(data.notification_count);
                }
                else {
                    errortoast(data.message);
                }
            }
        });
    });
    </script>





</body>

</html>
