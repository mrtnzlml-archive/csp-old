# Content Security Policy for Nette Framework

[![Build Status](https://travis-ci.org/mrtnzlml/csp.svg?branch=master)](https://travis-ci.org/mrtnzlml/csp)

Please read this:
- https://www.w3.org/TR/CSP/
- http://content-security-policy.com/
- https://developer.mozilla.org/en-US/docs/Web/Security/CSP/CSP_policy_directives

This library introduces simple CSP extension for DIC which help you to secure your application:

```
extensions:
  csp: Mrtnzlml\ContentSecurityPolicyExtension
```

There are a lot of configuration options. These are the default ones:

```
csp:
  enabled: yes
  report-only: no
  default-src: self
  script-src: * unsafe-inline unsafe-eval
  style-src: * unsafe-inline
  img-src: self data:
  connect-src: self
  font-src: *
  object-src: *
  media-src: *
  report-uri: NULL
  child-src: *
  form-action: self
  frame-ancestors: self
```

You can also use arrays in configuration:

```
csp:
  default-src: self
  script-src:
    - *
    - unsafe-inline
    - unsafe-eval
```

If enabled, it will send `Content-Security-Policy` or `Content-Security-Policy-Report-Only` header in `report-only` mode. You can setup whatever values you want in config. `report-uri` should be relative URL:

```
csp:
	report-uri: api/v1/csp_report
```

And remember, you can use `report-only` mode only if there is `report-uri` specified.
