# Security Policy

## Reporting a Vulnerability

**Do not open a public GitHub issue for security vulnerabilities.**

Please report them privately using [GitHub's private security advisory feature](https://github.com/anrosol/team-trends/security/advisories/new).

Include as much of the following as possible:

- A description of the vulnerability and its potential impact
- The affected component or file(s)
- Steps to reproduce or a proof of concept
- Any suggested remediation, if you have one

You can expect an acknowledgment within **72 hours** and a status update within **7 days**.

---

## Scope

The following are in scope:

- Authentication and session handling
- Passphrase hashing and anonymity guarantees
- Response encryption and data-at-rest security
- Authorization and access control (admin vs. respondent boundaries)
- SQL injection, XSS, CSRF, and other OWASP Top 10 issues
- Anything that could allow an administrator to de-anonymize respondents

The following are out of scope:

- Vulnerabilities in third-party dependencies (report those upstream)
- Issues requiring physical access to the server
- Social engineering

---

## Supported Versions

Only the latest tagged release is actively maintained. Please verify you are running the latest version before reporting.

---

## Security Design

Team Trends is designed with a minimal attack surface for respondent data:

- Respondents have no accounts, emails, or persistent identifiers
- Passphrases are hashed using a strong one-way algorithm before storage
- Sensitive response data is encrypted at rest using the application key
- No third-party tracking scripts are loaded for respondents

If you believe any of these guarantees are violated by the code, that is a high-priority report.
