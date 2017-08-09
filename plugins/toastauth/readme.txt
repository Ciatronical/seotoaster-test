This plugin allow external members to login into seotoaster through Facebook and Google.

Widgets:
{$auth:facebook}
{$auth:google}
{$auth:linkedin}

Setup:
Open "Auth settings" menu on "Admin panel" and аill the form fields.

The client_id and client_secret for Facebook can be found at https://developers.facebook.com/apps
On the Facebook site set the Site URL to http://YOUR_WEBSITE/

The client_id and client_secret for Google can be found at https://code.google.com/apis/console
On the Google site set the Redirect URI to "http://YOUR_WEBSITE/plugin/toastauth/run/login/provider/google"

The client_id and client_secret for Linkedin can be found at https://developer.linkedin.com/
On the Linkedin site set the Redirect URI to "http://YOUR_WEBSITE/plugin/toastauth/run/login/provider/linkedin"


Please don't forget to setup a "action emails"