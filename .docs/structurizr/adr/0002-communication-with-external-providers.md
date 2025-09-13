# Communication with external providers

Date: 2025-09-13

## Status
Accepted

## Context
We need to integrate with external invoice providers (e.g., Fortnox, Stripe, etc.). There are two main options:

REST API polling/calls → Our system calls the provider’s REST API to create, fetch, or update invoices.
Webhooks/event-driven → The provider pushes updates to us (or we push events to them).

We must ensure reliability, handle authentication securely, and support multiple providers with potentially different integration patterns.

## Decision
We will primarily use REST API communication with providers, since all major providers expose REST APIs. Where providers also support webhooks for status updates, we will consume them to reduce polling and improve timeliness.

## Consequences
- REST is widely supported, predictable, and easier to standardize across multiple providers.
- REST-only would require periodic polling → inefficient.
- Webhooks introduce complexity (security validation, retries, public endpoint exposure).
- Webhooks improve responsiveness for invoice status changes (paid, canceled, overdue).
- Providers differ in webhook capabilities, meaning we’ll need fallback to polling in some cases.
