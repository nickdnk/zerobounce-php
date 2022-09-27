# ZeroBounce PHP Library

[![example workflow](https://github.com/nickdnk/zerobounce-php/actions/workflows/test.yml/badge.svg)](https://github.com/nickdnk/zerobounce-php/actions/workflows/test.yml)

This library will allow you to integrate the **ZeroBounce** API in your project using modern PHP7 and an OOP structure.

### Prerequisites

You need an active account at https://zerobounce.net to use this library. From there, grab your API Key under **API - Keys &amp; Info**. 


### Installation

To include this in your project, install it using Composer.

As we use return types and type hints, this library requires PHP 7.1 and works on all versions up until PHP 8.1

`composer require nickdnk/zerobounce-php`

### Tests

Enter your API key into the `ZeroBounceTest` file and run it. This
uses the test-emails supplied by ZeroBounce and will not cost you credit.

### How to use

```php
use nickdnk\ZeroBounce\Email;
use nickdnk\ZeroBounce\Result;
use nickdnk\ZeroBounce\ZeroBounce;

// You can modify the timeout using the second parameter. Default is 15.
// You an also pass proxy options to Guzzle using the third parameter.
// See https://docs.guzzlephp.org/en/stable/request-options.html#proxy for details.
$handler = new ZeroBounce('my_api_key', 30, ['https' => 'https://my-proxy-server']);

$email = new Email(
    
    // The email address you want to check
    'some-email@domain.com',
    
    // and if you have it, the IP address - otherwise null or omitted
    '123.123.123.123'

);

try {

    // Validate the email
    $result = $handler->validateEmail($email);
    
    if ($result->getStatus() === Result::STATUS_VALID) {
        
        // All good
        
        if ($result->isFreeEmail()) {
            
            // Email address is free, such as @gmail.com, @hotmail.com.
            
        }
        
        /**
        * The user object contains metadata about the email address
        * supplied by ZeroBounce. All of these may be null or empty
        * strings, so remember to check for that. 
        */
        $user = $result->getUser();
        
        $user->getCountry();
        $user->getRegion();
        $user->getZipCode();
        $user->getCity();
        $user->getGender();
        $user->getFirstName();
        $user->getLastName();
        
    } else if ($result->getStatus() === Result::STATUS_DO_NOT_MAIL) {
        
        // The substatus code will help you determine the exact issue:
        
        switch ($result->getSubStatus()) {
            
            case Result::SUBSTATUS_DISPOSABLE:
            case Result::SUBSTATUS_TOXIC:
                // Toxic or disposable.
                break;
                
                
            case Result::SUBSTATUS_ROLE_BASED:
                // admin@, helpdesk@, info@ etc; not a personal email
                break;
            
            // ... and so on.
                
        }
        
    } else if ($result->getStatus() === Result::STATUS_INVALID) {
        
        // Invalid email.
        
    } else if ($result->getStatus() === Result::STATUS_SPAMTRAP) {
        
        // Spam-trap.
        
    } else if ($result->getStatus() === Result::STATUS_ABUSE) {
        
        // Abuse.
        
    } else if ($result->getStatus() === Result::STATUS_CATCH_ALL) {
        
        // Address is catch-all; not necessarily a private email.
        
    } else if ($result->getStatus() === Result::STATUS_UNKNOWN) {
        
        // Unknown email status.
       
    }
    
    /*
     * To find out how to use and react to different status and
     * substatus codes, see the ZeroBounce documentation at:
     * https://www.zerobounce.net/docs/?swift#version-2-v2
     */

} catch (\nickdnk\ZeroBounce\HttpException $exception) {

   // ZeroBounce returned an error of some kind. Message is best-effort parsing.
   $exception->getMessage();
   // The response is available here also.
   // The HTTP code is 200 for 400-range problems, such as invalid credentials,
   // so don't rely too much on that. It is included mostly for debugging purposes.
   $exception->getResponse(); 

} catch (\nickdnk\ZeroBounce\ConnectionException $exception) {

   // There was a problem connecting to ZeroBounce or the connection timed out waiting for a reply.
   $exception->getMessage(); // will always return generic "request timed out or failed" message

} catch (\nickdnk\ZeroBounce\APIError $exception) {

   // Base exception. Both ServerException and ConnectionException extend from this, so you can
   // catch this to handle both errors. The somewhat odd naming for this was to avoid
   // introducing breaking changes when ServerException and ConnectionException was added.
   $exception->getMessage();

}
```
