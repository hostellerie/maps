# Maps for Geeklog — Modernization Roadmap

This roadmap tracks the final stabilization of Maps 1.5.8 and the work required before the official release.

Last updated: August 27, 2026

## Release target

Maps 1.5.8 targets:

- Geeklog 2.1.1 through 2.2.2
- PHP 5.6 through 8.3
- MySQL/MariaDB versions supported by the corresponding Geeklog release
- current Google Maps Platform behavior in 2026
- preservation of existing Maps data and public URLs whenever practical
- safe upgrade from Maps 1.4.0 through Geeklog's normal plugin upgrade mechanism
- compliance with the Geeklog Plugin Content Interoperability Contract

The 1.5.8 line is now in **release-candidate validation mode**. New features should be deferred unless they fix a release blocker. A future 2.0 may introduce deeper architectural changes.

---

## Current release assessment

The principal implementation work required for RC1 is complete. The remaining release gates are now validation gates rather than feature gates.

| Area | Status | RC1 assessment |
| --- | --- | --- |
| Geeklog/PHP compatibility layer | Complete for code / validation pending | Ready for matrix testing |
| Google Maps modernization | Complete for 1.5.x scope | Smoke-test all rendering paths |
| Security hardening | Substantially complete | Final regression testing |
| Runtime/lifecycle consistency | Substantially complete | Final regression testing |
| Content interoperability | Complete for 1.5.8 scope | Validate consumers |
| Content Syndication | Complete | Validate feed generation |
| XML Sitemap | Complete | Validate generated entries |
| Related Items | Complete | Validate topic assignments |
| What’s New | Complete | Validate rendering |
| Statistics | Complete | Validate public/admin rendering |
| Install metadata | Complete | Fresh-install test required |
| Upgrade path | Implemented | Legacy upgrade test required |
| Packaging | Reproducible | Final archive verification |
| Multi-PHP syntax CI | Complete | PHP 5.6 / 7.4 / 8.1 / 8.3 green |
| Documentation | Near complete | Release notes still required |

**RC1 should be cut after the functional install/upgrade matrix passes.**

---

# Phase 1 — Compatibility and runtime stabilization

Status: **Implementation complete / validation in progress**

Completed:

- Geeklog 2.1.1 through 2.2.2 compatibility layer.
- PHP 5.6 through 8.3 install compatibility gate.
- Syntax CI on PHP 5.6, 7.4, 8.1 and 8.3.
- `COM_createHTMLDocument()` compatibility rendering.
- Geeklog `userinfo` / `user_attributes` compatibility.
- removal of PHP constructs incompatible with PHP 8.
- central coordinate and numeric normalization.
- locale-independent JavaScript numeric serialization.
- central Google Maps JavaScript API URL/loading helpers.
- removal of retired `sensor`, Google Maps AdSense and Google Image Charts dependencies.
- replacement of historical MarkerClusterer with `@googlemaps/markerclusterer` 2.6.2.
- browser-side Google Maps API diagnostics in administration.
- SVG marker generation while retaining uploaded custom icons.

Remaining validation:

- map list and map detail;
- marker list, create, edit and delete;
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

# Phase 2 — Content interoperability

Status: **Complete for Maps 1.5.8**

Implemented:

- `plugin_getiteminfo_maps()` for individual maps;
- collection retrieval with `id='*'`;
- `since`, `limit` and `order` collection options;
- `modified-desc` and `created-desc` ordering;
- normalized `id`, `title`, `url`, `description`, `excerpt`, dates, owner/author and type information;
- `type = maps`, `subtype = map`;
- permission-aware public and authenticated retrieval;
- inactive/hidden filtering;
- `plugin_idtourl_maps()` canonical URL resolution;
- lifecycle events after successful map save/delete;
- dependency lifecycle propagation from markers, imports, overlays and icons to parent maps;
- centralized `updateMap()` lifecycle semantics.

Markers remain internal/non-first-class content in 1.5.8. Making markers first-class interoperable objects is deferred until a concrete consumer requires it.

Validation before RC1:

- direct `PLG_getItemInfo()` single-map retrieval;
- collection retrieval with filters;
- anonymous/private permission tests;
- one lifecycle notification per successful mutation;
- no lifecycle notification after failed/no-op mutations;
- canonical URL equality between Item Info and ID-to-URL resolution.

---

# Phase 3 — Native Geeklog integrations

Status: **Complete for 1.5.8 scope**

## What’s New

Maps retains native What’s New integration using permission-aware map retrieval.

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
- improved statistics cards on `/maps/`;
- per-map views, visible markers and aggregate marker views on map detail pages;
- configuration-aware public statistics visibility;
- permission-aware queries without N+1 retrieval.

## XML Sitemap

Implemented native Maps sitemap collection with canonical URL and modified timestamp, visibility/permission filtering and limit support.

## Related Items

Implemented using Geeklog's central topic assignments. Maps does not fabricate similarity when no topic assignment exists.

Exit criterion: validate each native integration on both supported Geeklog generations where the corresponding core capability exists.

---

# Phase 4 — Security hardening

Status: **Substantially complete**

Completed hardening includes:

- administrator rights checks on mutation endpoints;
- POST-only state-changing operations;
- Geeklog CSRF tokens on map, marker, overlay, overlay-group, icon and geolocation mutations;
- integer identifier normalization;
- SQL escaping/validation on modernized mutation paths;
- safe HTML output on reviewed administration paths;
- icon and overlay upload extension/MIME restrictions;
- generated upload filenames and basename-safe deletes;
- private CSV staging in Geeklog `path_data`;
- CSV field whitelist, MIME validation, coordinate validation and CSRF confirmation;
- prevention of GET-based map/marker/overlay/icon/geolocation mutations;
- AJAX overlay mutation CSRF and relationship validation;
- lifecycle notification only after real dependency changes.

Final RC regression tests:

- invalid/expired CSRF token;
- GET request against mutation routes;
- invalid IDs;
- unauthorized administrator access;
- invalid image extension/MIME;
- malformed CSV;
- stale AJAX overlay association requests.

---

# Phase 5 — Install and upgrade validation

Status: **Code prepared / functional validation required**

Completed:

- plugin version metadata set to 1.5.8;
- install compatibility aligned with Geeklog 2.1.1–2.2.2 and PHP 5.6–8.3;
- autoinstall table list aligned with the eight tables actually created by Maps;
- obsolete/nonexistent marker category/field/value table aliases removed from runtime metadata;
- MySQL installer `TEXT` columns made portable by removing historical explicit empty-string defaults;
- CSV imports now populate mandatory marker validity dates and remark under strict SQL modes;
- sequential 1.5.x configuration repair functions retained and designed to be idempotent;
- canonical `/maps/` public-folder migration retained through the normal Geeklog plugin upgrade.

Required matrix before RC1:

1. **Fresh install** — Geeklog 2.1.1 + supported PHP environment.
2. **Fresh install** — Geeklog 2.2.2 + PHP 8.1/8.3.
3. **Upgrade** — Maps 1.4.0 → 1.5.8 on Geeklog 2.1.1.
4. **Upgrade/migration** — representative existing Maps data on Geeklog 2.2.2.
5. Confirm all eight expected Maps tables and configuration rows.
6. Confirm `/maps/`, shared `images/maps/icons/` and `images/maps/overlays/` paths.
7. Confirm a second upgrade invocation is harmless/idempotent.
8. Confirm uninstall removes only Maps-owned tables/configuration and does not delete unrelated shared data unexpectedly.

Any failure in this phase is an RC1 blocker.

---

# Phase 6 — Packaging and RC1

Status: **Packaging automated / RC1 pending validation matrix**

The packaging workflow is intentionally reproducible:

- it does not modify plugin source code;
- it lints interoperability and security-critical files;
- it builds `maps-1.5.8-test.zip` from the branch contents;
- only the generated test archive is committed to `dist/`.

Before RC1:

- verify the ZIP contains a single top-level `maps/` directory;
- verify all required 1.5.8 files are present;
- verify no development workflows, build directories or temporary files are packaged;
- install the ZIP through Geeklog's normal plugin administration UI;
- complete the test matrix above;
- update release notes/changelog;
- rebuild the candidate archive from the exact RC commit.

After RC1, accept **bug fixes only** unless a new issue is a demonstrated compatibility, security or data-integrity blocker.

---

# Deferred beyond 1.5.8

The following items should not delay RC1:

- making markers first-class interoperable content;
- migration from `google.maps.Marker` to Advanced Markers;
- deeper data-layer abstraction or repository architecture;
- plugin-specific REST/service API without a concrete consumer;
- major visual redesign beyond the completed statistics/admin improvements;
- breaking schema changes;
- speculative 2.0 features.

The priority is now to **prove the release**, not expand its scope.