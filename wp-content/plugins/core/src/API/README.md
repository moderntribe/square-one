# API

API is a semi-independent API GET service based on [MeekroDB](https://meekro.com/) and [FastRoute](https://github.com/nikic/FastRoute), designed to provide very fast response times (< 50ms, often half that), based on work done by @speerface on the Tribe Sidecar plugin.

## Requirements

In order to field requests, the nginx/Apache rules need to have a directive for your api base. The following nginx block redirects request to `{URL}/api` to the router:

```nginxconfig
	location /so-api {
		set $tryfile /cache/$arg_foo.$arg_bar.cached.json;
		try_files $tryfile /wp-content/plugins/tribe-api/router.php$is_args$args;
	}
```

## How does this work?

The API module relies on a custom DB table, which stores the objects served up by the API. When a user saves a post, an _indexed_ representation of that post is stored in the table along with any relevant data (e.g., post meta, thumbnails, etc).

When a GET request comes in to the API,

## Components

### WP

WP contains the WordPress integration, which typically runs when you save or delete posts.

### Router

The PHP Router
