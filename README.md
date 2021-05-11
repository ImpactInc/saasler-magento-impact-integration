# Impact: Partnership Cloud for Magento

## Description

The purpose of this plugin is to offer users of the Impact brand the 
possibility of ensuring that all transactions made through its site are 
being recorded and it allows to keep track of all clicks made.

### About Impact: Partnership Cloud 

Along with traditional sales and marketing, the partnerships channel is an essential tool for modern ecommerce growth. Many leading brands rely on partnerships to find new customers and drive sales in a scalable, cost-effective way.

Partnership Cloud is an integrated end-to-end solution for managing an enterprise’s partnerships across the entire partner lifecycle to activate rapid growth through the emerging PARTNERSHIP ECONOMY.

From discovery, recruitment and contracting to tracking, optimizing and commissioning, Impact’s Partnership Cloud helps drive growth from partners across the spectrum. Managing a thriving partnership program takes more than just getting partners to sign on the dotted line — it’s about building a relationship and each stage of that relationship needs special attention. But how can you keep thousands of partners productive? Partnership Cloud will help you get started with your Partner program in minutes.

## Discover and Recruit

Discover perfectly aligned global partners – and add them to your partnership program with ease. Save time finding partners with powerful automation technology.

## Contract and pay

Good contracts make for good partnerships - Choose your desired business outcomes, then reward the partners that drive them. Define your terms with flexible electronic contracting and automatically settle payments in 70+ currencies.

## Track

Track everything, all the time - Accurately track partners across web and app properties and attribute performance, no matter how many devices customers use.

## Engage

Keep your partners engaged and measure their performance - Proactive messaging and automated workflows make sure partners stay informed, productive, and on-brand.

## Protect

Identify suspicious payments - Benefit from an industry first: machine learning-based fraud scoring for your partnership program. Suppress and reverse payouts to high-risk sources. Streamline your quality assurance and partner relationship management all at once.

## Optimize

Actionable insights at your fingertips - Continually optimize your partnerships to drive growth and increase efficiency.


## How to install & upgrade Impact_Integration

### 1. Install via composer (recommend)

We recommend you to install Impact_Integration module via composer. It is easy to install, update and maintaince.

Run the following command in Magento 2 root folder.

#### 1.1 Install

```
composer require impact/module-magento-integration
php bin/magento module:enable Impact_Integration
php bin/magento setup:upgrade
```

#### 1.2 Upgrade

```
composer update impact/module-magento-integration
php bin/magento module:enable Impact_Integration
php bin/magento setup:upgrade
```

#### 1.3 Set up module

Finish set up the module by running the following commands.

Run compile:

```
php bin/magento setup:di:compile
```

If you run Magento in production mode, you must deploy the module’s static files:

```
php bin/magento setup:static-content:deploy
```

Flush and clean cache:

```
php bin/magento cache:flush
php bin/magento cache:clean
```

### 2. Install the module manually 

If you don't want to install via composer, you can use this way. 

- Download [the latest version here](https://github.com/saasler/saasler-magento-impact-integration/archive/refs/heads/main.zip).
- Extract `saasler-magento-impact-integration-main.zip` file to `app/code/Impact/Integration` ; You should create a folder path `app/code/Impact/Integration` if not exist.
- Go to Magento root folder and run upgrade command line to install `Impact_Integration`:

```
php bin/magento module:enable Impact_Integration
php bin/magento setup:upgrade
php bin/magento setup:di:compile
``` 

If you run Magento in production mode, you must deploy the module’s static files:

```
php bin/magento setup:static-content:deploy
``` 

Finish set up the module by running the following commands.

``` 
php bin/magento cache:flush
php bin/magento cache:clean
```


## How to uninstall Impact_Integration

### 1. Uninstall via composer (recommend)

Run the following command in Magento 2 root folder:

```
php bin/magento module:uninstall Impact_Integration
```

### 2. Uninstall the module manually 

Before you remove the impact/module folder, on admin panel, in stores/configuration go to Impact Settings tab and click on Uninstall button inside General Configuration tab. 

Remove `impact/module-magento-integration` created in `app/code/impact/module-magento-integration`.

Run the following command in Magento 2 root folder:

```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
php bin/magento cache:clean
```