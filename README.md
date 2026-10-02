# Maps for Geeklog

Maps is a Geeklog plugin for creating Google Maps, markers, overlays, profile maps, Calendar event maps and reusable map autotags.

## Maps 1.7.0 compatibility

- Geeklog 2.1.1 through 2.2.2
- PHP 5.6 through 8.3
- MySQL/MariaDB versions supported by the corresponding Geeklog release
- Google Maps Platform as available in 2026

PHP syntax is checked on PHP 5.6, 7.4, 8.1 and 8.3.

## What's new in 1.7.0

Maps 1.7.0 consolidates the modernized 1.6 codebase and turns Maps into a first-class interoperability provider for the Geeklog ecosystem.

Highlights:

- provider-neutral capability declaration through `plugin_getcapabilities_maps()`;
- normalized map and marker resources through Geeklog Item Info;
- `dashboard.summary` service for Eclipse and other administration dashboards;
- `maps.geo.nearby` read service for Agent, Hub and trusted in-process consumers;
- marker list/get/render services;
- marker create/update and validity services with idempotent operation support;
- lifecycle notifications for maps and markers;
- static `plugin.json` metadata for Monitor, Hub and repository tooling;
- reproducible release packaging with PHP compatibility checks and contract tests.

Maps remains the owner of permissions, canonical URLs, lifecycle and marker business rules. Agent, Eclipse and Hub consume the same shared contracts; Maps does not implement consumer-specific APIs.

## Interoperability

Maps exposes these shared capabilities:

- `content.read`
- `content.collection`
- `content.search`
- `content.url.resolve`
- `content.lifecycle`
- `content.syndication`
- `dashboard.summary`
- `maps.map.read`
- `maps.marker.read`
- `maps.marker.list`
- `maps.marker.render`
- `maps.geo.nearby`
- `maps.marker.create`
- `maps.marker.update`
- `maps.marker.validity.set`
- `maps.marker.validity.extend`

### Eclipse

Eclipse 1.2 and later can discover `dashboard.summary` and display Maps counts, pending submissions, expiring markers and the administration link without querying Maps tables directly.

### Agent and Hub

Agent and Hub can consume normalized map/marker resources and specialized read services through the shared Geeklog contracts. Hub remains responsible for cross-content relationships; Agent remains responsible for machine-facing adaptation.

## Issues addressed in 1.7.0

Maps 1.7.0 also addresses the following modernization issues:

- #4 — independent Users Map center and display settings, with compatibility fallbacks;
- #5 — administration discovery/status for XML Sitemap, Documents, IndexNow and native RSS/Atom feeds;
- #14 — dedicated server-only Geocoding key, with no browser-key fallback;
- #15 — Google Maps Platform diagnostics, documented API footprint and browser geolocation for marker route planning.

See [Google Maps Platform usage](docs/google-maps-platform.md) for API/key guidance.

## Google Maps Platform setup

Create a Google Cloud project, enable billing and enable at least:

- Maps JavaScript API
- Geocoding API when address-to-coordinate conversion is used
- Directions API when marker route planning is used

Configure the browser API key, optional server-side Geocoding key, language/region and optional Map ID in Geeklog's Maps configuration.

## Shared image resources and multisite

Map image resources intentionally remain under the shared Geeklog images path:

- `images/maps/icons/`
- `images/maps/overlays/`

This remains compatible with shared-files/multisite installations when the Geeklog images directory is shared.

## Upgrade

Upgrade through Geeklog's normal Plugin Administration screen. Back up the database and `images/maps/` before upgrading production installations.

The maintained transition target is Geeklog 2.1.1 through 2.2.2 and PHP 5.6 through 8.3. Maps 1.7.0 does not require a new database table beyond the service-operation table already introduced by the 1.6 line.

## Autotags

The historical autotags remain available:

- `[maps: ...]`
- `[geo: ...]`
- `[marker: ...]`

## Documentation

- [Roadmap](ROADMAP.md)
- [Maps 1.7.0 release notes](RELEASE-NOTES-1.7.0.md)

Bug reports and feature requests belong in the Geeklog Maps repository issue tracker.
