<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Status Update Notification</title>
    <style type="text/css">
        *,
        ::after,
        ::before {
            box-sizing: border-box
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent
        }

        article,
        aside,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        main,
        nav,
        section {
            display: block
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        }

        @media (min-width:576px) {
            .container {
                max-width: 540px
            }

            .col-sm-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }
        }

        @media (min-width:768px) {
            .container {
                max-width: 720px
            }
        }

        @media (min-width:992px) {
            .container {
                max-width: 960px
            }
        }

        @media (min-width:1200px) {
            .container {
                max-width: 1140px
            }
        }

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        }

        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px
        }

        .col,
        .col-1,
        .col-10,
        .col-11,
        .col-12,
        .col-2,
        .col-3,
        .col-4,
        .col-5,
        .col-6,
        .col-7,
        .col-8,
        .col-9,
        .col-auto,
        .col-lg,
        .col-lg-1,
        .col-lg-10,
        .col-lg-11,
        .col-lg-12,
        .col-lg-2,
        .col-lg-3,
        .col-lg-4,
        .col-lg-5,
        .col-lg-6,
        .col-lg-7,
        .col-lg-8,
        .col-lg-9,
        .col-lg-auto,
        .col-md,
        .col-md-1,
        .col-md-10,
        .col-md-11,
        .col-md-12,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-md-auto,
        .col-sm,
        .col-sm-1,
        .col-sm-10,
        .col-sm-11,
        .col-sm-12,
        .col-sm-2,
        .col-sm-3,
        .col-sm-4,
        .col-sm-5,
        .col-sm-6,
        .col-sm-7,
        .col-sm-8,
        .col-sm-9,
        .col-sm-auto,
        .col-xl,
        .col-xl-1,
        .col-xl-10,
        .col-xl-11,
        .col-xl-12,
        .col-xl-2,
        .col-xl-3,
        .col-xl-4,
        .col-xl-5,
        .col-xl-6,
        .col-xl-7,
        .col-xl-8,
        .col-xl-9,
        .col-xl-auto {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529
        }

        .table td,
        .table th {
            padding: 0.5rem;
            vertical-align: top;
            border: 1px solid #dee2e6
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem
        }

        .table-bordered {
            border: 1px solid #dee2e6
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6
        }

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-borderless tbody+tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, .075)
        }

        .table-primary,
        .table-primary>td,
        .table-primary>th {
            background-color: #b8daff
        }

        .table-primary tbody+tbody,
        .table-primary td,
        .table-primary th,
        .table-primary thead th {
            border-color: #7abaff
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .row {
                padding: 20px;
            }

            table {
                width: 100% !important;
            }

            td {
                font-size: 14px !important;
            }

            a {
                font-size: 14px !important;
            }

            .align-self-center {
                width: 100%;
            }
        }
    </style>
</head>

<body class="container" style="background-color:white">
    <div class="row">
        <div class="col-12 align-self-center" style="margin-top:20px;">
            <table role="presentation" border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td width="100%" align="left" valign="top">
                            <div style="background-color:#ffffff;border:1px solid #dedede;margin:0;padding:15px;">
                                <table width="100%" align="center" role="presentation" border="0" cellpadding="0"
                                    cellspacing="0">
                                    <tr>
                                        <td align="center" valign="center" style="padding-bottom:5px;">
                                            <a target="_blank"
                                                style="font-size: 24px;color: #000;float: left;text-transform: uppercase;text-decoration:none;"
                                                href="">
                                                <img src="{{ $appUrl }}/assets/img/favicon/logo_full.png"
                                                    alt="logo" width="80" border="0"
                                                    style="max-width:100%;display:inline-block">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:15px 0px 0px 15px;" align="left">
                                            <p style="font-size:14pt;font-family:'Calibri',sans-serif;color:#333333;">
                                                Hello,</p>
                                            <p style="font-size:12pt;color:#333333;">The status of the following task
                                                has been updated:</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"
                                            style="background-color:#891AB4 ; color:#ffffff; font-size:18px; font-weight:bold; text-align:center; padding:0.5rem; border-radius:5px;">
                                            Task Status Changed
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0;">
                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                style="border-collapse:collapse;">
                                                <tr style="border-bottom: 1px solid #eeeeee;">
                                                    <td style="padding:10px 0;"><b>Task ID</b></td>
                                                    <td style="padding:10px 0; color:#891AB4 ;">{{ $task->task_id }}
                                                    </td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #eeeeee;">
                                                    <td style="padding:10px 0;"><b>Task Title</b></td>
                                                    <td style="padding:10px 0; color:#891AB4 ;">{{ $task->title }}</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #eeeeee;">
                                                    <td style="padding:10px 0;"><b>Old Status</b></td>
                                                    <td style="padding:10px 0; color:#891AB4 ;">{{ $oldStatus }}</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #eeeeee;">
                                                    <td style="padding:10px 0;"><b>New Status</b></td>
                                                    <td style="padding:10px 0; color:#891AB4 ;">{{ $newStatus }}</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #eeeeee;">
                                                    <td style="padding:10px 0;"><b>Changed On</b></td>
                                                    <td style="padding:10px 0; color:#891AB4 ;">{{ $changedAt }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">
                                            <a href="{{ url('tasks') }}" target="_blank"
                                                style="background-color:#891AB4 ; color:#ffffff; padding:10px 20px; border-radius:6px; text-decoration:none;">
                                                View Task
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"
                                            style="display: block; margin-top: 40px; color:#555555; background-color: #f6dfff; padding: 1rem;">
                                            <img src="{{ $appUrl }}/assets/img/favicon/logo_full.png"
                                                alt="Logo" style="margin-bottom: 20px;">
                                            <p>
                                                <b style="color: #891AB4;">Address : </b>23, Maruti Plaza, Krishnanagar
                                                Road, Ahmedabad - 380038<br>
                                                <b style="color: #891AB4;">Phone No : </b>90338 89372<br>
                                                <b style="color: #891AB4;">Send Mail : </b>
                                                <a href="mailto:info@skysphereinfosoft.com"
                                                    style="color: #891AB4; font-weight: 600; text-decoration: none;">
                                                    info@skysphereinfosoft.com
                                                </a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
