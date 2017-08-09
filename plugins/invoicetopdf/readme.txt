Plugin Invoicetopdf.

Plugin Invoicetopdf used for making invoices in pdf format for the shopping plugin.

Template setup for invoice to pdf plugin:

Open "Layout" -> "Add template" under the "Admin Panel".
Then choose template type "invoices" and create template.

Open "STORE" -> "Configuration" under the "Admin Panel".
Then click on "Documents" tab and assign invoice template.

Plugin widgets that used inside invoice template

{$plugin:invoicetopdf:customer:shipping|billing:lastname|firstname|address1|address2|city|state|zip|country|phone|mobile|email} - Shipping or billing information about purchase

  shipping - shipping address
  billing - billing address
  lastname - last name
  firstname - first name
  address1 - address
  address2 - address
  city - city
  state - state
  zip - zip
  country - country
  phone - phone
  mobile - mobile
  email - email

{$plugin:invoicetopdf:invoiceDate} - Display date of creation invoice
{$plugin:invoicetopdf:invoiceNumber} - Cart id
{$plugin:invoicetopdf:created} - Display date when purchase was done


