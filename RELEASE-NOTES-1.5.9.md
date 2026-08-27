# Maps 1.5.9 release notes

## Marker Service API

Maps 1.5.9 introduces a trusted inter-plugin marker service API for Documents, Store and other Geeklog plugins.

Services available through `PLG_invokeService()`:

- `marker_list` — list accessible markers for selectors;
- `marker_get` — retrieve normalized marker data;
- `marker_render` — render an embeddable Google map focused on one marker;
- `marker_set_validity` — set an explicit marker validity period;
- `marker_extend_validity` — extend a marker from the later of now or its current expiry.

Validity mutations support `operation_id` idempotency so payment callbacks cannot apply the same purchased duration twice. `source` and `source_id` are recorded for traceability.

These services are deliberately internal. Requests arriving through Geeklog Webservices (`gl_svc`) are rejected. Maps remains the owner of marker storage, permissions, rendering and validity rules; consumer plugins must not write Maps tables directly.
