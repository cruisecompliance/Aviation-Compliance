<html>
<head>
    <title>Silent Authentication Sample Login</title>
</head>
<body>
{{--<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-SNENyRfvDvybst1u0LawETYF6L5yMx5Ya1dIqWoG4UDTZ/5UAMB15h37ktdBbyFh" crossorigin="anonymous"></script>--}}

<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-mhp2E+BLMiZLe7rDIzj19WjgXJeI32NkPvrvvZBrMi5IvWup/1NUfS5xuYN5S3VT" crossorigin="anonymous"></script>

<script src="https://secure.aadcdn.microsoftonline-p.com/lib/1.0.15/js/adal.min.js" integrity="sha384-lIk8T3uMxKqXQVVfFbiw0K/Nq+kt1P3NtGt/pNexiDby2rKU6xnDY8p16gIwKqgI" crossorigin="anonymous"></script>

<script type="text/javascript">
    microsoftTeams.initialize();
    // Get the tab context, and use the information to navigate to Azure AD login page
    microsoftTeams.getContext(function (context) {
        // ADAL.js configuration
        let config = {
            clientId: "1a09dd1e-2d5e-4b08-a6c1-75c44270e4f5",
            redirectUri: window.location.origin + "/login/teams-silent-end",       // This should be in the list of redirect uris for the AAD app
            cacheLocation: "localStorage",
            navigateToLoginRequestUrl: false,
        };
        // Setup extra query parameters for ADAL
        // - openid and profile scope adds profile information to the id_token
        // - login_hint provides the expected user name
        if (context.upn) {
            config.extraQueryParameters = "scope=openid+profile&login_hint=" + encodeURIComponent(context.upn);
        } else {
            config.extraQueryParameters = "scope=openid+profile";
        }

        // Use a custom displayCall function to add extra query parameters to the url before navigating to it
        config.displayCall = function (urlNavigate) {
            if (urlNavigate) {
                if (config.extraQueryParameters) {
                    urlNavigate += "&" + config.extraQueryParameters;
                }
                window.location.replace(urlNavigate);
            }
        }
        // Navigate to the AzureAD login page
        let authContext = new AuthenticationContext(config);
        authContext.login();
    });
</script>
</body>
</html>