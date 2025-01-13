<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-body p {
            line-height: 1.6;
            margin: 0 0 16px;
        }
        .token-box {
            text-align: center;
            margin: 20px 0;
        }
        .token-box span {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            background-color: #f0f8ff;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
            color: #666666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Verify Your Email</h1>
        </div>
        <div class="email-body">
            <p>Hi,</p>
            <p>Thank you for registering! To complete your registration, please use the verification token below:</p>
            <div class="token-box">
                <span>{{ $token }}</span>
            </div>
            <p>If you did not request this, please ignore this email.</p>
            <p>Thank you,<br>The Chatbot Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2025 Chatbot. All rights reserved.</p>
        </div>
    </div>
</body>
</html>