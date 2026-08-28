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

## Public SEO

- Canonical marker URL is now `/maps/index.php?mode=marker&mkid=...`.
- Legacy `markers.php?mode=show...` marker detail URLs permanently redirect to the canonical route.
- Public map and marker pages emit canonical, meta description, Open Graph and Twitter metadata.
- Marker pages emit Schema.org `Place`, `PostalAddress` and `GeoCoordinates` JSON-LD when data is available.
- Invalid, hidden or inaccessible maps/markers return a real 404.
- Map and marker pages now expose a clear H1 and marker pages link back to their parent map.
- Private marker management pages are `noindex,follow`.
- XML Sitemap collection includes canonical public marker pages in addition to maps.
- Internal marker search/list links use the canonical marker URL.

## Administration and menu integration

- Saving a map in administration now redirects to its public map page so the administrator can immediately validate the result.
- Maps already exposes `/maps/index.php` through Geeklog's native `plugin_getmenuitems_maps()` callback when `hide_maps_menu` is disabled. A `#` fallback from a custom Menu element therefore points to a Menu-side fallback/configuration issue rather than a missing Maps callback.