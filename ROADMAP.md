# Maps for Geeklog — Modernization Roadmap

This roadmap tracks the final stabilization of Maps 1.5.10 and the remaining work before the official release.

Last updated: August 27, 2026

## Release target

Maps 1.5.10 targets:

- Geeklog 2.1.1 through 2.2.2;
- PHP 5.6 through 8.3, with the core compatibility target fully covering PHP 5.6 through 8.1;
- MySQL/MariaDB versions supported by the corresponding Geeklog release;
- current Google Maps Platform behavior in 2026;
- preservation of existing Maps data and public URLs whenever practical;
- safe upgrade from Maps 1.4.x/1.5.x through Geeklog's normal plugin upgrade mechanism;
- compliance with the Geeklog Plugin Content Interoperability Contract;
- interoperable lifecycle notifications consumable by IndexNow, Hub, Hello and other listeners without direct plugin dependencies.

The 1.5.10 line is now in **release-candidate validation mode**. Feature scope should be considered frozen. New work before RC1 should be limited to compatibility, security, data-integrity, upgrade or release-blocking regressions.

---

## Current release assessment

The implementation work planned for the 1.5.x modernization is complete. The remaining release gates are primarily functional validation gates.

| Area | Status | RC1 assessment |
| --- | --- | --- |
| Geeklog/PHP compatibility layer | Complete for code | Final matrix testing |
| Google Maps modernization | Complete for 1.5.x scope | Smoke-test rendering paths |
| Security hardening | Complete for reviewed scope | Final regression testing |
| Runtime/lifecycle consistency | Complete | Validate mutation paths |
| Content interoperability | Complete | Validate external consumers |
| Marker interoperability | Complete | Validate `marker:<mkid>` consumers |
| Marker service API | Complete | Validate Documents/Store consumers |
| Content Syndication | Complete | Validate feed generation |
| XML Sitemap | Complete | Validate map + marker entries |
| Related Items | Complete | Validate topic assignments |
| What's New | Complete | Validate rendering |
| Statistics | Complete | Validate public/admin rendering |
| Public SEO | Complete for 1.5.10 scope | Validate generated HTML |
| Install metadata | Complete | Fresh-install test required |
| Upgrade path | Implemented through 1.5.10 | Legacy upgrade test required |
| Packaging | Reproducible | Final archive verification |
| Multi-PHP syntax CI | Complete | PHP 5.6 / 7.4 / 8.1 / 8.3 green |
| Release notes | Complete through 1.5.10 | Final editorial review |

**RC1 should be cut after the functional install/upgrade matrix passes.**

---

# Phase 1 — Compatibility and runtime stabilization

Status: **Implementation complete / final validation required**

Completed:

- Geeklog 2.1.1 through 2.2.2 compatibility layer;
- PHP 5.6 through 8.3 install compatibility gate;
- syntax CI on PHP 5.6, 7.4, 8.1 and 8.3;
- `COM_createHTMLDocument()` compatibility rendering;
- Geeklog `userinfo` / `user_attributes` compatibility;
- removal of PHP constructs incompatible with PHP 8;
- central coordinate and numeric normalization;
- locale-independent JavaScript numeric serialization;
- central Google Maps JavaScript API URL/loading helpers;
- removal of retired `sensor`, Google Maps AdSense and Google Image Charts dependencies;
- replacement of historical MarkerClusterer with `@googlemaps/markerclusterer` 2.6.2;
- browser-side Google Maps API diagnostics in administration;
- SVG marker generation while retaining uploaded custom icons;
- map-save redirect to the public map page for immediate visual validation.

Remaining validation:

- map list and map detail;
- marker list, create, edit, move and delete;
- overlays and overlay groups;
- uploaded icons and overlay images;
- global map;
- profile map;
- Calendar integration where available;
- `[maps:]`, `[geo:]` and `[marker:]` autotags;
- Google API key failure and success paths;
- legacy comma-decimal coordinates and historical dimensions.

Exit criterion: no Maps-generated PHP warnings or JavaScript errors in the supported functional matrix.

---

# Phase 2 — Content interoperability and lifecycle

Status: **Complete for Maps 1.5.10**

Implemented for maps:

- `plugin_getiteminfo_maps()` for individual maps;
- collection retrieval with `id='*'`;
- `since`, `limit` and `order` options;
- `modified-desc` and `created-desc` ordering;
- normalized `id`, `title`, `url`, `description`, `excerpt`, dates, owner/author, type and subtype;
- `type = maps`, `subtype = map`;
- permission-aware public and authenticated retrieval;
- inactive/hidden filtering;
- `plugin_idtourl_maps()` canonical URL resolution;
- lifecycle events after successful map save/delete;
- centralized `updateMap()` lifecycle semantics.

Implemented for markers:

- namespaced interoperable identifier `marker:<mkid>`;
- `plugin_getiteminfo_maps('marker:<mkid>', ...)` support;
- canonical marker URL resolution through `plugin_idtourl_maps()`;
- `subtype = marker` metadata;
- marker create/update emits `PLG_itemSaved('marker:<mkid>', 'maps')`;
- marker delete emits `PLG_itemDeleted('marker:<mkid>', 'maps')`;
- deterministic URL resolution still works after deletion;
- marker changes also notify the parent map;
- moving a marker notifies both old and new parent maps;
- CSV imports emit one marker event per inserted marker and one parent-map event per batch;
- marker validity changes through services emit the same lifecycle semantics.

This enables IndexNow, Hub, Hello and other plugins to consume Maps changes without any direct dependency from Maps to those plugins.

Validation before RC1:

- direct `PLG_getItemInfo()` map retrieval;
- direct marker Item Info retrieval using `marker:<mkid>`;
- anonymous/private permission tests;
- ID-to-URL resolution for map and marker IDs;
- marker URL resolution after deletion;
- one marker lifecycle event per successful mutation;
- parent-map lifecycle propagation after marker mutation;
- old + new parent-map notification when moving a marker;
- no lifecycle notification after failed/no-op mutations.

---

# Phase 3 — Inter-plugin Marker Service API

Status: **Complete for 1.5.10 scope**

Maps remains the sole owner of marker records, permissions, rendering, coordinates, validity and lifecycle.

Implemented services:

- `marker_list` — list/select accessible markers;
- `marker_get` — retrieve structured marker data;
- `marker_render` — render a reusable mini-map for another plugin;
- `marker_set_validity` — set a validity interval;
- `marker_extend_validity` — extend marker visibility from `max(now, current validity_end)`.

Safety and commerce behavior:

- mutation services are intended for trusted internal plugin calls;
- HTTP/webservice-originated mutation calls are rejected;
- Store-style operations support `source`, `source_id` and `operation_id`;
- service operations are idempotent so duplicate payment callbacks cannot extend validity twice;
- the dedicated `maps_service_operations` table records claimed operations;
- services return the canonical `/maps/index.php?mode=marker&mkid=...` URL;
- successful validity changes trigger marker + parent-map lifecycle events.

Concrete intended consumers:

- Documents: select one Maps marker and render it on a document page while marker CRUD remains in Maps;
- Store: associate a commercial item with a marker and sell/extend marker visibility duration.

Validation before RC1:

- `marker_list`, `marker_get` and `marker_render` with anonymous and authenticated permissions;
- `marker_set_validity` and `marker_extend_validity` through an internal service call;
- repeated identical `operation_id` does not apply twice;
- same `operation_id` cannot be reused for another action/marker;
- failed mutation rolls back the reserved operation;
- lifecycle listeners receive marker and map events after successful validity change.

---

# Phase 4 — Native Geeklog integrations

Status: **Complete for 1.5.10 scope**

## What's New

Maps retains native What's New integration using permission-aware map retrieval.

## Content Syndication

Implemented:

- native feed source discovery;
- feed content generation;
- feed update checks;
- anonymous/public permission filtering;
- feed item and temporal limits;
- RSS/Atom extension support where Geeklog requests it.

## Statistics

Implemented:

- native Geeklog statistics callbacks;
- global map count;
- visible marker count;
- aggregate map views;
- aggregate marker views;
- statistics cards on `/maps/`;
- per-map views, visible marker count and aggregate marker views on map detail pages;
- configuration-aware public statistics visibility;
- permission-aware queries without N+1 retrieval.

## XML Sitemap

Implemented:

- canonical map pages;
- canonical marker pages;
- modified timestamps;
- map and marker visibility/permission filtering;
- parent-map permission validation for marker entries;
- limit support.

## Related Items

Implemented using Geeklog's central topic assignments. Maps does not fabricate similarity when no topic assignment exists.

Exit criterion: validate each native integration on both supported Geeklog generations where the corresponding core capability exists.

---

# Phase 5 — Public SEO

Status: **Complete for 1.5.10 scope**

Implemented:

- canonical Maps landing-page URL;
- canonical map URLs;
- canonical marker URL `/maps/index.php?mode=marker&mkid=...`;
- permanent 301 redirect from historical `markers.php?mode=show...` detail URLs;
- internal marker links aligned with the canonical URL;
- dedicated landing-page SEO title configuration;
- independent landing-page H1 configuration;
- dedicated landing-page meta description configuration;
- automatic fallbacks when SEO fields are empty;
- map and marker dynamic meta descriptions;
- Open Graph and Twitter metadata;
- real 404 responses for invalid, hidden or inaccessible public map/marker pages;
- `noindex,follow` for private/edit/navigation-only marker screens;
- semantic H1 on map and marker public pages;
- marker-to-parent-map HTML link;
- Schema.org `Place`, `PostalAddress` and `GeoCoordinates` JSON-LD for markers;
- sitemap entries use the same canonical marker URLs.

Validation before RC1:

- inspect generated `<title>`, H1, description, canonical, OG/Twitter and JSON-LD on `/maps/`;
- inspect the same metadata on one public map and one public marker;
- verify legacy marker detail URL returns 301;
- verify inaccessible/nonexistent map and marker return 404;
- verify private/edit marker pages expose `noindex`;
- validate representative marker JSON-LD with a structured-data validator.

Deferred SEO improvements are listed below and are not release blockers.

---

# Phase 6 — Security hardening

Status: **Implementation complete / final regression required**

Completed hardening includes:

- administrator rights checks on mutation endpoints;
- POST-only state-changing operations;
- Geeklog CSRF tokens on map, marker, overlay, overlay-group, icon and geolocation mutations;
- identifier normalization;
- SQL escaping/validation on modernized mutation paths;
- safe HTML output on reviewed administration paths;
- icon and overlay upload extension/MIME restrictions;
- generated upload filenames and basename-safe deletes;
- private CSV staging in Geeklog `path_data`;
- CSV field whitelist, MIME validation, coordinate validation and CSRF confirmation;
- prevention of GET-based map/marker/overlay/icon/geolocation mutations;
- AJAX overlay mutation CSRF and relationship validation;
- lifecycle notifications only after successful dependency changes;
- internal-only protection around marker mutation services.

Final RC regression tests:

- invalid/expired CSRF token;
- GET request against mutation routes;
- invalid IDs;
- unauthorized administrator access;
- invalid image extension/MIME;
- malformed CSV;
- stale AJAX overlay association requests;
- external/webservice call against marker validity mutation service.

---

# Phase 7 — Install and upgrade validation

Status: **Code prepared / functional validation required**

Completed:

- plugin version metadata set to 1.5.10;
- install compatibility aligned with Geeklog 2.1.1–2.2.2 and PHP 5.6–8.3;
- autoinstall table list aligned with the actual Maps schema including `maps_service_operations`;
- obsolete marker category/field/value table aliases removed;
- portable MySQL installer definitions;
- CSV imports compatible with strict SQL modes;
- sequential 1.5.x configuration repair/migration functions designed to be idempotent;
- 1.5.9 service-operation table migration;
- 1.5.10 landing-page SEO configuration migration;
- canonical `/maps/` public-folder migration retained through normal Geeklog upgrade.

Required matrix before RC1:

1. **Fresh install** — Geeklog 2.1.1 + PHP 5.6-compatible environment.
2. **Fresh install** — Geeklog 2.2.2 + PHP 8.1.
3. **Optional extended check** — Geeklog 2.2.2 + PHP 8.3.
4. **Upgrade** — representative Maps 1.4.x installation → 1.5.10 on Geeklog 2.1.1.
5. **Upgrade** — Maps 1.5.7/1.5.8/1.5.9 → 1.5.10 with existing maps, markers, icons and overlays.
6. Confirm all expected Maps tables and configuration rows, including `maps_service_operations` and the three landing SEO settings.
7. Confirm `/maps/`, shared `images/maps/icons/` and `images/maps/overlays/` paths.
8. Confirm a second upgrade invocation is harmless/idempotent.
9. Confirm obsolete `infos_label` is absent after upgrade.
10. Confirm uninstall removes only Maps-owned tables/configuration and does not remove unrelated shared data.

Any failure in this phase is an RC1 blocker.

---

# Phase 8 — Packaging and RC1

Status: **Packaging automated / RC1 pending validation matrix**

The packaging workflow:

- does not modify plugin source code;
- lints interoperability, services, install and security-critical files;
- builds `maps-1.5.10-test.zip` from branch contents;
- commits only the generated test archive to `dist/` when it changes.

Before RC1:

- verify ZIP contains one top-level `maps/` directory;
- verify all required 1.5.10 files are present, including `services.inc.php` and `RELEASE-NOTES-1.5.10.md`;
- verify no temporary migration workflow/script or build directory is packaged;
- install the ZIP through Geeklog's normal plugin administration UI;
- complete the functional and upgrade matrix above;
- perform final release-note/editorial review;
- update the pull-request title/body to 1.5.10 if still referring to an older version;
- rebuild the candidate archive from the exact RC commit.

After RC1, accept **bug fixes only** unless an issue is a demonstrated compatibility, security or data-integrity blocker.

---

# Remaining work before RC1 — short checklist

No additional mandatory feature is currently identified.

The remaining release work is:

- [ ] Fresh install on Geeklog 2.1.1 / PHP 5.6-compatible stack.
- [ ] Fresh install on Geeklog 2.2.2 / PHP 8.1.
- [ ] Upgrade representative legacy Maps data to 1.5.10.
- [ ] Re-run upgrade to prove idempotence.
- [ ] Full map/marker CRUD and move regression test.
- [ ] Overlay/icon/import regression test.
- [ ] Validate map + marker lifecycle events with a simple listener or IndexNow test build.
- [ ] Validate the five marker services through a real consumer or focused harness.
- [ ] Validate public SEO output, canonical/301/404 and marker JSON-LD.
- [ ] Validate feeds, sitemap, Related Items, What's New and statistics.
- [ ] Install the generated ZIP through Geeklog administration.
- [ ] Confirm final package cleanliness and release notes.
- [ ] Update PR metadata to 1.5.10 if necessary.

Once these checks pass, Maps 1.5.10 can move to RC1 without another feature release.

---

# Deferred after 1.5.10

These items should not delay RC1.

## SEO / public rendering

- optional per-map `seo_title` override while keeping map `name` as fallback;
- specialized marker Schema.org types such as `LocalBusiness`, `Store`, `Restaurant`, `Hotel` and `TouristAttraction`, with type-appropriate properties;
- `CollectionPage` + `ItemList` structured data for map pages;
- human-readable SEO URLs such as `/maps/map-slug/marker-slug/`, with permanent redirects from query-string URLs;
- advanced lazy loading / IntersectionObserver initialization for Google Maps to improve Core Web Vitals;
- richer Open Graph images/social cards for maps and markers.

## Google Maps platform

- migration from legacy `google.maps.Marker` to Advanced Markers/importLibrary when compatibility requirements allow it;
- broader lazy-loading modernization once the legacy Geeklog compatibility requirement is relaxed.

## Architecture / interoperability

- optional richer relations between Maps markers and Hub/Documents/Store content;
- optional UI in Documents for marker selection and rendering;
- optional Store UI for associating products/listings with marker validity purchases;
- deeper data-layer abstraction/repository architecture;
- REST exposure only if a concrete external use case appears and security semantics are defined.

## UI

- major visual redesign beyond the completed statistics/admin improvements;
- additional map/marker analytics visualizations when concrete needs are identified.

The priority is now to **prove and release Maps 1.5.10**, not expand its scope.