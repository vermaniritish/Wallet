<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>PayPal Checkout Integration</title>
</head>
<body>
  <h2>PayPal Payment Example</h2>

  <!-- Container where PayPal button will render -->
  <div id="paypal-button-container"></div>

  <!-- ✅ Load PayPal SDK (Sandbox for testing) -->
  <script src="https://www.paypal.com/sdk/js?client-id=AYXlDi-lHWz99toobrKV0fzLCeQanEV5z4UnJB1fsSZN6r-xzqEacYY4KnESQuEyVgu-ARJ47y_YEkKb&currency=GBP"></script>

  <script>
    // Render PayPal buttons
    paypal.Buttons({
      // Set up the transaction
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: '10.00' // 💰 Replace with your amount
            },
            description: 'Test Purchase - Demo Item'
          }]
        });
      },

      // Finalize the transaction after payer approval
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          alert('✅ Transaction completed by ' + details.payer.name.given_name);
          console.log(details); // Log the full response for debugging

          // Example: redirect to your success page
          // window.location.href = '/paypal/success?orderId=' + details.id;
        });
      },

      onError: function(err) {
        console.error('❌ PayPal Error:', err);
        alert('Something went wrong during the transaction.');
      }
    }).render('#paypal-button-container');
  </script>
</body>
</html>
