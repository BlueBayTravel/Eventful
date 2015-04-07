# Eventful

- Composer friendly.
- Only requires cURL.

## Getting an API Key
Before you can use the PHP class, you're required to register for an API key from Eventful.com - see [api.eventful.com](http://api.eventful.com)

## Documentation
This class includes only two functions which are required to request and process information from Eventful.

To get started:

```php
$ev = new Eventful($APP_KEY);
```

Then you need to login:

```php
$evLogin = $ev->login($USERNAME, $PASSWORD);
```

The `login` method simply calls the API request `users/login` but handles all of the `nonce` and `response` for you.

You can boolean check $evLogin to see if the login has been successful.

Now that you're logged in, you can use the `call` method to make API requests!

```php
$aArgs = [
    'location' => 'Mexico'
];

$evEvent = $ev->call('events/search', $aArgs);

var_dump($evEvent);
```

# License
[MIT](/LICENSE)
