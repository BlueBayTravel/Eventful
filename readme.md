# Eventful API PHP Class
I developed this class when I found that the current Eventful PHP class requires PEAR packages and other extra includes that were bulky and in my ideal world - a waste of space.

So I started developing a prerequisite free class. **At least, only using CURL, which is installed on many servers by default anyway!**

## Functions / Documentation
This class includes only two functions which are required to request and process information from Eventful.

When invoking the class, call the API with the __construct method.

```php
$ev = new Eventful($APP_KEY);
```

Then, you need to login, so call the login method!

```php
$evLogin = $ev->login($USERNAME, $PASSWORD);
```

The `login` method simply calls the API request `users/login` but handles all of the `nonce` and `response` for you.

You can boolean check $evLogin to see if the login has been successful.

Now you're logged in, you can use the `call` method to make API requests!

```php
$aArgs = array(
    'location' => 'Mexico'
);
$evEvent = $ev->call('events/search', $aArgs);

echo print_r($evEvent, true);
```

## Getting an API Key
Before you can use the PHP class, you're required to register for an API key from Eventful.com - see [api.eventful.com](http://api.eventful.com)

# License
MIT - [http://jbrooksuk.mit-license.org](http://jbrooksuk.mit-license.org)
