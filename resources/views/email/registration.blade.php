<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background-color: #fff5f0;
            /* Matches your body background */
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #ffe0d0;
        }

        .header {
            background-color: #ff6b6b;
            /* Your signature coral color */
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }

        .content {
            padding: 30px;
            color: #444;
            line-height: 1.6;
        }

        .user-greeting {
            color: #ff6b6b;
            font-size: 20px;
            font-weight: bold;
        }

        .details-box {
            background-color: #fffcfb;
            border: 2px dashed #ff6b6b;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .details-box p {
            margin: 5px 0;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            color: #666;
            width: 100px;
            display: inline-block;
        }

        .btn-order {
            display: inline-block;
            background-color: #ff6b6b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .important-note {
            background: #ffebee;
            border: 1px solid #ef5350;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🍕 Your Food Journey Begins!</h1>
        </div>

        <div class="content">
            <p class="user-greeting">Hello {{ $user->full_name }},</p>
            <p>Welcome to <strong>EatWays</strong>! We're excited to help satisfy your cravings. Your account has been successfully created.</p>

            <div class="details-box">
                <h3 style="margin-top: 0; color: #ff6b6b;">Your Account Details</h3>
                <p><span class="label">Email:</span> {{ $user->username }}</p>
                <p><span class="label">Password:</span> {{ $plainPassword }}</p>
                <p><span class="label">Role:</span> {{ ucfirst($accountType) }}</p>
            </div>

            <div class="important-note">
                <p style="margin: 0;"><strong>⚠️ IMPORTANT:</strong> Please save your login credentials securely. This is the only time your password will be sent to you.</p>
            </div>

            <p>Ready to eat? Click the button below to start exploring local favorites.</p>

            <div style="text-align: center;">
                <a href="{{ url('/user/signin') }}" class="btn-order">Start Ordering Now</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} EatWays. All rights reserved.<br>
            This is an automated message. Please do not reply to this email
        </div>
    </div>
</body>

</html>