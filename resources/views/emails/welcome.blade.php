<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h3>Hi {{ $data['name'] }}, we’re glad you’re here! Following are your account details: <br></h3>
    <h3>Email: </h3>
    <p>{{ $data['email'] }}</p>
</body>

</html>
