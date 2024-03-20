<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="swagger/swagger-ui.css" />
    <link rel="icon" type="image/png" href="swagger/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="swagger/favicon-16x16.png" sizes="16x16" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>

<body>
    <div style="display: none" id="url">{{ env('APP_URL') }}/api/swagger</div>
    <div id="swagger-ui"></div>

    <script src="/swagger/swagger-ui-bundle.js" charset="UTF-8"></script>
    <script src="/swagger/swagger-ui-standalone-preset.js" charset="UTF-8"></script>
    <script>
        let url = document.getElementById('url').innerHTML;

        console.log(url);
        window.onload = function() {
            //   Begin Swagger UI call region
            const ui = SwaggerUIBundle({
                url: url,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
            // End Swagger UI call region

            window.ui = ui;
        };
    </script>
</body>

</html>