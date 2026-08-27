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

## Authorization

The endpoints run the middleware stack from `config('service-health-checking.middleware')`,
which is empty by default — they are polled by monitoring, not a browser, so they carry no
session and no authorization gate.

To restrict who may poll them, publish the config (see below) and add middleware:

```php
'middleware' => ['can:poll-health'],
```

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
registered, in order for them to run. A published config replaces the default registry entirely. A registry entry
that cannot be resolved is reported as a failing check.

## Bundled health checks
| Check | Default registry | Purpose |
|---|---|---|
| `DefaultDatabaseConnectionCheck` | registered | Checks that a connection to the default database can be established |
| `CacheCheck` | opt-in | Checks read/write access to the cache |

## Upgrading to 2.0
- `ActiveRequestInsurancesCheck`, `FailedRequestInsurancesCheck` and `ServiceHealthConfigCheck` have been removed.
  Remove them, and the `request-insurance` section, from your published `config/service-health-checking.php`.
- `CacheCheck` is no longer registered by default. Add it to your registry if you want to keep running it.
