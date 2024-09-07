'use strict';

/**
 * @fileoverview Google Pay API integration example
 */

// Tokenization specification for the payment gateway
const tokenizationSpecification = {
  type: 'PAYMENT_GATEWAY',
  parameters: {
    gateway: 'example',
    gatewayMerchantId: 'gatewayMerchantID', // Replace with your gateway merchant ID
  },
};

// Card payment method
const cardPaymentMethod = {
  type: 'CARD',
  tokenizationSpecification: tokenizationSpecification,
  parameters: {
    allowedCardNetworks: ['VISA', 'MASTERCARD'],
    allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
  },
};

// Google Pay configuration
const googlePayConfiguration = {
  apiVersion: 2,
  apiVersionMinor: 0,
  allowedPaymentMethods: [cardPaymentMethod],
};

/**
 * @type {PaymentsClient}
 * @private
 */
let googlePayClient;

// Function to be called when Google Pay is loaded
function onGooglePayLoaded() {
  googlePayClient = new google.payments.api.PaymentsClient({
    environment: 'TEST',
  });

  googlePayClient.isReadyToPay(googlePayConfiguration)
    .then(response => {
      if (response.result) {
        document.querySelectorAll('.buy-now').forEach(button => {
          button.style.display = 'inline-block';
          button.addEventListener('click', onGooglePayButtonClicked);
        });
      } else {
        console.log("Google Pay is not available");
      }
    })
    .catch(error => console.error('isReadyToPay error: ', error));
}

// Function that captures the click event
function onGooglePayButtonClicked(event) {
  const carId = event.target.dataset.carId;
  const carPrice = event.target.dataset.carPrice;

  const paymentDataRequest = { ...googlePayConfiguration };
  paymentDataRequest.merchantInfo = {
    merchantId: 'BCR2DN4TWWMPJMJP', // Replace with your actual merchant ID
    merchantName: 'Jaki General Supplies',
  };

  paymentDataRequest.transactionInfo = {
    totalPriceStatus: 'FINAL',
    totalPrice: carPrice, // Use the actual total price
    currencyCode: 'TZS',
    countryCode: 'TZ',
  };

  googlePayClient.loadPaymentData(paymentDataRequest)
    .then(paymentData => processPaymentData(paymentData, carId))
    .catch(error => console.error('loadPaymentData error:', error));
}

// Function to process the payment data
function processPaymentData(paymentData, carId) {
  fetch('your-orders-endpoint-url', { // Replace with your actual endpoint URL
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ paymentData, carId:1 }),
  })
  .then(response => response.json())
  .then(data => {
    // Handle the response from your server
    console.log('Payment successful', data);
  })
  .catch(error => console.error('Error processing payment data:', error));
}

// Load Google Pay script
document.addEventListener('DOMContentLoaded', onGooglePayLoaded);
