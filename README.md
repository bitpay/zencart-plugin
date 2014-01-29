©2012,2013,2014 BITPAY, INC.

Permission is hereby granted to any person obtaining a copy of this software
and associated documentation for use and/or modification in association with
the bit-pay.com service.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

Bitcoin payment module using the bit-pay.com service.

Installation
------------
Copy these files into your Zen Cart directory.

Configuration
-------------
1. Create an API key at bitpay.com under your My Account section.
2. In Admin panel under "Modules > Payment > Bitcoins" click Install.
3. Fill out all configuration information:
	a. Verify that the module is enabled.
	b. Set the API key to the value you created in step 1.
	c. Select a transaction speed.  The high speed will send a confirmation as soon as a transaction is received in the bitcoin network (usually a few seconds).  A medium speed setting will typically take 10 minutes.  The low speed setting usually takes around 1 hour.  See the bit-pay.com merchant documentation for a full description of the transaction speed settings.
	d. Choose a status for unpaid and paid orders (or leave the default values as defined).  
	e. Verify that the currencies displayed correspond to what you want and to those accepted by bit-pay.com (the defaults are what bitpay accepts as of this writing).
	f. Choose a sort order for displaying this payment option to visitors.  Lowest is displayed first.

Usage
-----
When a shopping chooses the Bitcoin payment method, they will be presented with an order summary as the next step (prices are shown in whatever currency they've selected for shopping).  Upon receiving their order, the system takes the shopper to a bit-pay.com invoice where the user is presented with bitcoin payment instructions.  Once payment is received, a link is presented to the shopper that will take them back to your website.

In your Admin control panel, you can see the orders made with Bitcoins just as you would any other order.  The status you selected in the configuration steps above will indicate whether the order has been paid for.  

Note: This extension does not provide a means of automatically pulling a current BTC exchange rate for presenting BTC prices to shoppers.

Change Log
----------
Version 1
  - Initial version, tested against Zen Cart 1.3.9h
Version 2
  - Updated to support API key instead of SSL files.  Tested against Zen Cart 1.5.1.
