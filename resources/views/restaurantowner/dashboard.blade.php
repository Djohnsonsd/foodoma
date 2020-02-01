@extends('admin.layouts.master')
@section("title")
Dashboard
@endsection
@section('content')
<div class="content mb-5">
    <div class="row mt-3">
        <div class="col-6 col-xl-3 mt-2">
            <div class="col-xl-12 dashboard-display p-3">
                <a class="block block-link-shadow text-left" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-right mt-10 d-none d-sm-block">
                            <i class="dashboard-display-icon icon-city"></i>
                        </div>
                        <div class="dashboard-display-number">{{ $restaurantsCount }}</div>
                        <div class="font-size-sm text-uppercase text-muted">Restaurants</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6 col-xl-3 mt-2">
            <div class="col-xl-12 dashboard-display p-3">
                <a class="block block-link-shadow text-left" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-right mt-10 d-none d-sm-block">
                            <i class="dashboard-display-icon icon-basket"></i>
                        </div>
                        <div class="dashboard-display-number">{{ $ordersCount }}</div>
                        <div class="font-size-sm text-uppercase text-muted">Orders Processed</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6 col-xl-3 mt-2">
            <div class="col-xl-12 dashboard-display p-3">
                <a class="block block-link-shadow text-left" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-right mt-10 d-none d-sm-block">
                            <i class="dashboard-display-icon icon-stack-star"></i>
                        </div>
                        <div class="dashboard-display-number">{{ $orderItemsCount }}</div>
                        <div class="font-size-sm text-uppercase text-muted">Items Sold</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6 col-xl-3 mt-2">
            <div class="col-xl-12 dashboard-display p-3">
                <a class="block block-link-shadow text-left" href="javascript:void(0)">
                    <div class="block-content block-content-full clearfix">
                        <div class="float-right mt-10 d-none d-sm-block">
                            <i class="dashboard-display-icon icon-coin-dollar"></i>
                        </div>
                        <div class="dashboard-display-number">{{ config('settings.currencyFormat') }} {{ $totalEarning }}</div>
                        <div class="font-size-sm text-uppercase text-muted">Earnings</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row pt-4 p-0">
        <div class="col-xl-12">
            <div class="panel panel-flat dashboard-main-col mt-4">
                <div class="panel-heading">
                    <h4 class="panel-title pl-3 pt-3"><strong>NEW ORDERS</strong></h4>
                    <hr>
                </div>
                <div id="newOrdersTable" class="table-responsive @if(!count($newOrders)) hidden @endif">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Restaurant</th>
                                <th>Price</th>
                                <th>Order Status</th>
                                <th class="text-center"><i class="
                                    icon-circle-down2"></i></th>
                            </tr>
                        </thead>
                        <tbody id="newOrdersData">
                            @foreach($newOrders as $nO)
                            <tr>
                                <td>
                                    <a href="{{ route('restaurant.viewOrder', $nO->unique_order_id) }}" class="letter-icon-title">{{ $nO->unique_order_id }}</a>
                                </td>
                                <td>
                                    {{ $nO->restaurant->name }}
                                </td>
                                <td>
                                    <span class="text-semibold no-margin">{{ config('settings.currencyFormat') }} {{ $nO->total }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-flat border-grey-800 text-default text-capitalize">
                                    NEW
                                    </span>
                                    @if($nO->delivery_type == 2)
                                        <span class="badge badge-flat border-danger-800 text-default text-capitalize">
                                        Self Pickup
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('restaurant.acceptOrder', $nO->id) }}"
                                        class="badge badge-primary badge-icon"> Accept Order <i
                                        class="icon-checkmark3 ml-1"></i></a>
                                        <a href="{{ route('restaurant.cancelOrder', $nO->id) }}"
                                        class="badge badge-danger badge-icon cancelOrderBtn" data-popup="tooltip" data-placement="top" title="Double Click to Cancel"> Cancel Order <i
                                        class="icon-cross ml-1"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(!count($newOrders)) 
                <div class="text-center text-muted pb-2" id="newOrdersNoOrdersMessage">
                    <h4> No orders to show</h4>
                </div>
                @endif
            </div>
        </div>
        <div class="col-xl-12">
            <div class="panel panel-flat dashboard-main-col mt-4">
                <div class="panel-heading">
                    <h4 class="panel-title pl-3 pt-3"><strong>PREPARING ORDERS</strong></h4>
                    <hr>
                </div>
                @if(count($acceptedOrders))
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Price</th>
                                <th>Order Placed Time</th>
                                <th>Order Accepted Time</th>
                                <th>Order Status</th>
                                <th class="text-center"><i class="
                                    icon-circle-down2"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($acceptedOrders as $aO)
                            <tr>
                                <td>
                                    <a href="{{ route('restaurant.viewOrder', $aO->unique_order_id) }}" class="letter-icon-title">{{ $aO->unique_order_id }}</a>
                                </td>
                                <td>
                                    <span class="text-semibold no-margin">{{ config('settings.currencyFormat') }} {{ $aO->total }}</span>
                                </td>
                                <td>
                                    {{ $aO->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    {{ $aO->updated_at->diffForHumans() }}
                                </td>
                                <td>
                                    <span class="badge badge-flat border-grey-800 text-default text-capitalize">
                                    RUNNING
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($aO->delivery_type == 2 && $aO->orderstatus_id == 2)
                                    <a href="{{ route('restaurant.markOrderReady', $aO->id) }}"
                                        class="badge badge-warning badge-icon"> Mark as Ready <i
                                        class="icon-checkmark3 ml-1"></i></a>
                                    @endif
                                    @if($aO->delivery_type == 2 && $aO->orderstatus_id == 7)
                                    <a href="{{ route('restaurant.markSelfPickupOrderAsCompleted', $aO->id) }}"
                                        class="badge badge-success badge-icon"> Mark as Completed <i
                                        class="icon-checkmark3 ml-1"></i></a>
                                    @endif
                                    @if($aO->delivery_type == 1)
                                        <span>--</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center text-muted pb-2">
                        <h4> No orders to show</h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
    	var notification = document.createElement('audio');
           notification.setAttribute('src', '{{substr(url("/"), 0, strrpos(url("/"), '/'))}}/assets/backend/audio/new-order.mp3');
           notification.setAttribute('type', 'audio/mp3');
           notification.setAttribute('muted', 'muted');
    
    	let array = @json($newOrdersJS);
    
    	setInterval(function() {
    		$.ajax({
    			url: '{{ route('restaurant.getNewOrders') }}',
    			type: 'GET',
    			dataType: 'json',
    		})
    
    		.done(function(newArray) {
    			if (JSON.stringify(array) !== JSON.stringify(newArray)) {
    			    //if orders are not same so something here..
    			    array = newArray;
    			    //play sound
    			    notification.play();
    				
    				var tableData = "";
    			    $.map(newArray, function(item, index) {
                        if(item.delivery_type == 2) {
                            var selfPickup = '<span class="badge badge-flat border-danger-800 text-default text-capitalize">Self Pickup</span>'
                        } else {
                            selfPickup = "";
                        }
    			    	var viewOrderURL = "{{ url('/restaurant-owner/order') }}/" + item.unique_order_id;
    			    	var acceptedOrderURL = "{{ url('/restaurant-owner/orders/accept-order') }}/" + item.id;
                        var cancelOrderURL = "{{ url('/restaurant-owner/orders/cancel-order') }}/" + item.id;
    			    	tableData += '<tr><td> <a href="'+viewOrderURL+'" class="letter-icon-title">'+item.unique_order_id+'</a> </td>';
                        tableData += '<td>'+item.restaurant.name+'</td>';
    			    	tableData += '<td><span class="text-semibold no-margin">{{ config('settings.currencyFormat') }}'+item.total+'</span></td>';
    			    	tableData += '<td> <span class="badge badge-flat border-grey-800 text-default text-capitalize"> NEW </span> '+ selfPickup +'</td>';
    			    	tableData += '<td class="text-center"> <a href="'+acceptedOrderURL+'" class="badge badge-primary badge-icon mr-2"> Accept Order <i class="icon-checkmark3 ml-1"></i></a> <a href="'+cancelOrderURL+'" class="badge badge-danger badge-icon cancelOrderBtn" data-popup="tooltip" data-placement="top" title="Double Click to Cancel"> Cancel Order <i class="icon-checkmark3 ml-1"></i></a> </td></tr>'
    			    	$('#newOrdersData').html(tableData);
    			    	$('#newOrdersTable').removeClass('hidden')
    			    	$('#newOrdersNoOrdersMessage').remove();
    			    });
    			}
    		})
    		.fail(function() {
    			console.log("error");
    		})	
    	}, 10 * 1000);

        //double click to cancel 
        
        $('.cancelOrderBtn').click(function() {
            return false;
        }).dblclick(function() {
            window.location = this.href;
            return false;
        });
        
        /** FIREBASE **/
        @if(config('settings.firebaseSDKSnippet') != NULL)
            // firebase config from db
            {!! config('settings.firebaseSDKSnippet') !!}
            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);
            
            // Retrieve Firebase Messaging object.
            const messaging = firebase.messaging();

            navigator.serviceWorker.register('{{substr(url("/"), 0, strrpos(url("/"), '/'))}}/firebase-messaging-sw.js')
            .then((registration) => {
              messaging.useServiceWorker(registration);

              // Request permission and get token.....
              messaging.requestPermission().then(function() {
                  console.log('Notification permission granted.');
                  //make API call to save the user token to send notifications
              }).catch(function(err) {
                console.log('Unable to get permission to notify.', err);
              });
              
              messaging.getToken().then(function(currentToken) {
                if (currentToken) {
                  console.log(currentToken);
                  console.log("make api call now")
                  // make API CALL to save token in db
                  $.ajax({
                      url: '{{ url('restaurant-owner/save-restaurant-owner-notification-token') }}',
                      type: 'POST',
                      dataType: 'JSON',
                      data: {user_id: "{{Auth::user()->id}}", _token: "{{ csrf_token() }}" , push_token: currentToken},
                  })
                  .done(function() {
                      console.log("Saving notification token success");
                  })
                  .fail(function() {
                      console.log("Saving notification token failed");
                  })
                  
                }
              }).catch(function(err) {
                console.log('An error occurred while retrieving token. ', err);
              });
            });
        @endif
        /** END FIREBASE **/
    });
</script>
@endsection