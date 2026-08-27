# Maps 1.5.8 — Release Notes

Maps 1.5.8 is a major stabilization and modernization release for the Geeklog Maps plugin. It preserves the historical Maps data model and URLs where practical while bringing the plugin forward for current Geeklog, PHP and Google Maps Platform environments.

## Compatibility

- Geeklog 2.1.1 through 2.2.2
- PHP 5.6 through 8.3
- MySQL/MariaDB versions supported by the corresponding Geeklog release
- Google Maps Platform as available in 2026

The source is syntax-checked in CI on PHP 5.6, 7.4, 8.1 and 8.3.

## Google Maps modernization

- centralized Google Maps JavaScript API loading;
- current HTTPS geocoding endpoint;
- removed retired `sensor` parameter;
- removed Google Maps AdSense integration;
- removed TimThumb and FCKeditor-specific dependencies;
- replaced retired Google Image Charts marker URLs with SVG data-URI markers;
- replaced the bundled historical MarkerClusterer with `@googlemaps/markerclusterer` 2.6.2;
- retained `google.maps.Marker` for compatibility with existing custom icons and historical Maps behavior;
- added browser-side API-key diagnostics in Maps administration;
- normalized historical comma-decimal coordinates and locale-sensitive numeric output.

## Geeklog compatibility

- compatibility rendering across Geeklog 2.1.1 through 2.2.2;
- support for the Geeklog `userinfo` to `user_attributes` storage change;
- modern document rendering where supported;
- PHP 8 compatibility fixes while retaining PHP 5.6 syntax;
- removal of obsolete PHP constructs and numerous unsafe/uninitialized legacy request paths.

## Content interoperability

Maps are first-class interoperable content in 1.5.8.

Implemented:

- normalized Item Info for individual maps;
- collection retrieval using `id='*'`;
- `since`, `limit`, `modified-desc` and `created-desc` collection options;
- permission-aware and visibility-aware retrieval;
- canonical map URL resolution;
- centralized lifecycle events after successful content mutations;
- propagation of marker, CSV import, overlay and icon changes to affected parent maps.

Markers remain internal/non-first-class objects in 1.5.8 and may be exposed in a future release when a concrete consumer requires them.

## Native Geeklog integrations

Maps 1.5.8 integrates with:

- What's New;
- Content Syndication / feeds;
- Statistics;
- XML Sitemap;
- Related Items;
- Search;
- Autotags.

Content Syndication includes public permission filtering, feed limits, update checks and RSS/Atom extension support where requested by Geeklog.

## Statistics

- improved statistics cards on the public Maps page;
- global map count;
- visible marker count;
- aggregate map and marker views;
- per-map view count;
- per-map visible marker count;
- per-map aggregate marker views;
- native Geeklog statistics callbacks;
- permission-aware queries without N+1 retrieval.

## Security hardening

Administration mutation paths have been reviewed and modernized.

Notable changes:

- POST-only state-changing operations;
- Geeklog CSRF validation for map, marker, overlay, overlay-group, icon and geolocation mutations;
- safer identifier normalization and SQL escaping;
- hardened icon and overlay uploads;
- extension and MIME restrictions for uploaded images;
- basename-safe image deletion;
- private CSV staging in Geeklog `path_data`;
- CSV field whitelist and coordinate validation;
- CSRF-protected CSV confirmation;
- AJAX overlay relationship validation;
- lifecycle notifications only after real successful dependency changes.

## Installation and database portability

- install metadata now matches the eight Maps tables actually created;
- obsolete/nonexistent marker category/field/value table aliases were removed;
- install compatibility now accepts PHP 5.6 through 8.3;
- historical explicit empty-string defaults were removed from `TEXT` columns for better MySQL portability;
- CSV marker import now supplies mandatory validity dates and remark values for strict SQL environments.

## Upgrade

The supported modernization path is:

1. upgrade older Maps installations to Maps 1.4.0 first;
2. back up the Geeklog database and shared `images/maps/` directory;
3. upload/copy the Maps 1.5.8 package;
4. run Geeklog's normal plugin upgrade from Plugin Administration before opening Maps administration.

The 1.5.8 upgrader sequentially applies the 1.5.x configuration and public-folder repair steps and normalizes the canonical public plugin folder to `/maps/`.

## Shared resources and multisite

Uploaded map resources intentionally remain shared under Geeklog's common image directory:

- `images/maps/icons/`
- `images/maps/overlays/`

This preserves the historical behavior and remains suitable for installations sharing the Geeklog image directory across sites.

## Release-candidate validation

Before the final 1.5.8 release, RC testing must cover:

- fresh install on Geeklog 2.1.1;
- fresh install on Geeklog 2.2.2 with PHP 8.1/8.3;
- representative Maps 1.4.0 to 1.5.8 upgrade;
- map/marker/overlay/icon CRUD;
- CSV import/export;
- public maps, profiles, Calendar integration and autotags;
- Content Syndication, Statistics, XML Sitemap and Related Items;
- anonymous/private permission behavior;
- lifecycle notifications;
- invalid CSRF, invalid upload and malformed CSV rejection;
- final installable ZIP inspection.

After RC1, changes should be restricted to regression, compatibility, security and data-integrity fixes.

## Configuration cleanup in 1.5.8

- Renamed marker resource configuration labels to `Custom field N label` / `Libellé du champ personnalisé N`.
- Removed the obsolete `infos_label` / `Infos label (Pro version)` configuration option.
- Kept `item_1` through `item_10` as supported marker custom fields.

- Centralized public rendering of marker custom fields on marker detail pages and map info windows; fields are shown only when both label and value are present.

- Marker edit forms now hide untouched custom-field placeholders; a field appears only after its label is customized, or when the marker already contains a value to preserve legacy data.
