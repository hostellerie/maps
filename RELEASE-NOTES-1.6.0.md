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
- the dedicated marker map template avoids introducing a second H1 on the canonical marker page.
