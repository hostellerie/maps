# Maps 1.5.8 custom fields audit

`item_1` through `item_10` remain supported marker custom fields. They are stored in `maps_markers` and `maps_submission`, included in moderation, and exposed in the admin/user marker edit forms when their configured label is non-empty.

## Public rendering

Maps 1.5.8 renders configured custom fields publicly in both places where marker content is presented:

- the marker detail page;
- the Google Maps info window.

A custom field is rendered only when **both** its configured label and its stored marker value are non-empty. The rendering is centralized in `MAPS_renderPublicCustomFields()` so the detail page and info window apply the same visibility rule and escaping policy. Values continue to support Geeklog autotags through `PLG_replaceTags()`.

The obsolete `infos_label` option is not used for this rendering and is removed in Maps 1.5.8.
