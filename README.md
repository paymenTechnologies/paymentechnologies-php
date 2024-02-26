# paymentechnologies-php

# clone project

git clone https://github.com/paymenTechnologies/paymentechnologies-php.git

# open project paymentechnologies-php - [2d,3ds,frictionless]

cd paymentechnologies-php && cd [2d,3ds,frictionless]

# run project

php -S localhost:8000


# sample form data,  please change the credentials to your own credentials

orderid:62879\
transaction_type:A\
amount:1.00\
currency:USD\
email:test44@sample.com\
street:1600 Amphitheatre Parkway\
city:Mountain View\
zip:94043\
state:CA\
country:USA\
phone:+12345345345\
transaction_hash:zqT5fqc3V1yIrFeE0rPjkaF2wSmldT6p5AXD8Qho5ONTINGw6nUiji79HEq4iI70n4gczl8fbusXhz5r8rmTRg==\ # optional
customerip:127.0.0.1\
firstname:John\
lastname:Smith\
card_number:4001919257537193\
expiration_month:12\
expiration_year:24\
cvc:465

\
-- additional fields,  3dsv and frictionless

dob:1990-01-01\
success_url: example-success.com\
fail_url: example-fail.com\
notify_url: example-notify.com
