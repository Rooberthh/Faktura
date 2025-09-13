# Read-only integration with external providers

Date: 2025-09-13

## Status
Proposed

## Context
We need to synchronize invoices with the external providers. Our system is the authoritative source of invoice data. Updating or overwriting invoices in the provider’s system creates risks of divergence and conflicts. Instead, we want a simple model: push a draft invoice once, then treat the external provider as the authority on its lifecycle (payment, cancellation, reminders).

## Decision
We will only create draft invoices in the external provider system via REST API.

- After creation, we will not modify or delete invoices externally.
- Our system will listen to status changes from the provider (via webhooks if available, or periodic polling otherwise).
- All updates will be recorded internally as read-only syncs from the provider.
- 
## Consequences
- Keeps our system as the source of truth for invoice creation.
- Reduces risk of overwriting or conflicting with provider data.
- Simplifies integration — only one outbound call type (“create draft”).
- We depend on the provider for the final invoice lifecycle → limited control.
- If a mistake occurs after draft creation, we cannot “fix” it externally — must void/cancel and create a new invoice.
- Requires robust webhook/polling handling to stay in sync.
