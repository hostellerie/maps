# Maps for Geeklog — Roadmap

Last updated: September 22, 2026

## Current release target: Maps 1.7.0

Maps 1.7.0 is the interoperability and service-integration release built on the stabilized 1.6 line.

Compatibility target:

- Geeklog 2.1.1 through 2.2.2;
- PHP 5.6 through 8.3;
- MySQL/MariaDB versions supported by the corresponding Geeklog release;
- Google Maps Platform current behavior in 2026;
- safe operation in mono-site and shared-files/multisite deployments.

## 1.7.0 release scope

### Shared plugin capability contract — complete

Maps declares one provider-neutral capability catalogue for Agent, Eclipse, Hub and future consumers:

- normalized content read/collection/search;
- canonical URL resolution;
- lifecycle and syndication;
- dashboard summary;
- map and marker read capabilities;
- marker list and render services;
- nearby-marker geographic lookup;
- marker create/update and validity actions.

No Agent-specific, Eclipse-specific or Hub-specific registry is introduced.

### Agent integration — complete for 1.7.0

Agent can discover the Maps capabilities and consume:

- normalized maps;
- normalized `marker:<mkid>` resources;
- canonical URLs;
- collections and search;
- `maps.geo.nearby`;
- existing marker services where authorized.

Machine/client protocol adaptation remains Agent/Connector responsibility.

### Eclipse integration — complete for 1.7.0

Maps exposes `dashboard.summary` through `PLG_invokeService()`.

The summary provides bounded administration data:

- map count;
- marker count;
- pending marker submissions;
- markers expiring within 30 days;
- actionable alerts;
- Maps administration link.

Eclipse can render this generically without direct SQL against Maps tables.

### Hub integration — complete for 1.7.0

Hub can discover the same capability declaration and consume the same normalized resources/lifecycle events as Agent.

Maps remains authoritative for content and geospatial data. Hub remains authoritative for cross-plugin relationships and contextual graph data.

### Static metadata — complete

`plugin.json` follows the memorandum metadata manifest and exposes:

- plugin id/name;
- administrative icon;
- minimum Geeklog version;
- minimum PHP version.

### Marker service API — complete for current scope

Available services include:

- `marker_list`;
- `marker_get`;
- `marker_render`;
- `marker_save`;
- `marker_set_validity`;
- `marker_extend_validity`;
- `geo_nearby`;
- `dashboard_summary`.

Mutation services remain internal/trusted service surfaces and retain idempotent operation support where applicable.

## Release gates

Before tagging 1.7.0:

- [ ] PHP lint green on 5.6, 7.4, 8.1 and 8.3.
- [ ] Contract/regression tests green.
- [ ] Fresh install on Geeklog 2.1.1 / PHP 5.6-compatible stack.
- [ ] Fresh install on Geeklog 2.2.2 / PHP 8.1 or 8.3.
- [ ] Upgrade an existing Maps 1.6.0 installation to 1.7.0.
- [ ] Validate map and marker CRUD.
- [ ] Validate marker render/list/get services.
- [ ] Validate `dashboard_summary` in Eclipse.
- [ ] Validate `geo_nearby` with accessible/inaccessible markers.
- [ ] Validate Item Info map and `marker:<mkid>` retrieval.
- [ ] Validate lifecycle events with a consumer such as IndexNow/Hub.
- [ ] Verify the generated ZIP contains one top-level `maps/` directory and `plugin.json`.
- [ ] Install the generated ZIP through Geeklog Plugin Administration.

## After 1.7.0

### Interoperability

- richer JSON-Schema-compatible capability descriptors for machine adapters;
- optional bounded map-specific service beyond Item Info if a concrete consumer requires it;
- richer Hub relations between maps/markers and Documents, Videos, Store or other content providers;
- explicit action authorization descriptors if Geeklog standardizes caller identity/scopes for internal services.

### Google Maps platform

- migrate from legacy `google.maps.Marker` to Advanced Markers when the compatibility baseline allows it;
- migrate fully to `importLibrary()` and asynchronous loading after legacy rendering constraints are removed;
- review clustering and Map ID behavior against future Google Maps Platform changes.

### Public/SEO

- optional human-readable map/marker slugs;
- richer marker Schema.org types;
- `CollectionPage`/`ItemList` structured data;
- performance-oriented lazy initialization for large map pages.

### Architecture

After historical sites have migrated to Geeklog 2.2.2 + PHP 8.1+, future major Maps development can drop the PHP 5.6/Geeklog 2.1.1 transition baseline and simplify compatibility code.
