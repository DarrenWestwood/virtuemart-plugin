# virtuemart-plugin

# Joomla Bitcoin Payments - Blockonomics #
**Tags:** bitcoin, accept bitcoin, bitcoin virtuemart, bitcoin joomla plugin, bitcoin payments

Accept bitcoin payments and altcoins on your Joomla website, Bitcoin payments go directly to your wallet.

## Description ##

The fastest and easiest way to start accepting Bitcoin payments on your VirtueMart online store. Since 2015, [Blockonomics](https://www.blockonomics.co/merchants?utm_source=joomla) has helped thousands of ecommerce sites increase sales by including Bitcoin, Ethereum, Litecoin, and other major altcoins as a payment option for their customers.

## Using the Blockonomics plugin for VirtueMart ##
You must have a Blockonomics merchant account to use this plugin. It's free to sign-up.

### Download ###

Download the zip file from the most recent release on the release page of this repository.

### Install the Blockonomics VirtueMart plugin ###

Go to Extensions -> Extension Manager -> Install
Browse and select the zip file, click Upload & Install.
Go to Manage, and find the plugin under "VM Payment - Blockonomics", and make sure that the plugin is enabled.

### Setup merchant account ###

Create a Merchant API Key in your Blockonomics Merchant account at [Blockonomics](https://www.blockonomics.co/merchants?utm_source=joomla).
Copy The API Key shown on the merchants page.

### Plugin settings - Payment method ###

Go to Components -> VirtueMart and click on Payment Methods.
Click New and type in the payment method information, selecting "VM Payment - Blockonomics" as the Payment Method. Be sure to select "Yes" in the publish section. 
Click save.

### Plugin settings - Blockonomics ###

Select the configuration tab and paste your Merchant API Key.
Copy your Callback URL and click on Save & Close. 

### Blockonomics merchant callback ###

Visit your Blockonomics Merchant account.
Paste your Callback URL in HTTP Callback URL field and click Save Changes.


You’re officially ready to accept Bitcoins on your Joomla site.
