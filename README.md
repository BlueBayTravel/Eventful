# Eventful [![StyleCI](https://styleci.io/repos/2307902/shield)](https://styleci.io/repos/2307902)

## API Key
You'll need to register for an API key from Eventful.com - see [api.eventful.com](http://api.eventful.com).

## Documentation
This class includes only two functions which are required to request and process information from Eventful.

To get started:

```php
use BlueBayTravel\Eventful;

$ev = new Eventful(API_KEY);
```

Then you need to login:

```php
$evLogin = $ev->login(API_USERNAME, API_PASSWORD);
```

The `login` method simply calls the API request `users/login` but handles all of the `nonce` and `response` for you.

You can boolean check `$evLogin` to see if the login has been successful.

Now that you're logged in, you can use the `call` method to make API requests!

```php
$locationSearch = [
    'location' => 'Mexico'
];

$evEvent = $ev->call('events/search' ,$locationSearch);

var_dump($evEvent);
```

# License
[MIT](/LICENSE)
