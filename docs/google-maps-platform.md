# Google Maps Platform usage in Maps 1.7.0

Maps 1.7.0 keeps its Google Maps Platform footprint deliberately small.

## APIs used

### Maps JavaScript API

Used in the visitor's browser for:

- interactive maps;
- marker rendering;
- map controls;
- marker info windows;
- route rendering through the existing JavaScript directions service.

Configure `google_api_key` as the browser key and restrict it by HTTP referrer/domain.

### Geocoding API

Used by PHP/server-side code when Maps converts an address to latitude/longitude.

Maps 1.7.0 requires the dedicated `google_server_api_key` for these server requests. The browser key is never used as a fallback.

Recommended restriction:

- server/IP restriction appropriate to the hosting environment;
- allow only the Geocoding API.

### Directions API

The current marker route planner continues to use the Google Maps JavaScript directions service. The starting point may be entered manually or populated with the browser's standard `navigator.geolocation` API.

Browser geolocation is not a Google API and normally requires HTTPS plus explicit user permission.

## APIs not required by Maps 1.7.0

Maps does not require Street View, Roads, Elevation, Maps Static or Places for its maintained 1.7.0 feature set.

## API keys

Use separate keys:

- `google_api_key`: browser-side Maps JavaScript and route display;
- `google_server_api_key`: server-side Geocoding only.

The administration dashboard reports whether the browser key, server key and optional Map ID are configured without displaying the secret values.

## Geocoding endpoint

`url_geocode` remains configurable for compatibility and is now used consistently by the server URL builder. If it is empty, Maps uses Google's standard Geocoding endpoint.

## Routes API evaluation

Maps 1.7.0 deliberately keeps the established `google.maps.DirectionsService` implementation to avoid changing the routing architecture during the compatibility release.

For a future release, evaluate Routes API separately, including:

- browser versus server architecture;
- authentication and key restrictions;
- feature parity with the existing route panel;
- billing implications;
- compatibility with the maintained Geeklog/PHP baseline;
- transition strategy if both routing implementations need to coexist.

This evaluation is not a blocker for Maps 1.7.0 while the current route planner remains functional.

## Places API evaluation

Places remains optional future work. A future marker editor may use it to search named places and fill address/coordinates, but Maps 1.7.0 does not make Places a dependency.
