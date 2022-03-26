[![Latest Stable Version](https://poser.pugx.org/chantouch/laravel-addresses/v/stable)](https://packagist.org/packages/chantouch/laravel-addresses)
[![Total Downloads](https://poser.pugx.org/chantouch/laravel-addresses/downloads)](https://packagist.org/packages/chantouch/laravel-addresses)
[![License](https://poser.pugx.org/chantouch/laravel-addresses/license)](https://packagist.org/packages/chantouch/laravel-addresses)

# Laravel Addresses

Simple address and contact management for Laravel with automatically geocoding to add longitude and latitude.

## Installation

Require the package from your `composer.json` file

```json
"require": {
	"chantouch/laravel-addresses": "^1.0"
}
```

and run `$ composer update` or both in one with `$ composer require chantouch/laravel-addresses`.

## Configuration & Migration

```bash
$ php artisan vendor:publish --provider="Chantouch\Addresses\AddressesServiceProvider"
```

This will publish a `config/laravel-address.php` and some migration files, that you'll have to run:

```bash
$ php artisan migrate
```

For migrations to be properly published ensure that you have added the directory `database/migrations` to the classmap in your projects `composer.json`.

## Usage

First, add our `HasAddresses` trait to your model.
        
```php
<?php 
namespace App\Models;

use Chantouch\Addresses\Traits\HasAddresses;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasAddresses;

    // ...
}
?>
```

##### Add an Address to a Model
```php
$post = Post::find(1);
$post->addAddress([
    'street'     => '123 Example Drive',
    'city'       => 'Vienna',
    'post_code'  => '1110',
    'country'    => 'AT', // ISO-3166-2 or ISO-3166-3 country code
    'is_primary' => true, // optional flag
]);
```

Alternativly you could do...

```php
$address = [
    'street'     => '123 Example Drive',
    'city'       => 'Vienna',
    'post_code'  => '1110',
    'country'    => 'AT', // ISO-3166-2 or ISO-3166-3 country code
    'is_primary' => true, // optional flag
];
$post->addAddress($address);
```

Available attributes are `street`, `street_extra`, `city`, `post_code`, `state`, `country`, `state`, `notes` (for internal use). You can also use custom flags like `is_primary`, `is_billing` & `is_shipping`. Optionally you could also pass `lng` and `lat`, in case you deactivated the included geocoding functionality and want to add them yourself.

##### Check if Model has Addresses
```php
if ($post->hasAddresses()) {
    // Do something
}
```

##### Get all Addresses for a Model
```php
$addresses = $post->addresses()->get();
```

##### Get primary/billing/shipping Addresses
```php
$address = $post->getPrimaryAddress();
$address = $post->getBillingAddress();
$address = $post->getShippingAddress();
```

##### Update an Address for a Model
```php
$address = $post->addresses()->first(); // fetch the address

$post->updateAddress($address, $new_attributes);
```

##### Delete an Address from a Model
```php
$address = $post->addresses()->first(); // fetch the address

$post->deleteAddress($address); // delete by passing it as argument
```

##### Delete all Addresses from a Model
```php
$post->flushAddresses();
```

## Contacts

First, add our `HasContacts` trait to your model.

```php
<?php 
namespace App\Models;

use Chantouch\Addresses\Traits\HasContacts;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasContacts;

    // ...
}
?>
```

##### Add a Contact to a Model
```php
$post = Team::find(1);
$post->addContact([
    'first_name' => 'Alex',
    'website'    => 'https://twitter.com/AMPoellmann',
    'is_primary' => true, // optional flag
]);
```

## Relate Addresses with Contacts

Above all, `addresses` and `contacts` can be connected with an optional One To Many relationship. Like so you could assign multiple contacts to an address and retrieve them like so:

```php
use Chantouch\Addresses\Models\Address;

$address = Address::find(1);
$contacts = $address->contacts;

foreach ($contacts as $contact) {
    //
}
```

```php
use Chantouch\Addresses\Models\Address;

$contact = Address::find(1)
                  ->contacts()
                  ->first();
```

```php
use Chantouch\Addresses\Models\Contact;

$contact = Contact::find(1);

return $contact->address->getHtml();
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).
