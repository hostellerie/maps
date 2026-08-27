# Maps 1.5.8 custom fields audit

`item_1` through `item_10` remain supported marker fields. They are stored in `maps_markers` and `maps_submission`, included in moderation, and exposed in the admin/user marker edit forms when their configured label is non-empty.

The public marker rendering does not currently render these values as a dedicated public custom-fields block. The only `public_html` references are in `markers.php`, where they support the user edit form and save path. This is existing behavior and is intentionally not changed by the 1.5.8 configuration-label cleanup.

Files in `public_html` containing item references during the audit: public_html/markers.php.
