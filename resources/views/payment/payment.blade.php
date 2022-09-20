@extends('layouts.index')

@section('center')
<section>
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
              <li><a href="#">Home</a></li>
              <li class="active">Shopping Cart</li>
            </ol>
        </div><!--/breadcrums-->
        <div class="shopper-informations">
            <div class="row">
                <div class="col-sm-12 clearfix">
                    <div class="bill-to">
                        <p>Shipping/Bill To</p>
                        <div class="form-one">
                            <div class="total_area">
                                <ul>          
                                    <li>Payment Status <span>{{$payment_info['status'] == 'on_hold' ? 'Not Paid Yet' : ""}}</span></li>
                                    <li>Shipping Cost <span>Free</span></li>
                                    <li>Total <span>{{$payment_info['price']}}</span></li>
                                </ul>
                                    <a class="btn btn-default update" href="">Update</a>
                                    {{-- <a class="btn btn-default check_out" id="paypal-button">Pay Now</a> --}}
                                    <div class="btn" id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>				
            </div>
        </div>
    </div>
</section>
@endsection

{{-- <script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
    paybal.Buttons.render({
        // Configure environment
        env: 'sandbox', // Testing mode
        client: {
            sandbox: 'AXMXSjy1iVQ4wl6GJOCUPGJJTVWZRO5gmeriNNgrvfcMoczQTaG3_KL7iKqMy8lm1SNCIqZKy0haHRQG',
            production: 'demp_production_client_id'
        },
        // Customize button (optional)
        locale: 'en_US',
        style:{
            size: 'small',
            Color: 'gold',
            shape: 'pill',
        },
        // Enable Pay Now checkout flow (optional)
        commit: true,
        // Set up a payment
        payment: function(data, actions){
            return actions.payment.create({
                transactions:[{
                    amount:{
                        total: {!! json_encode($payment_info['price'], JSON_HEX_TAG) !!},
                        currency: 'USD'
                    }
                }]
            });
        },
        // Execute the payment
        onAuthorize: function(data, actions){
            return actions.payment.execute().then(function(){
                // Show a confirmation message to the buyer
                window.alert('Thank you for your purchase!');
                console.log(data);
            });
        }  
    }, '#paypal-button');
</script> --}}

<!-- Replace "test" with your own sandbox Business account app client ID -->
<script src="https://www.paypal.com/sdk/js?client-id=AXMXSjy1iVQ4wl6GJOCUPGJJTVWZRO5gmeriNNgrvfcMoczQTaG3_KL7iKqMy8lm1SNCIqZKy0haHRQG&currency=USD"></script>
<!-- Set up a container element for the button -->

<script>
  paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '{{$payment_info['price']}}', // Can also reference a variable or function
          }
        }]
      });
    },
    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
      return actions.order.capture().then(function(orderData) {
        // Successful capture! For dev/demo purposes:
        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
        const transaction = orderData.purchase_units[0].payments.captures[0];
        alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
        // When ready to go live, remove the alert and show a success message within this page. For example:
        // const element = document.getElementById('paypal-button-container');
        // element.innerHTML = '<h3>Thank you for your payment!</h3>';
        // Or go to another URL:  actions.redirect('thank_you.html');
        console.log(data);
        window.location = '/payment/receipt/'+data.paymentID+'/'+data.payerID;
        

      });
    }
  }).render('#paypal-button-container');
</script>