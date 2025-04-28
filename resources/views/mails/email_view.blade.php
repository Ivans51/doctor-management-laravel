<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        .button {
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Hi,</h2>
    <p>{{ $data['message'] }}</p>
    <a href="{{ $data['action_url'] }}" class="button">{{ $data['action_text'] }}</a>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p style="font-size:12px;color:#888;">If the button doesn't work, copy and paste this link into your browser:</p>
    <p style="font-size:12px;">{{ $data['action_url'] }}</p>
</body>
</html>
