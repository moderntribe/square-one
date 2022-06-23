# Security

TODO: security docs

## Authentication vs Access

## Handling User Input
When dealing with user input, nothing can be trusted. Malicious users can try a number of different techniques to attack sites from areas where user input is permitted (ex. XSS—Cross Site Scripting, CSRF—Cross Site Request Forgery, SQL Injection, etc.).

### Validation
Before any user input is processed by the server for use within the code-base, it should first be validated to confirm that the information provided is what is expected (Emails should be properly formatted to be emails, urls should be urls, required fields should have values, etc.).

Often this is handled client-side with some javascript or HTML5 form validation.

Though validation can help protect against attacks, it is not perfect. Malicious users can still alter the request before it is sent to the server.

### Sanitization
Since user information cannot be trusted, input needs to be processed at every step of the process. Once a form is submitted, the input from the user needs some sanitization to guarantee it conforms to the format required.

Sanitization prevents the server from unintentionally executing code submitted by users. By sanitizing input, we verify that the information is correct and cannot be circumvented by malicious users.

Some examples:
- Number fields sanitized to integers.
- Text fields sanitized to remove raw javascript or html.

WordPress has [quite a few functions available](https://developer.wordpress.org/themes/theme-security/data-sanitization-escaping/#sanitization-securing-input) for sanitizing user data and they should be utilized anywhere user input is going to be processed. By default SquareOne uses these functions and enforces their use through PHP Coding Standards.

### Escaping
While sanitizing is securing the code-base against malicious user input, escaping strips out unwanted data so that when it is presented to users, it is not seen as executable code.

WordPress also provides [several escaping functions](https://developer.wordpress.org/themes/theme-security/data-sanitization-escaping/#escaping-securing-output) which should be used any time user input is being displayed in a browser.

## Common Vulnerabilities (and how to avoid them)

### XSS

### CSRF

### SQL Injection
