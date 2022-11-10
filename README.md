# Information
This package makes it possible to create service accounts and assign permissions to these.
The package creates a couple of routes which you can use for creating/modifing service accounts.

## Authentication
All routes require authentication. There are two ways of authenticating.
    1. Using the api_token specified in service-accounts config file. (this has access to all routes)
    2. By using a service account's token with the right permission.

Both types of tokens should be sent in the Authorization header, 
    Format for service account's token "Bearer TOKEN"
    Format for api_token "ServiceAccount TOKEN"

# Routes
```
POST /service_accounts
    Body:
        name
        permissions
            Comma separated list of permissions in format something.something
    Creates a new Service account
    Requeres authentication with Service Account api_token
    Returns service account token
```

# Install

## 1.
Run composer command to download package. More information comming...

## 2.
### Add following to `config/auth.php` config:
Under `guards` add:
```php
'svc' => [
	'driver' => 'sanctum',
	'provider' => 'svc',
    'hash' => true,
],
```

Under `providers` add:
```php
'svc' => [
	'driver' => 'eloquent',
	'model' => Openwod\ServiceAccounts\Models\ServiceAccount::class,
],
```

## 3. 
Add `SERVICE_ACCOUNT_ADMIN_TOKEN` to env. This token is used to authenticate requests to add new service accounts.

## 4.
Run `php artisan vendor:publish --provider="Openwod\ServiceAccounts\ServiceAccountsServiceProvider"` to publish files 

## 5.
Run `php artisan migrate`


# Usage

## Get service account
```php
$svc = auth()->guard('svc')->user();
```

## Check permission
```php
$svc->tokenCan('permission')
```
