<html>
<head>
    <title>Silent Authentication Sample</title>
</head>
<body>

<!-- Login button -->
<button id="btnLogin" class="btn btn-primary" onclick="login()" style="display: none">Login</button>

<style>
    .btn{
        display: inline-block;
        font-weight: 400;
        color: #6c757d;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.45rem 0.9rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.15rem;

    }
    .btn-primary{
        color: #fff;
        background-color: #6658dd;
        border-color: #6658dd;
    }
</style>

{{--<!-- Result -->--}}
{{--<p>--}}
{{--<div id="divError" style="display: none"></div>--}}
{{--<div id="divProfile" style="display: none">--}}
{{--    <div><b>Name:</b> <span id="profileDisplayName"/></div>--}}
{{--    <div><b>UPN:</b> <span id="profileUpn"/></div>--}}
{{--    <div><b>Object id:</b> <span id="profileObjectId"/></div>--}}
{{--</div>--}}

<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha384-VC7EHu0lDzZyFfmjTPJq+DFyIn8TUGAJbEtpXquazFVr00Q/OOx//RjiZ9yU9+9m" crossorigin="anonymous"></script>
<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-mhp2E+BLMiZLe7rDIzj19WjgXJeI32NkPvrvvZBrMi5IvWup/1NUfS5xuYN5S3VT" crossorigin="anonymous"></script>
{{--<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-SNENyRfvDvybst1u0LawETYF6L5yMx5Ya1dIqWoG4UDTZ/5UAMB15h37ktdBbyFh" crossorigin="anonymous"></script>--}}

<!-- Check before upgrading your ADAL version, as this sample depends on an internal function to authenticate silently -->
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

    let upn = undefined;
    microsoftTeams.getContext(function (context) {
        upn = context.upn;
        loadData(upn);
    });
    // Loads data for the given user
    function loadData(upn) {
        // Setup extra query parameters for ADAL
        // - openid and profile scope adds profile information to the id_token
        // - login_hint provides the expected user name
        if (upn) {
            config.extraQueryParameters = "scope=openid+profile&login_hint=" + encodeURIComponent(upn);
        } else {
            config.extraQueryParameters = "scope=openid+profile";
        }

        let authContext = new AuthenticationContext(config);

        // See if there's a cached user and it matches the expected user
        let user = authContext.getCachedUser();
        if (user) {
            if (user.userName !== upn) {
                // User doesn't match, clear the cache
                authContext.clearCache();
            }
        }

        // Get the id token (which is the access token for resource = clientId)
        let token = authContext.getCachedToken(config.clientId);
        if (token) {
            // showProfileInformation(token);
            loginUser(token);
        } else {
            // No token, or token is expired
            authContext._renewIdToken(function (err, idToken) {
                if (err) {
                    console.log("Renewal failed: " + err);
                    // Failed to get the token silently; show the login button
                    $("#btnLogin").css({ display: "" });
                    // You could attempt to launch the login popup here, but in browsers this could be blocked by
                    // a popup blocker, in which case the login attempt will fail with the reason FailedToOpenWindow.
                } else {
                    // showProfileInformation(idToken);
                    loginUser(idToken);
                }
            });
        }
    }

    // Login to Azure AD
    function login() {
        $("#divError").text("").css({ display: "none" });
        $("#divProfile").css({ display: "none" });
        microsoftTeams.authentication.authenticate({
            url: window.location.origin + "/login/teams-silent-start",
            width: 600,
            height: 535,
            successCallback: function (result) {
                // AuthenticationContext is a singleton
                let authContext = new AuthenticationContext();
                let idToken = authContext.getCachedToken(config.clientId);
                if (idToken) {
                    // showProfileInformation(idToken);
                    loginUser(idToken);

                } else {
                    console.error("Error getting cached id token. This should never happen.");
                    // At this point we have to get the user involved, so show the login button
                    $("#btnLogin").css({ display: "" });
                };
            },
            failureCallback: function (reason) {
                console.log("Login failed: " + reason);
                if (reason === "CancelledByUser" || reason === "FailedToOpenWindow") {
                    console.log("Login was blocked by popup blocker or canceled by user.");
                }
                // At this point we have to get the user involved, so show the login button
                $("#btnLogin").css({ display: "" });
                $("#divError").text(reason).css({ display: "" });
                $("#divProfile").css({ display: "none" });
            }
        });
    }

    // // Get the user's profile information from the id token
    // function showProfileInformation(idToken) {
    //     var token = parseJwt(idToken);
    //     console.log(token.email);
    //     $("#profileDisplayName").text(token.name);
    //     $("#profileUpn").text(token.upn);
    //     $("#profileObjectId").text(token.oid);
    //     $("#divProfile").css({ display: "" });
    //     $("#divError").css({ display: "none" });
    // }

    function loginUser(idToken) {
        // decode profile data
        var profile = parseJwt(idToken);

        // set user data
        var name = profile.name;
        var email = profile.email ?? profile.upn;

        // auth user
        $.get('/login/teams/profile/' + email + '/name/' + name, function (data) {
            // console.log(data);
            if (data.success) {
                if (data.role.name == data.sme) {
                    window.location.href = window.location.origin + "/admin/home";
                } else {
                    window.location.href = window.location.origin + "/user/flows/table?rule_reference=&rule_section=&assignee=" + data.user.id + "&status=&finding=";
                }
            }
        });
    }

    // Decode Token
    function parseJwt (token) {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));

        return JSON.parse(jsonPayload);
    };
</script>
</body>
</html>


{{-- //////////////// --}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>Simple Authentication Sample</title>--}}
{{--</head>--}}
{{--<body>--}}

{{--<!-- Login button -->--}}
{{--<button onclick="azure()" class="btn btn-primary">Login</button>--}}

{{--{{ request()->header('User-Agent') }}--}}
{{--<style>--}}
{{--    .btn{--}}
{{--        display: inline-block;--}}
{{--        font-weight: 400;--}}
{{--        color: #6c757d;--}}
{{--        text-align: center;--}}
{{--        vertical-align: middle;--}}
{{--        -webkit-user-select: none;--}}
{{--        -ms-user-select: none;--}}
{{--        user-select: none;--}}
{{--        background-color: transparent;--}}
{{--        border: 1px solid transparent;--}}
{{--        padding: 0.45rem 0.9rem;--}}
{{--        font-size: 0.875rem;--}}
{{--        line-height: 1.5;--}}
{{--        border-radius: 0.15rem;--}}

{{--    }--}}
{{--    .btn-primary{--}}
{{--        color: #fff;--}}
{{--        background-color: #6658dd;--}}
{{--        border-color: #6658dd;--}}
{{--    }--}}
{{--</style>--}}
{{--<p>--}}
{{--<h2>Profile from Microsoft Graph</h2>--}}
{{--<div id="divError" style="display: none"></div>--}}
{{--<div id="divProfile" style="display: none">--}}
{{--    <div><b>Name:</b> <span id="profileDisplayName"/></div>--}}
{{--    <div><b>Job title:</b> <span id="profileJobTitle"/></div>--}}
{{--    <div><b>E-mail:</b> <span id="profileMail"/></div>--}}
{{--    <div><b>UPN:</b> <span id="profileUpn"/></div>--}}
{{--    <div><b>Object id:</b> <span id="profileObjectId"/></div>--}}
{{--</div>--}}
{{--</p>--}}

{{--<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha384-VC7EHu0lDzZyFfmjTPJq+DFyIn8TUGAJbEtpXquazFVr00Q/OOx//RjiZ9yU9+9m" crossorigin="anonymous"></script>--}}
{{--<script src="https://statics.teams.cdn.office.net/sdk/v1.6.0/js/MicrosoftTeams.min.js" integrity="sha384-mhp2E+BLMiZLe7rDIzj19WjgXJeI32NkPvrvvZBrMi5IvWup/1NUfS5xuYN5S3VT" crossorigin="anonymous"></script>--}}
{{--<script src="https://secure.aadcdn.microsoftonline-p.com/lib/1.0.17/js/adal.min.js" integrity="sha384-BIOS/65fbAsb2XiCCSTlZSTTl0ZgqkOU522dpyk5meOnN2EOQ3uH+QpqEtoAtmBn" crossorigin="anonymous"></script>--}}

{{--<script type="text/javascript">--}}
{{--    microsoftTeams.initialize();--}}

{{--    $( document ).ready(login(window.location.origin + "/login/teams-start"));--}}

{{--    function azure() {--}}
{{--        login(window.location.origin + "/login/teams-start"); // URL--}}
{{--    }--}}

{{--    // Login to Azure AD and get access to Microsoft Graph--}}
{{--    function login(url) {--}}
{{--        hideProfileAndError();--}}
{{--        microsoftTeams.authentication.authenticate({--}}
{{--            url: url,--}}
{{--            width: 600,--}}
{{--            height: 535,--}}
{{--            successCallback: function (result) {--}}
{{--                console.log("Login succeeded: " + result);--}}
{{--                let data = localStorage.getItem(result);--}}
{{--                localStorage.removeItem(result);--}}
{{--                let tokenResult = JSON.parse(data);--}}
{{--                getUserProfile(tokenResult.accessToken);--}}
{{--            },--}}
{{--            failureCallback: function (reason) {--}}
{{--                console.log("Login failed: " + reason);--}}
{{--                handleAuthError(reason);--}}
{{--            }--}}
{{--        });--}}
{{--    }--}}

{{--    // Get the user's profile information from Microsoft Graph--}}
{{--    function getUserProfile(accessToken) {--}}
{{--        $.ajax({--}}
{{--            url: "https://graph.microsoft.com/v1.0/me/",--}}
{{--            beforeSend: function(request) {--}}
{{--                request.setRequestHeader("Authorization", "Bearer " + accessToken);--}}
{{--            },--}}
{{--            success: function (profile) {--}}

{{--                // $("#profileDisplayName").text(profile.displayName);--}}
{{--                // $("#profileJobTitle").text(profile.jobTitle);--}}
{{--                // $("#profileMail").text(profile.mail);--}}
{{--                // $("#profileUpn").text(profile.userPrincipalName);--}}
{{--                // $("#profileObjectId").text(profile.id);--}}
{{--                // $("#divProfile").show();--}}
{{--                // $("#divError").hide();--}}

{{--//				 $.ajax({--}}
{{--//                    url: '/login/teams/profile/',--}}
{{--//                    type: 'POST',--}}
{{--//                    data: {email: profile.mail, name: profile.displayName},--}}
{{--//                    success: function (data) {--}}
{{--//                      	console.log(data);--}}
{{--//                    },--}}
{{--//                    error: function (data) {--}}
{{--//                    	console.log('Error:', data);--}}
{{--//                    }--}}
{{--//				});--}}


{{--                $.get('/login/teams/profile/' + profile.mail + '/name/' + profile.displayName, function (data) {--}}
{{--                    console.log(data);--}}
{{--                    if (data.success) {--}}
{{--                        if (data.role.name == data.sme) {--}}
{{--                            window.location.href = window.location.origin + "/admin/home";--}}
{{--                        } else {--}}
{{--                            window.location.href = window.location.origin + "/user/home";--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}

{{--            },--}}
{{--            error: function (xhr, textStatus, errorThrown) {--}}
{{--                console.log("textStatus: " + textStatus + ", errorThrown:" + errorThrown);--}}
{{--                $("#divError").text(errorThrown).show();--}}
{{--                $("#divProfile").hide();--}}
{{--            },--}}
{{--        });--}}
{{--    }--}}
{{--    // Show error information--}}
{{--    function handleAuthError(reason) {--}}
{{--        $("#divError").text(reason).show();--}}
{{--        $("#divProfile").hide();--}}
{{--    }--}}
{{--    // Clear all information in tab--}}
{{--    function hideProfileAndError() {--}}
{{--        $("#divError").text("").hide();--}}
{{--        $("#divProfile").hide();--}}
{{--    }--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}
