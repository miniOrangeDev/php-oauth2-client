# PHP OAuth 2 Connector

This package makes it simple to integrate your application with [OAuth 2.0](http://oauth.net/2/) service providers.

---

PHP OAuth 2 Connector allows any PHP app to do Single Sign-On with any OAuth 2.0 and OpenID Connect 1.0 compliant providers or servers like Azure AD, AWS Cognito, Invision Community, slack, facebook, google, Instagram, Discord or any custom OAuth 2.0 and OpenID Connect (OIDC) servers.

In PHP OAuth 2 Connector you just have to configure app with your desired OAuth 2.0 or OpenID Connect server. When you done the configuration by following the instructions you can set up your Single Sign-On (SSO) environment.

Most of the websites or webapps are now streaming on PHP and many owners wants to login their user from their social media accounts and other already existing accounts rather then create a new one. PHP OAuth 2 Connector has a solution for that problem.


* Any PHP App can connect with PHP OAuth 2 Connector and perform their SSO.
* Configuration with OAuth / OpenID Provider is very simple and elegant way.
* The documentation is suitable for both novice PHP users and experienced programmers.


## MAIN FEATURES 

*	PHP OAuth 2 Connector supports single sign-on / SSO with any 3rd party OAuth /OpenID Connect server or custom OAuth /OpenID Connect server.
*   OAuth/OpenID provider support : Single OAuth/OpenID provider's application can be configured.
*	Attribute Mapping : PHP OAuth 2 Connector supports basic Attribute Mapping feature to map user profile attributes like email.
*	Grant Type : Authorization code Grant
*	Login link/shortcode : Use link to easily integrate the SSO login with your Application site. 
*	Redirect URL after Login : PHP OAuth 2 Connector Automatically Redirects user after successful login. 


## Requirements

The following versions of PHP are supported.

* PHP 5.4 and above

## List of grant types we support 
*   Authorization code grant
*   Implicit grant
*   Resource owner credentials grant (Password Grant)
*   Client credentials grant
*   Refresh token grant



## Installation

1. Download the PHP OAuth 2 Connector 
2. Unzip the file and extract it
3. Copy this folder to your local server root directory
4. Now open your web browser and enter the url.  
   ``` html
   Example - http://localhost/<your-folder-name>/
   ```

## Set up your First App
1. Login with miniOrange Credentials or Register with miniOrange.
2. Go to Configure OAuth.
3. Select the Application from the given list. (Custom OAuth/OpenID for the OAuth provider which are not available in given list).
4. Enter the details in the form and submit.
5. Go to Attribute Mapping.
6. Click on the test configuration and it will give you user's atttribute data.
7. Attribute name from the test configuration screen put into the Attribute Mapping form and submit.
8. Now from your application use given link to do SSO.

   link -
   ``` php 
   https://<your-domain>/sso?app=<appname>&redirect_to=<after-login-redirect-url>
   ```

   here :
   ``` html
   - <your-domain>: your domain at where you hosted your application. 
   - <appname>: name of your application which you have configured.
   - <after-login-redirect-url>: at where after login you want to redirect.
   ```

   Example -
   ``` php
    <a href="https://example.com/sso?app=myfirstapp&redirect_to=https://redirect-domain.com">Login SSO</a>
    ```
      
9. Use the following code in your application to access the user attributes.

``` php
    if (!isset($_SESSION)){                          
        session_start();
    }
    $email = $_SESSION['sso_email'];
    // These variables contain the mapped attribute values.
    // After retrieving these values using the above code, you can use the $email variable in your code.

    $_SESSION['sso_email'] //contains the Email.
```



## List of popular OAuth Providers we support 
*	Azure AD
*	AWS Cognito
*   WHMCS
*   Ping Federate
*	Slack
*	Discord
*	HR Answerlink / Support center
*	WSO2
*	Wechat
*	Weibo
*   LinkedIn
*	Gitlab
*	Shibboleth
*	Blizzard (Formerly Battle.net)
*	servicem8
*	Meetup
*	Eve Online
*	Gluu Server

## List of popular OpenID Connect (OIDC) Providers we support 
*	Amazon
*	Salesforce
*	PayPal
*	Google
*	AWS Cognito
*	Okta
*	OneLogin
*	Yahoo
*	ADFS
*	Gigya



## Other OAuth Providers we support 
*	Other oauth 2.0 providers oauth single sign-on plugin support includes Autodesk, Zendesk, Foursquare, Harvest, Mailchimp, Bitrix24, Spotify, Vkontakte, Huddle, Reddit, Strava, Ustream, Yammer, RunKeeper, Instagram, SoundCloud, Pocket, PayPal, Pinterest, Vimeo, Nest, Heroku, DropBox, Buffer, Box, Hubic, Deezer, DeviantArt, Delicious, Dailymotion, Bitly, Mondo, Netatmo, Amazon, FitBit, Clever, Sqaure Connect, Windows, Dash 10, Github, Invision Community, Blizzar, authlete, Keycloak etc.



## For any other query/problem/request 
Please email us at info@xecurify.com or <a href="http://miniorange.com/contact" target="_blank">Contact us</a>.


## Changelog 

### 1.0 
* First version with supported applications as EVE Online and Google.
