<p align="center">

![Bivouac's Logo](https://user-images.githubusercontent.com/61099/278423139-b8f05516-f8e5-40f1-9e88-5d039947fca7.png)

</p>

## Bivouac is an open source project management software.

Bivouac has the following features:

- messages
- tasks
- project updates
- files
- 1:1s

The project is released as MIT license. You can host it yourself, or you can use our [own hosted version](https://bivouacfoundation.org).

This is what the product looks like:

![image](https://github.com/djaiss/bivouac/assets/61099/b706e3ea-0a94-4302-a12f-b6d6463d9840)

## Motivation

The project management space is crowded with commercial offerings. We believe that we can offer a nice alternative for companies who value complete privacy and data ownership.

Also, we wanted to build something simple, solid and fast. We believe that we have achieved that.

## Design goals

Bivouac has been built with those goals in mind:

- The least amount of dependencies possible,
- It should be dead easy to maintain,
- It should be simple to use,
- It should be fast,
- It should be well tested,
- It should stick with Laravel's default architecture as much as possible.

## Requirements to host the software

Bivouac is made with Laravel and VueJS. Since it's basically PHP, you can run it on any platform.

To know precisely what is needed to run a Laravel project, follow the [official Laravel documentation](https://laravel.com/docs/10.x/deployment#server-requirements).

You also need to have [GD (the PHP image manipulation library)](https://www.php.net/manual/en/image.installation.php) installed.

## Basic installation instructions

You need PHP 8.1+, Composer, NodeJS and yarn and bun installed on your machine.

1. Clone the repository.
2. Run `composer install`.
3. Run `bun install`
4. Edit the `.env` file to match your environment, by renaming the `.env.example` to `.env`.
5. Compile the assets with `yarn build`.
6. Setup your server so it serves the PHP application.

## License

Copyright © 2023

Licensed under [the MIT License](/LICENSE.md).
