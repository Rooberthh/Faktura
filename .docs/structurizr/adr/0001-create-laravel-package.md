# Use Laravel for Invoice Package

Date: 2025-09-12

## Status
Accepted

## Context
We are building a reusable invoice package intended to integrate with Laravel applications. Developers should be able to install, configure, and use the package easily.

## Decision
We will develop the package as a **Laravel package** using a ServiceProvider, configuration file, and facades where appropriate.

## Consequences
- Package integrates naturally into Laravel apps using standard mechanisms.
- Developers can publish config, migrations, and assets.
- Limits package to Laravel projects only.
