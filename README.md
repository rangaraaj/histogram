# Twitter Histogram

## Question

Using an appropriate micro-framework as a foundation (Silex, Sinatra),
connect to Twitter’s API and return a JSON-encoded array containing hour ->
tweet counts for a given user, to determine what hour of the day they are most
active. The application should consist of at least 3 endpoints:

* / -> will respond with Try /hello/:name as text
* /hello/BarackObama -> will respond with Hello BarackObama as text
* /histogram/Ferrari -> will respond with a JSON structure displaying the
number of tweets per hour of the day

The app will be reviewed based on the appropriate use of OO and SOLID
principles.

## Installation

### Requirements
PHP 5.6

Apache

### Download the Project
`git clone https://github.com/rangaraaj/histogram`

### Composer Update
If you haven't got the composer, please follow the link to get composer. https://getcomposer.org/download/
Once downloaded, run the following command to install the required packages using composer.

`php composer.phar update`

### Point apache to the web folder
Place the project in the folder accessed by apache

### Prepare the application
Copy config.sample.php to config.php

`cp config.sample.php config.php`

## Background
