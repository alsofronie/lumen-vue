# Lumen and Vue

This project is to be used for consuming an JSON API produced by Lumen Endpoints, by a VueJS application.

For easier prototyping, the single HTML web page that boots the Vue application is also served by the Lumen framework, but this can be easily moved to an independent project.

The awesome [Bulma CSS Flex framework](https://bulma.io/) is a pretty straight-forward choice for this setup.

Any serious app uses **vue-router** for displaying various pages (components) and **axios** for consuming API, so you'll find them included.

A cool API will be language independent, so we modified the validation rule messages and we'll be processing them in the front-end.
For this to work, we are using the **vue-i18n** package, which greatly alleviates the I18N pains and integrates pretty easily.

Of course, even if it's not explicitly used in the scaffolding, the **moment** library is a must-have.

Everything is packed and ready to go with **laravel-mix**.

> **Warning**: this package is not intended for the absolute beginner.
> You need to have some basic understanding of Lumen, what is JSON, how
> Vue works, how a Promise work and some other things. **Also**,
> it is not production-ready yet, although trusting JWT encryption
> is a pretty safe bet, but there are no tests as of now.

## Features out of the box:

 - Authentication
 - Registration
 - Cors (not enabled by default)
 - JSON Error handler
 - Internationalization

## Lumen (API Backend)
Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

#### Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

#### Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

#### License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Vue (Front-End)

Vue (pronounced `/vjuː/`, like view) is a progressive framework for building user interfaces. It is designed from the ground up to be incrementally adoptable, and can easily scale between a library and a framework depending on different use cases. It consists of an approachable core library that focuses on the view layer only, and an ecosystem of supporting libraries that helps you tackle complexity in large Single-Page Applications.

#### Browser Compatibility

Vue.js supports all browsers that are ES5-compliant (IE8 and below are not supported).

#### Documentation

The official documentation is at [vuejs.org](https://vuejs.org/)
