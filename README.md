# Notice

-----

##### If you have installed this plugin after 7/30/19, please recreate your API token

-----

This is a Community-supported project.

If you are interested in becoming a maintainer of this project, please contact us at integrations@bitpay.com. Developers at BitPay will attempt to work along the new maintainers to ensure the project remains viable for the foreseeable future.

# Description

This is the BitPay payment plugin for ZenCart.

[![Build Status](https://travis-ci.org/bitpay/zencart-plugin.svg?branch=master)](https://travis-ci.org/bitpay/zencart-plugin)

# Requirements

This plugin requires the following:

* [Zen Cart](https://www.zen-cart.com/).
* A BitPay merchant account ([Test](http://test.bitpay.com) and [Production](http://www.bitpay.com))

# Installation

After the plugin is installed on your Zen Cart installation, set the following options

* **Enable BitPay Checkout Module**
	* Set to **TRUE** to enable  the checkout method
* **Production Environment**
	* By default this is set to **FALSE** and will use the *Sandbox* environment, [test.bitpay.com](test.bitpay.com).  Set to **TRUE** and configure your account at [bitpay.com](bitpay.com)
* **Production API Key**
	* Your *production* API key, used for real transactions
* **Sandbox API Key**
	* Your *sandbox* API key, used for testing the installation
* **Transaction speed**
	* This determines at what point orders are considered *paid*.  **Medium** is the default for most users

## Support

### BitPay Support

* Last Cart Version Tested: 1.5.1
* [GitHub Issues](https://github.com/bitpay/zencart-plugin/issues)
  * Open an issue if you are having issues with this plugin.
* [Support](https://help.bitpay.com)
  * BitPay merchant support documentation

### ZenCart Support

* [Homepage](http://www.zen-cart.com)
* [Documentation](http://www.zen-cart.com/wiki/index.php/Developers_API)
* [Support Forums](http://www.zen-cart.com/forum.php)

## Contribute

To contribute to this project, please fork and submit a pull request.

## License

The MIT License (MIT)

Copyright (c) 2011-2015 BitPay

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
