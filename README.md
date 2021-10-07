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
registered, in order for them to run. The package is shipped with a basic database connection check, which is registered 
by default.
