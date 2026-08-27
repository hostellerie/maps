# Maps 1.5.10 release notes

## Landing-page SEO configuration

Maps 1.5.10 adds three dedicated SEO settings for `/maps/` without changing map or marker SEO automation:

- `maps_page_title` controls the landing-page document title, Open Graph title and Twitter title;
- `maps_page_h1` controls the visible H1 independently from the menu label and document title;
- `maps_meta_description` controls the landing-page meta/social description.

Fallbacks remain safe and automatic when fields are empty: the plugin label is used for the title, the SEO title for the H1, and the introductory Maps content for the description. Canonical URLs remain automatic and are not administrator-editable.

The existing `map_main_header` key is retained for compatibility but is labeled more clearly in administration as introductory Maps-page content.

## Generic content lifecycle notifications

- marker create/update now emits `PLG_itemSaved('marker:<mkid>', 'maps')`;
- marker deletion emits `PLG_itemDeleted('marker:<mkid>', 'maps')`;
- the parent map is also refreshed through its existing `PLG_itemSaved(<mid>, 'maps')` lifecycle;
- moving a marker between maps refreshes both the old and new parent maps;
- CSV imports emit one marker lifecycle event per inserted marker and one parent-map event per batch;
- `plugin_idtourl_maps()` resolves both map IDs and namespaced `marker:<mkid>` IDs, including after deletion;
- `plugin_getiteminfo_maps()` exposes public marker metadata for namespaced marker IDs;
- marker services now return the canonical `/maps/index.php?mode=marker&mkid=...` URL.

This keeps Maps independent from IndexNow: IndexNow, Hub, Hello and other listeners can consume native Geeklog lifecycle events and resolve canonical Maps URLs through the interoperability callbacks.

