# EasyMailjet

[![Build Status](https://travis-ci.com/Nevermille/EasyMailjet.svg?branch=master)](https://travis-ci.com/Nevermille/EasyMailjet) [![BCH compliance](https://bettercodehub.com/edge/badge/Nevermille/EasyMailjet?branch=master)](https://bettercodehub.com/)

## Overview

A simple PHP class for sending emails through Mailjet provider

## Compatibility

This library has been tested for PHP 7.3 and higher

## Installation

Just use composer in your project:

```
composer require lianhua/easy-mailjet
```

If you don't use composer, clone or download this repository, all you need is inside the src directory. You'll need [Lianhua Email](https://github.com/Nevermille/Email) and [Mailjet API PHP Wrapper](https://github.com/mailjet/mailjet-apiv3-php)

## Usage
### Mailjet object

In order to create maijet object, you'll need your api keys and give them to the constructor.

```php
$mj = new EasyMailjet("Your key", "Your secret");
```

### Sending an email

Create an object of type [Lianhua Email](https://github.com/Nevermille/Email) and give it to the function sendMail.

```php
$mj->sendMail($email);
```

You'll get in return a boolean indicating if the email had been sent successfully or not.

### Get mailjet detailed response

If you give a var as second parameter, you'll be able to get the response from mailjet (read [Mailjet Documentation](https://dev.mailjet.com/email/guides/) for further explanations)

```php
$mj->sendMail($email, $res);
```

### Send with custom ids

You can give as third and fourth parameters a custom id for the campaign and the message.

```php
$mj->sendMail($email, $res, "campaign_id", "message_id");
```

### Sandbox mode

You can disable the email delivering while getting a feedback from mailjet.

```php
$mj->setSandbox(true);
```

### Deduplicate mode

You can disable duplicate contacts in a campaign.

```php
$mj->setDeduplicate(true);
```
