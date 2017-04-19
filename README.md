# Gatekeeper

Redirects incoming requests based on client IP matching against a configured whitelist of IP addresses and/or IP ranges.

## Deployment and Hosting

Any PHP-capable server will do. For hosting, Heroku is recommended.

## Configuration

This script expects the following three environment variables to be set:

- `WHITELIST` ... a comma separated list of IP addresses and IP ranges (start- and end-IP, separated by `-`)
- `DESTINATION_PASS` ... a URL to redirect the client to if its IP address matches the specific whitelist
- `DESTINATION_FAIL`... a URL to redirect the client to if its IP address fails to match the specific whitelist

Since this script was built with Heroku in mind, please see this page on [defining config vars](https://devcenter.heroku.com/articles/getting-started-with-php#define-config-vars).

## Copyright and License

Copyright (c) 2017 The Regents of the University of California

This is Open Source Software, published under the MIT license.
