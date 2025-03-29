<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Module Frame</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #cfcedd;
        }
        .header,
        .footer {
            background-color:  #cfcedd;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
@include('email.subviews.header')
<div class="container">
    <div class="row">
        <div class="col text-center">
            <h3>Email Content</h3>
            <p>This is the content of your email. Feel free to modify it as needed.</p>
        </div>
    </div>
</div>
@include('email.subviews.footer')
</body>
</html>
