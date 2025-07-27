<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reset Your Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>

</head>

<body style="background-color: #e9ecef; color: #ffffff; ">
    <!-- start body -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto;">
        <!-- start card view -->
        <tr>
            <td align="center" style="padding: 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%"
                    style="max-width: 600px; background-color: #fff; border-radius: 10px; overflow: hidden;">
                    <tr>
                        <td align="left" style="padding: 10px;">
                            <h1 style="font-weight: 700; text-align: center; margin-bottom: 10px; color: #000;">
                                Forgot your Password?
                            </h1>
                            <h2
                                style="font-size: 18px; font-weight: 500; text-align: center; color: #891AB4; margin: 0;">
                                No worries, let’s get you a new one!
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 15px; font-size: 16px; color: #fff;">
                            <p style="margin: 0; background-color: #d10000; padding: 0.5rem; border-radius: 5px;">This
                                link is valid for one-time use only and will expire in 30 minutes.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 15px">
                            <p style="font-size: 16px; text-align: center; margin: 10px 0; color: #000;">
                                Hello,
                            </p>
                            <p style="font-size: 16px; line-height: 1.5; text-align: center; color: #000;">
                                We received a request to reset your password. Click the button
                                below to choose a new one. If you didn't make this request, simply ignore this email.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 15px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#891AB4" style="border-radius: 5px;">
                                        <a href="{{ $link }}" target="_blank"
                                            style="display: inline-block; padding: 10px 30px;  font-size: 18px; color: #ffffff; text-decoration: none; font-weight: 700;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="display: block; margin-top: 10px; color:#555555; padding: 15px;">
                            <div style="background-color: #f6dfff; padding: 1rem; border-radius: 10px;">
                                <img src="{{ $appUrl }}/assets/img/favicon/logo_full.png" alt="Logo"
                                    style="margin-bottom: 20px;">
                                <p>
                                    <b style="color: #891AB4;">Address : </b>
                                    23, Maruti Plaza, Krishnanagar Road, Ahmedabad - 380038
                                    <br>
                                    <b style="color: #891AB4;">Phone No : </b>
                                    90338 89372
                                    <br>
                                    <b style="color: #891AB4;">Send Mail : </b>
                                    <a href="mailto:info@skysphereinfosoft.com"
                                        style="color: #891AB4; font-weight: 600; text-decoration: none;">
                                        info@skysphereinfosoft.com
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
