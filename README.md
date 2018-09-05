# virtuemart-plugin
Accept bitcoins on your Joomla site, payments go directly into your wallet

Using the Blockonomics plugin for VirtueMart
You must have a Blockonomics merchant account to use this plugin. It's free to sign-up.

1. Download
Download the zip file from the most recent release on the release page of this repository.

2. Install the Blockonomics VirtueMart plugin

Go to Extensions -> Extension Manager -> Install
Browse and select the zip file, click Upload & Install.
Go to Manage, and find the plugin under "VM Payment - Blockonomics", and make sure that the plugin is enabled.

3. Setup merchant account

Create a Merchant API Key in your Blockonomics Merchant account at https://blockonomics.co.
Copy The API Key shown on the merchants page.

4. Plugin settings - Payment method

Go to Components -> VirtueMart and click on Payment Methods.
Click New and type in the payment method information, selecting "VM Payment - Blockonomics" as the Payment Method. Be sure to select "Yes" in the publish section. 
Click save.

5. Plugin settings - Blockonomics

Select the configuration tab and paste your Merchant API Key.
Copy your Callback URL and click on Save & Close. 

6. Blockonomics plugin settings

Visit your Blockonomics Merchant account.
Paste your Callback URL in HTTP Callback URL field and click Save Changes.


You’re officially ready to accept Bitcoins on your Joomla site.
