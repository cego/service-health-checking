# Service Health Checking
This package contains core functionality for HTTP health checking of Laravel services.

## Usage
When the package is installed, a health endpoint, `/vendor/service-health-checking` is exposed. The endpoint
returns `200 OK` and a body with a JSON data object with the following format:
```json
{
    "status": "pass|warn|fail",
    "checks": [
        {
            "status": "pass|warn|fail",
            "name": "HealthCheckClassName",
            "description": "Description defined in the health check class",
            "message": "Message set in the HealthStatus object"
        }
    ]
}
```
The `checks` array contains an entry for each registered health check.

## Creating health checks
To create a health check for your service, simply create a class that extends
`\Cego\ServiceHealthChecking\BaseHealthCheck`. The base method has 2 abstract methods:
1. `check(): HealthStatus` should perform the check and return a `HealthStatus` object.
2. `getDescription(): string` should return a description of the health check.

## Registering health checks
Firstly, publish the package assets by running:
```
php artisan vendor:publish --provider="Cego\ServiceHealthChecking\ServiceHealthCheckingServiceProvider"
```
The package will publish a config file, `service-health-checking.php`, in which health check classes must be 
registered, in order for them to run. Services that do not publish the config run the default registry listed below.
A published config replaces the default `registry` entirely, so changes to the package defaults never reach a service
that has published the config.

A registry entry that cannot be resolved (e.g. a class that has been removed) is reported as a failing check instead
of breaking the endpoint. The service still reports `fail`, so uptime monitoring will flag it as down until the entry
is removed.

## Bundled health checks
| Check | Default registry | Purpose |
|---|---|---|
| `DefaultDatabaseConnectionCheck` | registered | Verifies that a connection to the default database can be established |
| `CacheCheck` | opt-in | Writes and reads back a random cache key. Redundant when the cache driver is `database`, so register it only when the cache is a separate system (e.g. Redis) |

## Upgrading to 2.0
- `ActiveRequestInsurancesCheck`, `FailedRequestInsurancesCheck` and `ServiceHealthConfigCheck` have been removed,
  along with the `request-insurance` config section. Request insurance backlogs are monitored through Prometheus
  instead, and the config check had nothing left to detect. Remove the three classes from your published
  `config/service-health-checking.php` in the same change as the version bump: the endpoint stays up, but each stale
  entry is reported as a failing check, which marks the service down in uptime monitoring.
- `CacheCheck` is no longer registered by default. This only affects services that have not published the config;
  a published registry keeps running whatever it lists. Register `CacheCheck` explicitly if the service relies on
  a cache that is not backed by the default database.
