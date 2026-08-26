# Maps for Geeklog — Modernization Roadmap

This roadmap is the working plan for completing the Maps modernization effort and preparing a stable release.

Last updated: August 2026

## Target

The current modernization target is:

- Geeklog 2.1.1 through 2.2.2
- PHP 5.6 through 8.1
- Current Google Maps Platform behavior in 2026
- Existing Maps data and URLs preserved whenever practical
- Safe upgrade path from historical Maps installations
- Progressive UI/UX modernization without introducing a plugin-specific CSS framework
- Compliance with the Geeklog Plugin Content Interoperability Contract defined in `hostellerie/memorandum/plugin-content-interoperability-contract.md`

The current development line is **1.5.x** and is currently identified as **1.5.7** in the plugin metadata. A future **2.0** may introduce deeper architectural changes that are intentionally out of scope for the stabilization release.

---

## Release assessment

The modernization branch is already a substantial rewrite of the historical Maps 1.4 codebase and is close to a release-candidate stage in several technical areas.

However, the next public release should not be proposed as stable yet.

The principal remaining release gates are:

1. complete the **P1 content interoperability baseline**;
2. finish security and runtime audits;
3. validate upgrades from representative legacy installations;
4. complete the Geeklog 2.1.1 / 2.2.2 functional matrix;
5. verify packaging and version consistency;
6. run an RC period focused on regressions rather than new features.

The interoperability work is deliberately part of **1.5.x**, not postponed to 2.0, because it provides a small stable public surface without requiring a data-layer rewrite.

---

## Status overview

| Area | Status | Priority |
| --- | --- | --- |
| Core compatibility / PHP modernization | ~90% | Critical |
| Google Maps modernization | ~95% | Critical |
| Upgrade / legacy migration | ~85% | Critical |
| Configuration cleanup | ~90% | High |
| Security hardening | ~80–85% | Critical |
| Public/admin runtime stability | ~80–85% | Critical |
| Plugin content interoperability | ~25% | **Critical / release gate** |
| What’s New / statistics integration | ~85–90% | High |
| UI / UX modernization | ~65–70% | High |
| Documentation | ~80% | Medium |
| Test matrix / release validation | ~55–60% | Critical |

Percentages are directional indicators, not release guarantees.

The interoperability percentage reflects that Maps already has useful permission-aware recent-content query logic for What’s New, but does not yet expose the P1 structured contract required by the memorandum.

---

# Phase 1 — Finish the 1.5.x compatibility layer

## 1. Google Maps rendering

Status: **In progress / close to complete**

Completed:

- Load the Maps JavaScript API through a central helper.
- Remove the retired `sensor` parameter.
- Remove Google Maps AdSense integration.
- Replace retired Google Image Charts marker URLs with SVG markers.
- Replace the bundled historical MarkerClusterer with `@googlemaps/markerclusterer` 2.6.2.
- Preserve `google.maps.Marker` for the 1.5 compatibility release.
- Add browser API key diagnostics in the Maps administration area.
- Normalize historical coordinates stored with comma decimal separators.
- Make JavaScript numeric serialization independent of the server locale.
- Add explicit JavaScript initialization diagnostics.

Remaining:

- Audit every rendering path for coordinates that bypass the central normalization helpers.
- Validate map centers, marker positions and overlay bounds before rendering.
- Verify map initialization when map dimensions come from old installations.
- Verify Google Maps loading on public pages, administration pages, profiles and autotags.
- Check for duplicate Google Maps script loads in every execution path.

Exit criteria:

- No grey maps caused by invalid legacy coordinates or script-loading order.
- All supported map rendering paths work on Geeklog 2.1.1 and 2.2.2.

## 2. PHP and Geeklog compatibility

Status: **In progress / close to complete**

Completed:

- Target Geeklog 2.1.1 through 2.2.2.
- Target PHP 5.6 through 8.1.
- Use `COM_createHTMLDocument()` when available, including Geeklog 2.1.1.
- Handle the Geeklog user storage change from `userinfo` to `user_attributes`.
- Remove PHP `preg_replace(... /e ...)` usage.
- Fix numerous undefined-array-key and uninitialized-variable cases discovered during runtime testing.

Remaining audit:

- Direct `$_GET`, `$_POST`, `$_REQUEST` and `$_FILES` access.
- Optional-before-required function signatures.
- `count(null)` and null passed to string functions.
- `DB_fetchArray(false)` and unchecked query results.
- Uninitialized `.=` accumulators.
- Callback signatures used by `ADMIN_list`.
- Large timestamp-like marker identifiers on 32-bit PHP.
- Template variables that may remain unset in uncommon rendering paths.

Exit criteria:

- No Maps-generated PHP warnings during the functional test matrix.
- PHP lint passes for every PHP/INC file.

---

# Phase 2 — Content interoperability baseline

Status: **Required before RC1**

Maps must comply with the common Geeklog Plugin Content Interoperability Contract so Hello, Hub, IndexNow, Sitemap, Search, recommendations and future consumers can reuse Maps content without reading Maps tables directly.

This phase should stay intentionally small and compatibility-oriented. It must not become a new Maps-specific service API.

## 3. Structured Item Info — P1

Implement:

```php
function plugin_getiteminfo_maps($id, $what, $uid = 0, $options = array())
```

For an addressable map, expose at minimum:

```text
id
title
url
description or excerpt
date-created
date-modified
```

Where practical also expose:

```text
uid
author
type
subtype
```

Recommended identity values:

```text
type = maps
subtype = map
```

The plugin remains responsible for permissions, SQL structure, routing and formatting.

Consumers must never need to know that Maps stores identifiers in `mid`, titles in `name`, or dates in internal table fields.

### Implementation rule

Do not duplicate the permission-aware recent-map query already used by What’s New.

Create or reuse an internal Maps query/helper layer that can serve both:

- `plugin_getiteminfo_maps()` structured results;
- `plugin_getwhatsnew_maps()` presentation HTML.

This keeps What’s New as a renderer while Item Info becomes the normalized data contract.

## 4. Collection retrieval — P1

Support:

```php
$id = '*';
```

When `'*'` is requested, return an array of normalized map records.

Initial common options:

```php
$options = array(
    'since' => $timestamp,
    'limit' => 20,
    'order' => 'modified-desc'
);
```

Required initial behavior:

- `since` — maps created or modified at or after the supplied timestamp;
- `limit` — bounded maximum result count;
- `order` — at least `modified-desc` and `created-desc`;
- current-user permission filtering;
- exclude inactive/hidden maps from ordinary public collection queries.

Future filters such as `ids`, `author`, `category` or subtype should wait until a concrete consumer needs them.

## 5. Lifecycle events — P1

After every successful map creation or update, emit:

```php
PLG_itemSaved($map_id, 'maps');
```

After every successful map deletion, emit:

```php
PLG_itemDeleted($map_id, 'maps');
```

Audit **all mutation paths**, including:

- normal administration save;
- alternate edit paths;
- import routines if they create/update addressable maps;
- any future service or batch operation.

The event must be emitted only after the corresponding database mutation succeeds.

### Marker events

Markers are useful interoperable content, but they should not block the first 1.5 interoperability baseline unless a consumer immediately needs them.

Recommended sequence:

1. make **maps** fully interoperable first;
2. model marker support as `subtype = marker` after the map contract is stable;
3. preserve nullable subtype compatibility for older Geeklog lifecycle APIs.

## 6. Canonical item URL resolution — P2

For Geeklog versions that support the callback, implement:

```php
function plugin_idtourl_maps($sub_type, $item_id)
```

Initial behavior:

- `map` or empty/legacy subtype resolves to the canonical map URL;
- future `marker` subtype may resolve marker detail URLs;
- unsupported subtype returns a safe failure value rather than guessing.

`plugin_getiteminfo_maps(..., 'url', ...)` remains mandatory as the compatibility fallback across Geeklog 2.1.1–2.2.2.

## 7. Preserve What’s New as presentation — already substantially implemented

Maps already implements:

```php
plugin_whatsnewsupported_maps()
plugin_getwhatsnew_maps()
```

Keep this capability.

Refactor it so the presentation code consumes the same underlying permission-aware content retrieval used by Item Info where practical.

Do not make Hello, Hub or IndexNow parse What’s New HTML.

## 8. Interoperability tests

Add explicit tests to the release checklist:

Single item:

```php
PLG_getItemInfo('maps', $map_id, 'id,title,url,excerpt,date-modified');
```

Collection:

```php
PLG_getItemInfo(
    'maps',
    '*',
    'id,title,url,excerpt,date-modified',
    0,
    array('since' => $timestamp, 'limit' => 20, 'order' => 'modified-desc')
);
```

Lifecycle:

- create a map → one save notification;
- edit a map → one save notification;
- delete a map → one delete notification;
- failed save/delete → no lifecycle notification.

Permissions:

- anonymous user does not receive private maps;
- authenticated users receive only maps they can access;
- collection results obey the same visibility rules as direct item retrieval.

URL resolution:

- canonical URL from Item Info matches the public Maps route;
- `plugin_idtourl_maps()` matches Item Info where supported.

### Interoperability exit criteria

Before RC1, all of the following must be true:

- `plugin_getiteminfo_maps()` implemented;
- single-map metadata implemented;
- `'*'` collection implemented;
- `since`, `limit`, `order` implemented;
- permission behavior validated;
- `PLG_itemSaved()` added to all relevant map save/update paths;
- `PLG_itemDeleted()` added to all relevant map delete paths;
- canonical URL available through Item Info;
- `plugin_idtourl_maps()` implemented for supported Geeklog versions or explicitly compatibility-guarded;
- What’s New still works after query refactoring.

---

# Phase 3 — Configuration and upgrade stabilization

## 9. Configuration cleanup

Status: **In progress**

Goals:

- Keep user-facing configuration in Geeklog configuration rather than hard-coded in scripts.
- Keep technical implementation constants internal when exposing them would add complexity without user benefit.

Configuration organization:

- General
- Google Maps
- Maps
- Markers
- Marker fields
- Integrations and statistics

Completed / planned configurable values include:

- Google browser and server API keys
- Google language and region
- Global/profile/geo display settings
- Default map settings
- Marker defaults
- Marker edit-map dimensions / zoom / type
- Marker detail-map dimensions / zoom
- Info-window dimensions
- Event-map dimensions / zoom
- Default map colors
- Image upload limits
- Permissions and marker field visibility
- What’s New enable/interval/limit settings
- administration/public statistics settings

Keep internal unless a real use case appears:

- Google Maps API `weekly` channel
- MarkerClusterer implementation version
- SVG pin geometry
- internal coordinate precision
- small administration thumbnail sizes

Remaining:

- Verify all tab labels exist in every supported language file.
- Audit scripts/templates for other meaningful hard-coded UI values.
- Verify restored-default behavior after configuration migration.
- Verify configuration rendering on both Geeklog 2.1.1 and 2.2.2.

## 10. Upgrade and legacy preservation

Status: **In progress**

Completed:

- Remove obsolete monetization / Google Ads settings.
- Preserve useful historical configuration values.
- Migrate legacy SEO/public folder information outside normal user configuration.
- Standardize the public plugin directory on `/maps/`.
- Preserve old public folders such as `/cartes/` as 301 redirect shells where possible.
- Use only paths declared by Geeklog for filesystem migration.
- Preserve shared map resources in `images/maps/icons/` and `images/maps/overlays/`.

Remaining:

- Test upgrades from representative historical installations.
- Test direct upgrade from the supported legacy baseline.
- Test upgrade from each modern alpha/beta configuration layout where needed.
- Confirm idempotence: reloading an upgrade must not duplicate configuration records or damage redirect folders.
- Document backup and rollback expectations.

Exit criteria:

- Upgrade preserves maps, markers, permissions, configuration and indexed public URLs.

---

# Phase 4 — Security and data hardening

Status: **High priority before RC**

Audit and fix:

- CSRF protection on administrative and destructive actions.
- Permission checks before edit/delete/import/export operations.
- HTML escaping in public and admin output.
- JavaScript serialization of titles, descriptions and InfoWindow content.
- User profile content embedded in map JavaScript.
- Upload MIME/extension validation and destination handling.
- Overlay/icon filenames and path traversal resistance.
- Import/export input validation.
- SQL values and identifier validation.
- Geocoding input/output handling.
- Interoperability callbacks must not bypass existing permission rules.
- Collection queries must use bounded limits and validated sort modes.

Already completed:

- Remove TimThumb.
- Remove installation/upgrade telemetry email.
- Add safer database and JavaScript helper functions.
- Begin replacing unsafe historical interpolation patterns.

Exit criteria:

- No known high-risk input path remains unchecked.
- Destructive actions require appropriate rights and request validation.
- Item Info cannot expose inaccessible content.

---

# Phase 5 — UI / UX modernization

Status: **Major remaining product-quality phase**

The goal is not a visual rewrite. The goal is to make Maps easier to understand and faster to operate while remaining compatible with Geeklog themes.

## 11. Administration navigation

Improve the Maps administration home page so the main workflows are immediately visible:

- Create a map
- Add a marker
- Manage maps
- Manage markers
- Icons
- Overlays
- Geocoder
- Import / Export
- Configuration

Dashboard information worth exposing:

- number of maps
- number of markers
- number of geolocated users
- markers with missing/invalid coordinates
- overlays/icons in use

Avoid displaying low-value technical statistics.

## 12. Map edit form

Reorganize into clear sections:

1. Information
2. Display
3. Center & zoom
4. Markers
5. Permissions
6. Advanced options

Improvements:

- map preview while editing
- “Use current map position as center” action
- clearer width/height controls
- contextual help for map type and zoom
- advanced options collapsed by default
- consistent save/cancel actions

## 13. Marker edit form

Reorganize into:

1. Location
2. Content
3. Contact
4. Appearance
5. Publication / permissions
6. Advanced options

Improvements:

- address search followed by draggable marker positioning
- latitude/longitude shown as secondary technical fields
- marker/icon live preview
- current-map context visible while editing
- clearer handling of the additional-contact field
- preserve form values after validation errors

## 14. Lists

Modernize map and marker lists with:

- search
- useful filters
- sorting
- status badges
- marker count per map
- visible primary actions
- responsive layout
- confirmation before destructive actions

Candidate convenience actions:

- duplicate map
- duplicate marker
- quickly enable/disable an item where the data model permits it

## 15. Public map experience

Keep the map visually dominant.

Improve progressively:

- responsive map sizing
- clearer InfoWindows
- optional collapsible description
- optional marker list / filters where useful
- directions panel that does not overwhelm the map
- sensible mobile behavior
- accessible controls and labels

Do not introduce a plugin-specific front-end framework solely for Maps.

Exit criteria for the 1.5 stable release:

- Core workflows can be understood without reading documentation first.
- Forms remain compatible with existing Geeklog themes.
- Mobile use is practical for common map/marker operations.

---

# Phase 6 — Functional test matrix

Status: **Required before RC**

Run the same functional workflow on both Geeklog targets rather than assuming identical behavior.

## Geeklog environments

Minimum:

- Geeklog 2.1.1 + representative supported legacy PHP
- Geeklog 2.2.2 + PHP 8.1

Where practical also lint/test against intermediate PHP versions used by CI.

## Screen-by-screen checklist

Public:

- map index
- individual map
- global map
- user map
- marker detail
- user marker list
- public marker submission
- profile map
- autotags: `maps`, `geo`, `marker`
- event map integration
- What’s New Maps section
- public statistics when enabled

Administration:

- Maps dashboard
- map create/edit/delete
- marker create/edit/delete
- icons
- overlays
- geocoder
- geolocation refresh
- import/export
- configuration and every configuration tab
- administration statistics when enabled

Interoperability:

- Item Info single map
- Item Info collection
- `since`, `limit`, `modified-desc`, `created-desc`
- permission filtering
- save/update lifecycle notification
- delete lifecycle notification
- Item Info URL fallback
- `plugin_idtourl_maps()` on supported core versions
- What’s New after interoperability refactor

Data cases:

- decimal points
- decimal commas
- empty coordinates
- invalid coordinates
- old numeric dimensions without CSS units
- Unicode titles/descriptions
- quotes/apostrophes
- multiline descriptions
- empty optional fields
- large historical marker IDs

Upgrade cases:

- historical public folder name
- customized marker field labels
- old Google settings
- existing icons/overlays
- existing permissions

Exit criteria:

- No Maps PHP warning/error.
- No Maps JavaScript syntax/runtime error.
- No unintended data loss.
- No regression between Geeklog 2.1.1 and 2.2.2.
- No interoperability callback exposes content that the current user cannot access.

---

# Phase 7 — Documentation, packaging and release preparation

## Documentation

Before RC:

- Update README installation and upgrade instructions.
- Document Google Cloud APIs and recommended key restrictions.
- Document browser vs server API keys.
- Document maps, markers, icons and overlays in user-oriented language.
- Document geocoding and user geolocation behavior.
- Document legacy `/cartes/` → `/maps/` behavior.
- Add a short troubleshooting section for grey maps / API failures.
- Document the Maps interoperability capabilities for plugin developers.
- Link to the common Plugin Content Interoperability Contract rather than duplicating it in full.

## Packaging/version consistency

Before RC1:

- verify every plugin header and metadata location reports the same version;
- verify `functions.inc`, `maps.php`, `autoinstall.php`, upgrade metadata and package name agree;
- verify packaged archive root layout;
- verify removed legacy assets are not accidentally shipped;
- verify required new files such as `integrations.php` are included;
- run package installation from the generated archive, not only from a Git checkout;
- confirm PHP lint workflow results for the exact RC commit.

Current note:

- plugin metadata is already on **1.5.7**;
- at least one source-file header may still carry an earlier 1.5.x identifier and should be normalized before release packaging.

## Release sequence

Preferred sequence:

1. Complete the interoperability P1 baseline.
2. Finish remaining 1.5.x runtime fixes.
3. Complete security sweep.
4. Complete upgrade validation.
5. Complete essential UI/UX pass.
6. Complete 2.1.1 functional matrix.
7. Complete 2.2.2 functional matrix.
8. Verify package/version consistency.
9. Release `1.5.x-rc1`.
10. Fix RC feedback only; avoid feature growth.
11. Release stable.

A stable release should not be declared solely because PHP lint passes; runtime validation on the supported Geeklog environments is required.

---

# Proposed release gates

## Must be complete for RC1

- P1 interoperability contract
- no known critical/high security issue
- successful upgrade from at least one representative Maps 1.4 installation
- clean PHP lint/package workflow
- no known fatal error on supported Geeklog targets
- key public/admin workflows usable
- version/package consistency

## May be completed during RC if non-blocking

- small UI polish
- secondary accessibility improvements
- documentation refinements
- low-risk warning cleanup on uncommon paths

## Must not delay 1.5 stable

- marker-level interoperability unless required by an immediate consumer
- advanced recommendations
- new specialized service APIs
- AdvancedMarkerElement migration
- architectural repository/service rewrite
- major schema redesign

---

# Future 2.0 roadmap

These changes are valuable but should not delay the 1.5 stable release.

## Architecture

Possible 2.0 work:

- separate data access from rendering
- repositories/services for maps, markers and overlays
- reduce dependence on globals
- centralize permission and validation logic
- consolidate JavaScript generation into reusable front-end modules
- review SQL schema and indexes
- automated upgrade tests
- broader integration test coverage

The 1.5 interoperability helpers should be designed so they can later call a cleaner 2.0 data layer without changing the public Geeklog callback contract.

## Interoperability 2.0 candidates

After the map-level contract is stable:

- marker Item Info using `subtype = marker`;
- marker lifecycle events;
- related map/marker discovery;
- `plugin_getrelateditems_maps()` where Hub/recommendations have a concrete use case;
- richer image/category metadata;
- optional specialized services only for actions that cannot be represented by Item Info.

## Google Maps next generation

Evaluate after 1.5 stable:

- migrate `google.maps.Marker` to `AdvancedMarkerElement`
- make fuller use of Google Map IDs
- modern marker content/HTML
- optional lazy loading where compatible with Geeklog rendering

These changes should be introduced only when compatibility implications are understood.

## UX 2.0 candidates

- richer map-side marker management
- batch marker editing
- drag-and-drop marker organization
- more advanced filtering
- better overlay management
- optional map style presets

---

# Development rules

Until the stable 1.5 release:

> Prefer stabilization, compatibility, data preservation, interoperability and usability improvements over architectural rewrites or feature expansion.

When a runtime issue is found, fix the underlying pattern and audit the same pattern elsewhere instead of applying a page-specific workaround.

For interoperability specifically:

> The Maps plugin remains the authority for Maps content. Other plugins should consume normalized Geeklog callbacks and must not depend on Maps SQL tables, column names, internal paths or routing implementation.
