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
    // ADAL.js configuration
    let config = {
        clientId: "1a09dd1e-2d5e-4b08-a6c1-75c44270e4f5",
        redirectUri: window.location.origin + "/login/teams-silent-end",       // This should be in the list of redirect uris for the AAD app
        cacheLocation: "localStorage",
        navigateToLoginRequestUrl: false,
    };
    let authContext = new AuthenticationContext(config);
    if (authContext.isCallback(window.location.hash)) {
        authContext.handleWindowCallback(window.location.hash);
        // Only call notifySuccess or notifyFailure if this page is in the authentication popup
        if (window.opener) {
            if (authContext.getCachedUser()) {
                microsoftTeams.authentication.notifySuccess();
            } else {
                microsoftTeams.authentication.notifyFailure(authContext.getLoginError());
            }
        }
    }
</script>
</body>
</html>