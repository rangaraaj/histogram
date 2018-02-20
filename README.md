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

## Run the application
Navigate to the web folder and access the following URL

### /hello/world
Returns a text, "Hello world"

### /histogram/variety
Returns a JSON string with the tweets per hour during the day
{"00":3,"01":2,"02":4,"03":3,"04":1,"05":2,"06":14,"07":2,"08":3,"09":2,"10":4,"12":1,"13":1,"14":2,"15":3,"16":4,"17":6,"18":7,"19":4,"20":4,"21":6,"22":2,"23":5}

## Tests
To run the unit tests, PHP >5.6 is required. Simple go the project path
and run `vendor/bin/phpunit`