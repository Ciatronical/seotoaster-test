PayPal plugin.

To enable pay with paypal button add to page content {$plugin:paypal:button}.
If you want to pay with credit card add {$plugin:paypal:creditcard}. Or add both.

Then click "PAYPAL" on main panel. And enter your paypal e-mail for payments by paypal button, 
or if you want to use payment by credit card, fill next fields:
- Signature;
- API Name;
- API Password.

Payplay button on quote
{$plugin:paypal:quote}

Subscribe quote

First value after quote param - quantity of payments
Second value after quote param - period of payments
Third value after quote param -
D – for days; allowable range from 1 to 90
W – for weeks; allowable range from 1 to 52
M – for months; allowable range from 1 to 24
Y – for years; allowable range from 1 to 5

Example:
{$plugin:paypal:quote:3:1:M:subscribe}
{$plugin:paypal:quote:5:1:M:subscribe}