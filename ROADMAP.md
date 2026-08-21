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

The current development line is **1.5.x**. A future **2.0** may introduce deeper architectural changes that are intentionally out of scope for the stabilization release.

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
| UI / UX modernization | ~65–70% | High |
| Documentation | ~80% | Medium |
| Test matrix / release validation | ~55–60% | Critical |

Percentages are directional indicators, not release guarantees.

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

# Phase 2 — Configuration and upgrade stabilization

## 3. Configuration cleanup

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

## 4. Upgrade and legacy preservation

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

# Phase 3 — Security and data hardening

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

Already completed:

- Remove TimThumb.
- Remove installation/upgrade telemetry email.
- Add safer database and JavaScript helper functions.
- Begin replacing unsafe historical interpolation patterns.

Exit criteria:

- No known high-risk input path remains unchecked.
- Destructive actions require appropriate rights and request validation.

---

# Phase 4 — UI / UX modernization

Status: **Major remaining product-quality phase**

The goal is not a visual rewrite. The goal is to make Maps easier to understand and faster to operate while remaining compatible with Geeklog themes.

## 5. Administration navigation

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

## 6. Map edit form

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

## 7. Marker edit form

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

## 8. Lists

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

## 9. Public map experience

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

# Phase 5 — Functional test matrix

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

---

# Phase 6 — Documentation and release preparation

## Documentation

Before RC:

- Update README installation and upgrade instructions.
- Document Google Cloud APIs and recommended key restrictions.
- Document browser vs server API keys.
- Document maps, markers, icons and overlays in user-oriented language.
- Document geocoding and user geolocation behavior.
- Document legacy `/cartes/` → `/maps/` behavior.
- Add a short troubleshooting section for grey maps / API failures.

## Release sequence

Preferred sequence:

1. Finish 1.5.x runtime fixes.
2. Complete UI/UX pass.
3. Complete security sweep.
4. Complete 2.1.1 functional matrix.
5. Complete 2.2.2 functional matrix.
6. Release `1.5.x-rc1`.
7. Fix RC feedback only; avoid feature growth.
8. Release stable.

A stable release should not be declared solely because PHP lint passes; runtime validation on the supported Geeklog environments is required.

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

# Development rule

Until the stable 1.5 release:

> Prefer stabilization, compatibility, data preservation and usability improvements over architectural rewrites or feature expansion.

When a runtime issue is found, fix the underlying pattern and audit the same pattern elsewhere instead of applying a page-specific workaround.
