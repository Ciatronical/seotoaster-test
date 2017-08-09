This is Apps plugin!

It has two modules, email-marketing and SMS & phone service


1)Setup Email-marketing:

To activate it you should have ConstantContact and SEO Samba accounts.

First go to your SEO Samba account and click on "News, Social & PR" tab.
On the top right you will see a link "[ Configure social networks | email marketing ]". Click "email marketing".
New window will be opened, where you can authenticate your ConstantContact account with our application.
After you can go back to Toaster and proceed with selecting clint's emails to send them to needed lists you have in your ConstantContact account.


2)Setup SMS & phone service

To activate this service you should have SEO Samba account.

First go to your SEO Samba account and click on "Voice and SMS" tab.
There you will see "Buy a phone number anywhere in the world" link.
Click that link, new window will be opened, where you can activate SMS & phone service.
After activation you will see the form which does the phone number search.
Choose needed parameters and click "Search" button. After choose the phone number you like, and click "Buy number" button.
!!! Attention, payment for phone number use is recurring (monthly) !!!

If you bought a phone number, you can go back to user dashboard page, refresh it, and click "Voice and SMS" tab, to setup phone number you've bought.
Select your number in dropdown in "Your SEO Samba phone numbers" column.
After phone number selection "Use to send SMS" and "Forward to" options will be enabled or disabled, depending on phone capabilities.

"Forward to" - can be used only to one website/number ("Forward to" - is your real phone/mobile number).
"Use to send SMS" - can be used for different websites.
"Click to call code" - button which will set the script code to clipboard, which can be used on whatever web page (none SEO Toaster websites), to generate a "Click to call" form.

Use SEO Toaster "Click to call" widget:

Click on a container or static container icon, than go to "Widgets", select "Click to call".
Set values for "Label" and "Button value", and put URL for an image, if you want to use one.
Also there is one more parameter called "Country", which will output only the countries you need.

Here is an example:
{$clicktocall:call:Please enter your phone number:Connect me!:country:UA,FR,US,GB}

Use SEO Toaster "SMS triggers"

SMS triggers can be fired up when "New order is placed" and "Shipping tracking code updated".
To set up SMS triggers go to admin panel / Other / Action e-mails & SMS. Select "Store" and add new or edit existing triggers.

3) Setup Crm

    General setup. Click on configure in apps->crm->configuration screen
    Fill up original login and password from your SalesForce account.
    Fill up "secure code". "Secure code" you can find next way:
        Login to your SalesForce account -> My Settings -> Personal -> Reset My Secure Token - after that secure token will be send on your email.


    a) Crm SalesForce - website forms setup
        Click Apps. Select form name -> Check proper data for particular form on your website.
        In website forms you can use next list of field names: email, name, lastname, company, mobile

        Important!!!  email, name, crmContact - mandatory fields
           crmContact -> hidden input field

        Data from the website forms appear under Leads Tab in SalesForce account

    b) Crm SalesForce website Dashboard -> Clients tab
        Here you can add website clients to the SalesFroce system.
        Choose clients after that click "with selected do" and choose add to crm after that choose "salesForce" finish setup and click "Apply".
        Data will appear under "Contacts and Accounts" tabs on SalesForce

    c) Crm SalesForce website Orders

        Click Apps. Click "Setup crm for ecommerce" under SalesForce config. Choose proper data.
        For every completed order system will automatically create or update "Account and Contact" in SalesForce.
        Also it will add new "Opportunity".

Important !!!!! If you want to use SalesForce for Ecommerce website. b) and c). You must add custom field in SalesForce System for "Account".
        Log in into your SalesForce account. Then click Setup -> Customize -> Accounts. After that click "Add a custom field to accounts".
        Here click "new".
        "Step 1. Choose the field type" Choose data type "email".
        "Step 2. Enter the details" After that fill up "Field Label" and "Field Name" as Custom_email.
        Check "Do not allow duplicate values" and "Set this field as the unique record identifier from an external system". After that click next.
        "Step 3. Establish field-level security" Click next.
        "Step 4. Add to page layouts" Click Save.
        Setup finished. Now you can start using your SalesForce account with website for orders and dashboard.
        More information about custom field creation you can find here https://help.salesforce.com/HTViewHelpDoc?id=adding_fields.htm
