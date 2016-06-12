# mkosolofski/houseseats-monitor
A Symfony 3, PHP 7 bundle for monitoring active shows on "http://www.houseseats.com". A command is available to run that will send email(s) of shows/descriptions when new shows are detected.

[![Build Status](https://travis-ci.org/mkosolofski/houseseats-monitor.svg?branch=master)](https://travis-ci.org/mkosolofski/houseseats-monitor)

# Installation
## 1. Bundle Configuration
Add the bundle configuration to app/config/config.yml
### Default
```
m_kosolofski_house_seats_monitor:
     admin_email: ~
     cookie_file: ~
     domain: ~
     login:
         email: ~
         password: ~
         max_attempts: 3
     page:
         login: "/member/index.bv"
         active_shows: /member/ajax.bv?supersecret=&search=&sortField=showTime&startMonthYear=&endMonthYear=&startDate=&endDate=&start=0
     notify:
         emails: [ matthew.kosolofski@gmail.com ]
```
### Example
```
m_kosolofski_house_seats_monitor:
     admin_email: admin@mydomain.com
     cookie_file: /tmp/houseseats_monitor_cookie
     domain: lv.houseseats.com
     login:
         email: houseseats@login_email.com
         password: houseseats_login_password
     notify:
         emails: [ notifiy1@email.com, notify2@email.com ]
```
## 2. Swift Mailer & Doctrine Configurations
In app/config/config.yml, add configurations for "swiftmailer" and "doctrine".

## 3. AppKernel
Add the bundle to app/AppKernel.php
```
new MKosolofski\HouseSeats\MonitorBundle\MKosolofskiHouseSeatsMonitorBundle()
```
## 4. Composer
Add the bundle to composer.json and install
```
"mkosolofski/houseseats-monitor": "*"
```
## 5. Create Database & Tables
Create the database and tables using Symfony "bin/console"

# Usage
```
bin/console mkosolofski:houseseats:monitor:check-for-new-shows
```
## Example Email
![Example Email](http://www.kozo-dev.com/assets/image/kozo-houseseats-bundle-example.gif)
