# Maps 1.6.0 release notes

Maps 1.6.0 is the stable release of the Maps modernization line validated on Geeklog 2.1.1 through 2.2.2 and PHP 5.6 through 8.3.

## Highlights

- modern Google Maps Platform loading, clustering, geocoding and marker rendering;
- PHP 5.6–8.3 and Geeklog 2.1.1–2.2.2 compatibility layer;
- hardened administration mutations, CSRF protection, uploads and CSV import;
- public/admin statistics and native Geeklog integrations;
- first-class interoperable maps and markers with canonical Item Info and ID-to-URL resolution;
- marker service API for trusted inter-plugin consumers such as Documents and Store;
- generic `PLG_itemSaved` / `PLG_itemDeleted` lifecycle notifications suitable for IndexNow, Hub and Hello;
- SEO canonicalization, 301 migration of legacy marker URLs, meta descriptions, Open Graph/Twitter metadata, Schema.org `Place`/`GeoCoordinates`, sitemap marker entries and dedicated `/maps/` SEO configuration;
- upgrade migrations retained from the 1.5.x modernization series, including 1.5.9 services and 1.5.10 landing-page SEO settings.

## Compatibility

- Geeklog: 2.1.1 through 2.2.2
- PHP: 5.6 through 8.3
- Database: MySQL/MariaDB versions supported by the corresponding Geeklog release

## Upgrade

Back up the database and shared `images/maps/` resources, copy the Maps 1.6.0 files, then run Geeklog's normal plugin upgrade. Existing 1.5.x migrations remain sequential and idempotent.

## Final public-page refinements

- the `/maps/` statistics block is rendered after the map list so primary navigational content appears first;
- canonical marker pages again display an individual map centered on the marker;
- marker pages restore the driving-directions form and route panel using the modern Google Maps Directions API already maintained by Maps;
- the dedicated marker map template avoids introducing a second H1 on the canonical marker page;
- the site-members map now uses the same initial center as the global map: the coordinates of the first active map ordered by `mid`, while retaining the global zoom/type/size settings.

## Public layout polish

- canonical marker pages now keep a single H1 and place the individual map directly below it;
- route controls are displayed below the map in a responsive panel with explicit Starting point / Point de départ labels and placeholder text;
- marker information uses a responsive card layout instead of the legacy 2010-era presentation;
- public map pages use a cleaner map card, content hierarchy, metadata area and responsive spacing;
- map listings use modern cards with separated title, description, metadata and actions.

## Administration and content hierarchy polish

- Added an H2 introducing marker lists on individual public map pages.
- Standardized the Maps administration menu and top-level H1 placement.
- Reordered the administration dashboard to maps list, statistics, then Google Maps API status.
- Added missing page titles to import/export and the main map/marker editors.
- Modernized the geocoder form while preserving its menu/title hierarchy.

## Marker footer and user-link hardening

- Reworked marker metadata into consistent labelled items and actions.
- Removed the duplicate parent-map link beneath canonical marker pages.
- Replaced the legacy lowercase `from` label with localized `Added by` / `Ajouté par`.
- User-entered website and custom-field values are rendered as non-clickable escaped text.
- Marker info windows always link to the canonical Maps marker page instead of a user-provided URL.
- User-entered websites are no longer emitted as schema.org `sameAs`.
