bitpay/zencart-plugin
=====================

# Installation

Copy these files into your Zen Cart directory.

# Configuration

1. Create an API key at bitpay.com under your My Account section.
2. In Admin panel under "Modules > Payment > Bitcoins" click Install.
3. Fill out all configuration information:<br />
a. Verify that the module is enabled.<br />
b. Set the API key to the value you created in step 1.<br />
c. Select a transaction speed.  The high speed will send a confirmation as soon as a transaction is received in the bitcoin network (usually a few seconds).  A medium speed setting will typically take 10 minutes.  The low speed setting usually takes around 1 hour.  See the bitpay.com merchant documentation for a full description of the transaction speed settings: https://bitpay.com/downloads/bitpayApi.pdf<br />
d. Choose a status for unpaid and paid orders (or leave the default values as defined).<br />
e. Verify that the currencies displayed correspond to what you want and to those accepted by bitpay.com (the defaults are what bitpay accepts as of this writing).<br />
f. Choose a sort order for displaying this payment option to visitors.  Lowest is displayed first.<br />

# Usage

When a shopping chooses the Bitcoin payment method, they will be presented with an order summary as the next step (prices are shown in whatever currency they've selected for shopping). Upon receiving their order, the system takes the shopper to a bitpay.com invoice where the user is presented with bitcoin payment instructions.  Once payment is received, a link is presented to the shopper that will take them back to your website.

In your Admin control panel, you can see the orders made with Bitcoins just as you would any other order.  The status you selected in the configuration steps above will indicate whether the order has been paid for.  

Note: This extension does not provide a means of automatically pulling a current BTC exchange rate for presenting BTC prices to shoppers.

# Support

## BitPay Support

* [GitHub Issues](https://github.com/bitpay/zencart-plugin/issues)
  * Open an issue if you are having issues with this plugin.
* [Support](https://support.bitpay.com)
  * BitPay merchant support documentation

## ZenCart Support

* [Homepage](http://www.zen-cart.com)
* [Documentation](http://www.zen-cart.com/wiki/index.php/Developers_API)
* [Support Forums](http://www.zen-cart.com/forum.php)

# Contribute

To contribute to this project, please fork and submit a pull request.

# License

The MIT License (MIT)

Copyright (c) 2011-2014 BitPay

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
