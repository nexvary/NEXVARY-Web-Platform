# Threat data provenance

The homepage retrieves CISA's Known Exploited Vulnerabilities catalog through
`/api/security-intelligence`. The upstream is the official `cisagov/kev-data`
JSON mirror, licensed CC0. The server caches a normalized snapshot for six hours
and retains it for up to one day if the upstream is temporarily unavailable.
The interface shows the source and catalog release date. The catalog is evidence
of exploited vulnerabilities, **not** a stream of attacks against NEXVARY or
the visitor, and contains no attack coordinates.

The world map uses Natural Earth geography. Without a configured geolocated
provider it shows no event nodes or paths. To connect an authorized provider,
set `NEXVARY_THREAT_FEED_URL` to its HTTPS endpoint and optionally set
`NEXVARY_THREAT_FEED_TOKEN` on the server. Never put the token into public JS
or a deployment archive. The provider JSON must contain `source`, optional
`observed_at`, and `events` with `lat`, `lon`, `label`, optional `city`, `id`,
`type` (`attack`, `scan`, `infrastructure`) and `severity` (`high`, `medium`,
`low`). These must be actual observations with coordinates and permission to
publish. The server validates, bounds, and caches at most 40 observations for
five minutes. It never infers routes between unrelated reports.

The CISA feed requires no API key. Any commercial geospatial threat provider
needs its own account, license and credentials before its observations can
appear on the map. If the CISA source or geo provider fails, the UI labels the
source unavailable rather than displaying invented data.
