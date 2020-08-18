<html>
<head>
    <title>Simple Authentication Sample Login</title>
</head>
<body>
<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-mhp2E+BLMiZLe7rDIzj19WjgXJeI32NkPvrvvZBrMi5IvWup/1NUfS5xuYN5S3VT" crossorigin="anonymous"></script>

<script type="text/javascript">
    microsoftTeams.initialize();
    localStorage.removeItem("simple.error");
    let hashParams = getHashParameters();
    if (hashParams["error"]) {
        // Authentication/authorization failed
        localStorage.setItem("simple.error", JSON.stringify(hashParams));
        microsoftTeams.authentication.notifyFailure(hashParams["error"]);
    } else if (hashParams["access_token"]) {
        // Get the stored state parameter and compare with incoming state
        let expectedState = localStorage.getItem("simple.state");
        if (expectedState !== hashParams["state"]) {
            // State does not match, report error
            localStorage.setItem("simple.error", JSON.stringify(hashParams));
            microsoftTeams.authentication.notifyFailure("StateDoesNotMatch");
        } else {
            // Success -- return token information to the parent page.
            // Use localStorage to avoid passing the token via notifySuccess; instead we send the item key.
            let key = "simple.result";
            localStorage.setItem(key, JSON.stringify({
                idToken: hashParams["id_token"],
                accessToken: hashParams["access_token"],
                tokenType: hashParams["token_type"],
                expiresIn: hashParams["expires_in"]
            }));
            microsoftTeams.authentication.notifySuccess(key);
        }
    } else {
        // Unexpected condition: hash does not contain error or access_token parameter
        localStorage.setItem("simple.error", JSON.stringify(hashParams));
        microsoftTeams.authentication.notifyFailure("UnexpectedFailure");
    }
    // Parse hash parameters into key-value pairs
    function getHashParameters() {
        let hashParams = {};
        location.hash.substr(1).split("&").forEach(function(item) {
            let s = item.split("="),
                k = s[0],
                v = s[1] && decodeURIComponent(s[1]);
            hashParams[k] = v;
        });
        return hashParams;
    }
</script>
</body>
</html>
