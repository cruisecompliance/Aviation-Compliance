<html>
<head>
    <title>Simple Authentication Sample</title>
</head>
<body>

<!-- Login button -->
<button onclick="azure()">Login to Azure AD</button>

<p>
<h2>Profile from Microsoft Graph</h2>
<div id="divError" style="display: none"></div>
<div id="divProfile" style="display: none">
    <div><b>Name:</b> <span id="profileDisplayName"/></div>
    <div><b>Job title:</b> <span id="profileJobTitle"/></div>
    <div><b>E-mail:</b> <span id="profileMail"/></div>
    <div><b>UPN:</b> <span id="profileUpn"/></div>
    <div><b>Object id:</b> <span id="profileObjectId"/></div>
</div>
</p>

<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha384-VC7EHu0lDzZyFfmjTPJq+DFyIn8TUGAJbEtpXquazFVr00Q/OOx//RjiZ9yU9+9m" crossorigin="anonymous"></script>
<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-mhp2E+BLMiZLe7rDIzj19WjgXJeI32NkPvrvvZBrMi5IvWup/1NUfS5xuYN5S3VT" crossorigin="anonymous"></script>
<script src="https://secure.aadcdn.microsoftonline-p.com/lib/1.0.17/js/adal.min.js" integrity="sha384-BIOS/65fbAsb2XiCCSTlZSTTl0ZgqkOU522dpyk5meOnN2EOQ3uH+QpqEtoAtmBn" crossorigin="anonymous"></script>

<script type="text/javascript">
    microsoftTeams.initialize();

    function azure() {
        login(window.location.origin + "/start"); // URL
    }

    // Login to Azure AD and get access to Microsoft Graph
    function login(url) {
        hideProfileAndError();
        microsoftTeams.authentication.authenticate({
            url: url,
            width: 600,
            height: 535,
            successCallback: function (result) {
                console.log("Login succeeded: " + result);
                let data = localStorage.getItem(result);
                localStorage.removeItem(result);
                let tokenResult = JSON.parse(data);
                getUserProfile(tokenResult.accessToken);
            },
            failureCallback: function (reason) {
                console.log("Login failed: " + reason);
                handleAuthError(reason);
            }
        });
    }

    // Get the user's profile information from Microsoft Graph
    function getUserProfile(accessToken) {
        $.ajax({
            url: "https://graph.microsoft.com/v1.0/me/",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + accessToken);
            },
            success: function (profile) {
                $("#profileDisplayName").text(profile.displayName);
                $("#profileJobTitle").text(profile.jobTitle);
                $("#profileMail").text(profile.mail);
                $("#profileUpn").text(profile.userPrincipalName);
                $("#profileObjectId").text(profile.id);
                $("#divProfile").show();
                $("#divError").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("textStatus: " + textStatus + ", errorThrown:" + errorThrown);
                $("#divError").text(errorThrown).show();
                $("#divProfile").hide();
            },
        });
    }
    // Show error information
    function handleAuthError(reason) {
        $("#divError").text(reason).show();
        $("#divProfile").hide();
    }
    // Clear all information in tab
    function hideProfileAndError() {
        $("#divError").text("").hide();
        $("#divProfile").hide();
    }
</script>
</body>
</html>
