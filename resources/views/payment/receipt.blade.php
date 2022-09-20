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
                        <p>Payment Receipt</p>
                        <div class="form-one">
                            <div class="total_area">
                                <ul>          
                                    <li>Order ID <span>{{$payment_receipt['order_id']}}</span></li>
                                    <li>Payer ID <span>{{$payment_receipt['paypal_payer_id']}}</span></li>
                                    <li>Payment ID <span>{{$payment_receipt['paypal_payment_id']}}</span></li>
                                    <li>Total <span>{{$payment_receipt['price']}}</span></li>
                                
                                </ul>
                                    {{-- <a class="btn btn-default update" href="">Update</a> --}}
                                    {{-- <a class="btn btn-default check_out" id="paypal-button">Pay Now</a> --}}
                                    {{-- <div class="btn" id="paypal-button-container"></div> --}}
                            </div>
                        </div>
                    </div>
                </div>				
            </div>
        </div>
    </div>
</section>
@endsection