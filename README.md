FireAndForget
===============

A simple PHP library to just fire off an HTTP request and forget about it

## Installation

To get started, require this package in your composer.json and run `composer update`:

```json
"aura-is-here/fire-and-forget": "0.1.*"
```

### If you're using Laravel

After updating composer, add the ServiceProvider to the providers array in `app/config/app.php`:

```php
'AuraIsHere\FireAndForget\FireAndForgetServiceProvider',
```

You'll probably want to set up the alias:

```php
'FireAndForget' => 'AuraIsHere\FireAndForget\Facades\FireAndForgetFacade'
```

You could also publish the config file:

```bash
php artisan config:publish aura-is-here/fire-and-forget
```

## Usage

Just call one of the  methods (`get`, `post`, `put`, `delete`) and get on with your life. `FireAndForget` will compile the request, open a socket, fire the request and immediately close and return.

*Note* All the methods have the same signature.

```php
// You can define a connection timeout, the default is 5
$connectionTimeout = 5;

$faf = new FireAndForget($connectionTimeout);
$faf->post($url, $params);
```

Or, if you're using Laravel,

```php
FireAndForget::post($url, $params);
```
