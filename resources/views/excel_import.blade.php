<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>excel整理</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">


        <div class="content">
            <div class="title m-b-md">
                数据整理
            </div>

            <div class="links">

                <form action="excel/import" method="POST" class="form-inline" id="need_submit">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="button" onclick="document.getElementById('upfile').click();" class="dm3-btn dm3-btn-medium button-large">
                        选择文件
                    </button>
                    <div style="display:inline-block;">
                        <input type="text" class="form-control" name="url" id="fileURL">
                    </div>
                    <input type="file" name="file" id="upfile" onchange="document.getElementById('fileURL').value=this.value;" style="display:none">
                    <button id="need_disable" type="button" class="dm3-btn dm3-btn-medium button-large" onClick="return userImport()">上传</button>
                    <!-- <a href="{{ URL('users/exampleDownload') }}" class="dm3-btn dm3-btn-medium button-large">{{ trans('button.example') }}</a> -->
                </form>

            </div>
            <div>
                {!! errors_for('fail', $errors) !!}
                {!! errors_for('success', $errors) !!}
                <div id="loading">
                </div>

            </div>
        </div>
    </div>
    <script src="/js/jquery-1.9.1.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function userImport() {

            var strHtml = '<p><span style="color:#00b0f0">正在上传。。。。。。</span></p>';
            document.getElementById("loading").innerHTML = strHtml;

            $("#need_disable").attr("disabled", "true");
            $('#need_submit').submit();
            return true;



        }
    </script>
</body>

</html>