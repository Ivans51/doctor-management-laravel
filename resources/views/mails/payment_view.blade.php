<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>My Company Newsletter</title>
    <style>
        /* Inline CSS styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .content {
            background-color: #fff;
            padding: 20px;
        }

        ul {
            padding-left: 25px;
            list-style: square;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="header">
    {{-- TODO: update logo url --}}
    {{--<img src="https://beo.comcaz.com/beo/app_metro/public/demo1/media/logos/logo-beo.png" alt="My Company Logo">--}}
</div>
<div class="content">
    <h1>¡Gracias por tu pago!</h1>
    <p>Resumen:</p>
    <ul>
        <li>
            <strong>Doctor: </strong>
            {{ $data->doctor->name }}
        </li>
        <li>
            <strong>Nombre de la especialidad: </strong>
            {{ $data->medicalSpecialty->name }}
        </li>
        <li>
            <strong class="ml-2">Monto: </strong>
            ${{ number_format($data->medicalSpecialty->price, 2, ',', '.') }} USD
        </li>
        <li>
            <strong>Fecha: </strong>
            {{ date('d-m-Y', strtotime($data->schedule->date)) }}
        </li>
        <li>
            <strong>Hora: </strong>
            {{ date('H:i', strtotime($data->schedule->start_time)) }}
        </li>
    </ul>

    <div class="row mt-5">
        <div class="col-md-12">
            <strong>Total: </strong>
            ${{ number_format($data->medicalSpecialty->price, 2, ',', '.') }} USD
        </div>
    </div>
</div>
<div class="footer">
    <p>Doctor Management &copy; {{ \Carbon\Carbon::now()->year }}</p>
</div>
</body>
</html>
