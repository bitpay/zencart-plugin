# Using the BitPay plugin for ZenCart

## Prerequisites

* Last Cart Version Tested: 1.5.1

You must have a BitPay merchant account to use this plugin.  It's free to [sign-up for a BitPay merchant account](https://bitpay.com/start).

## Installation

Download the zip file for this plugin, unzip the arcive and copy the files into the ZenCart directory on your webserver.

## Configuration

* Create an API key at bitpay.com under your My Account section.
* In Admin panel under "Modules > Payment > Bitcoins" click Install.
* Fill out all configuration information:
  * Verify that the module is enabled.
  * Set the API key to the value you created in step 1.
  * Select a transaction speed.  The high speed will send a confirmation as soon as a transaction is received in the bitcoin network (usually a few seconds).  A medium speed setting will typically take 10 minutes.  The low speed setting usually takes around 1 hour.  See the bitpay.com merchant documentation for a full description of the transaction speed settings: https://bitpay.com/downloads/bitpayApi.pdf<br />
  * Choose a status for unpaid and paid orders (or leave the default values as defined).<br />
  * Verify that the currencies displayed correspond to what you want and to those accepted by bitpay.com (the defaults are what bitpay accepts as of this writing).<br />
  * Choose a sort order for displaying this payment option to visitors.  Lowest is displayed first.<br />

## Usage

When a shopping chooses the Bitcoin payment method, they will be presented with an order summary as the next step (prices are shown in whatever currency they've selected for shopping). Upon receiving their order, the system takes the shopper to a bitpay.com invoice where the user is presented with bitcoin payment instructions.  Once payment is received, a link is presented to the shopper that will take them back to your website.

In your Admin control panel, you can see the orders made with Bitcoins just as you would any other order.  The status you selected in the configuration steps above will indicate whether the order has been paid for.  

**Note:** This extension does not provide a means of automatically pulling a current BTC exchange rate for presenting BTC prices to shoppers.
