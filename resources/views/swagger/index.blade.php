<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="swagger-ui.css" />
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

    <script src="swagger-ui-bundle.js" charset="UTF-8"></script>
    <script src="swagger-ui-standalone-preset.js" charset="UTF-8"></script>
    <script src="swagger-ui.js" charset="UTF-8"></script>
    <script>
        let url = document.getElementById('url').innerHTML;
        window.onload = function() {
            // Build a system
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: url,
                requestInterceptor: function(request) {
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    return request;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                deepLinking: true,
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

            })

            window.ui = ui

        }
    </script>
</body>

</html>
