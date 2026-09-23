# Maps 1.7.0 Release Notes

Maps 1.7.0 focuses on interoperability, reusable services and release readiness while preserving the compatibility work completed in the 1.6 line.

## Highlights

- Added a provider-neutral capability declaration through `plugin_getcapabilities_maps()`.
- Added `dashboard.summary` for generic administration dashboards such as Eclipse.
- Added `maps.geo.nearby` for bounded nearby-marker discovery.
- Added static `plugin.json` metadata for repository and disabled-plugin discovery.
- Kept normalized Item Info support for maps and `marker:<mkid>` resources.
- Kept canonical URL resolution and lifecycle notifications for maps and markers.
- Kept trusted inter-plugin marker services for list/get/render/save and validity management.
- Kept idempotent service-operation handling for mutation workflows.
- Updated packaging and documentation for a 1.7.0 release artifact.

## Shared capability contract

Maps now advertises these capabilities:

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

This catalogue is intentionally consumer-neutral. Agent, Eclipse, Hub and future integrations consume the same provider contract.

## Eclipse dashboard integration

The new `dashboard_summary` service returns a bounded, read-only administration summary with:

- total maps;
- total markers;
- pending marker submissions;
- markers expiring within 30 days;
- alerts;
- a Maps administration link.

Maps calculates these values itself. Eclipse does not need direct SQL access to Maps tables.

## Agent and Hub integration

Agent and Hub can consume:

- normalized map resources;
- normalized marker resources;
- collections and search;
- canonical URLs;
- lifecycle events;
- nearby-marker lookup;
- marker services when appropriate.

Hub remains responsible for relationships/context. Agent remains responsible for machine-facing adaptation and protocol exposure.

## Static plugin metadata

The package now includes `plugin.json` with schema 1 metadata:

- id: `maps`;
- name: `Maps`;
- administrative icon;
- minimum Geeklog version 2.1.1;
- minimum PHP version 5.6.0.

## Issues resolved

### #4 — Configurable Users Map

The Users Map now has independent optional values for latitude, longitude, zoom, type, width and height. Blank values intentionally fall back to the historical first-active-map/global-map behavior so upgrades do not unexpectedly move or resize existing installations.

### #5 — Integration discovery

Maps administration now shows integration status and discovery links for XML Sitemap, Documents, IndexNow and Geeklog's native RSS/Atom syndication. Maps continues to prefer shared Geeklog APIs and services over direct plugin database coupling.

### #14 — Dedicated server Geocoding key

Server-side geocoding now requires `google_server_api_key`. Maps no longer falls back to the browser `google_api_key`. Administration warns when coordinate autofill is enabled without a dedicated server key, and `url_geocode` is now consumed consistently by the geocoding URL builder.

### #15 — Google Maps API audit and route geolocation

Administration now reports the configuration state of Maps JavaScript, Geocoding and Directions usage, browser/server keys and optional Map ID without exposing secret values.

Marker route planning now offers **Use my location**, implemented with the browser's standard `navigator.geolocation` API. Manual departure remains available when permission is refused or geolocation is unavailable.

The maintained Google API footprint and future Routes API / optional Places evaluation are documented in `docs/google-maps-platform.md`.

## Compatibility

Maps 1.7.0 targets:

- Geeklog 2.1.1 through 2.2.2;
- PHP 5.6 through 8.3;
- supported MySQL/MariaDB versions for the corresponding Geeklog release.

## Upgrade notes

Use Geeklog's normal plugin upgrade path from Maps 1.6.0.

No new database table is introduced specifically for 1.7.0. The existing `maps_service_operations` table remains part of the service/idempotency layer.

Back up the database and shared `images/maps/` resources before upgrading a production site.

## Release validation

Before publishing the tag/release, validate:

- PHP syntax on 5.6, 7.4, 8.1 and 8.3;
- fresh install on Geeklog 2.1.1 and 2.2.2;
- upgrade from Maps 1.6.0;
- map/marker CRUD;
- marker list/get/render services;
- `dashboard_summary` in Eclipse;
- `geo_nearby` permissions and distance ordering;
- Item Info for maps and `marker:<mkid>`;
- lifecycle delivery to at least one consumer;
- generated installable ZIP through normal Plugin Administration.
