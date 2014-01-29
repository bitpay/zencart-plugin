<?php

/**
 * ©2011,2012,2013,2014 BITPAY, INC.
 * 
 * Permission is hereby granted to any person obtaining a copy of this software
 * and associated documentation for use and/or modification in association with
 * the bitpay.com service.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * Bitcoin payment plugin using the bitpay.com service.
 * 
 */
 
 
//	Bitpay Payment Module - Lanugage File
define('MODULE_PAYMENT_BITPAY_TEXT_TITLE', 'Bitcoins via bitpay.com');
define('MODULE_PAYMENT_BITPAY_TEXT_DESCRIPTION', 'Use bitpay.com\'s invoice processing server to automatically accept bitcoins.');
define('MODULE_PAYMENT_BITPAY_TEXT_EMAIL_FOOTER', 'You just paid with bitcoins via bitpay.com -- Thanks!');

// Error messages:
define('MODULE_PAYMENT_BITPAY_BAD_CURRENCY', 'Currency not supported by bitpay.com.  Please choose another currency.');
define('MODULE_PAYMENT_BITPAY_CREATE_INVOICE_FAILED', 'Unable to process payment using bitpay.  Please choose another form of payment.');
?>
