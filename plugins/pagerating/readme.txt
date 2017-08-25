This is "Pagerating plugin".

This plugin is necessary for inserting rating to your pages. People can vote once for each your page.

How to use it:
First after installation click on "container" (C) where do you want to place your rating for page.
If you want insert rating widget only put {$plugin:pagerating} in container.
If you want to let users to write comments put {$plugin:pagerating:review:moderated:nocaptcha} instead.
    - moderated - comments will be moderated by admin;
    - nocaptcha - remove recaptcha from review form;
If you want insert rating widget to product only put {$plugin:pagerating:stars:{$product:id}} in container.

