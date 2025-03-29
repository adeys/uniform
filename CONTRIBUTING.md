# Contributing to Uniform

Thank you for your interest in contributing to Uniform! This document provides guidelines to help you contribute effectively to this self-hostable form platform.

## Development Process

1. **Fork** the repository on GitHub.
2. **Clone** your fork locally (`git clone https://github.com/adeys/uniform.git`).
3. **Create a branch** for your contribution (`git checkout -b feature/my-feature`).
4. **Make your changes** and test them thoroughly.
5. **Commit** your changes with clear messages and using conventional commit format (`git commit -m "feat: add feature X"`).
6. **Push** to your fork (`git push origin feature/my-feature`).
7. **Open a Pull Request** to the main branch.

## Code Standards

- Follow **PSR-12** coding standards for PHP code.
- Use **Symfony best practices** for controller, service, and entity structure.
- Document new features or modifications thoroughly.
- Add unit tests for new features.
- Ensure all tests pass before submitting a PR.

## Project Structure

Uniform follows the standard Symfony application structure:
- `src/Controller/` - Controllers including the `EndpointController` for form submissions
- `src/Entity/` - Doctrine entities including `FormDefinition`
- `src/Service/` - Business logic and services
- `templates/` - Twig templates including email notifications
- `public/` - Web-accessible files

## Feature Development

If you're working on new features, consider the following areas:
- Form endpoint handling for submission collection
- Notification systems (email, webhooks, Slack)
- Form builder interface
- Spam protection mechanisms
- Database and migration handling

## Reporting Bugs

- Use GitHub Issues to report bugs.
- Clearly describe the problem and how to reproduce it.
- Include information about your environment (PHP version, database, etc.).

## Suggesting Improvements

- For significant new features, open an Issue for discussion first.
- Describe the current behavior and the expected behavior.
- Explain why the feature would be useful to the project.

## Questions?

If you have questions or need help, don't hesitate to open an Issue.

Thank you for contributing to Uniform!
