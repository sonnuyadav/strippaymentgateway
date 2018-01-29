<?php 
require 'stripe/Stripe.php';
require 'connect.php';
require 'functions.php';
$params = array(
   "testmode"   => "on",
   "private_live_key" => "sk_live_ojQXBh4RYiCAo2WNmCtKnKRj",
   "public_live_key"  => "pk_live_ymJ2YRy9TSA1CgcHF2BgB5nu",
   "private_test_key" => "sk_test_fP8OmzYGsqe1yYKyHuUsiywL",
   "public_test_key"  => "pk_test_dtVqq9jStFOdLc4BqyNQ7UAa"
);

if ($params['testmode'] == "on") {
   Stripe::setApiKey($params['private_test_key']);
   $pubkey = $params['public_test_key'];
} else {
   Stripe::setApiKey($params['private_live_key']);
   $pubkey = $params['public_live_key'];
}


if(isset($_POST['stripeToken']))
{
   $amount_cents = str_replace(".","","10.52");  // Chargeble amount
   $invoiceid = "14526321";                      // Invoice ID
   $description = "Invoice #" . $invoiceid . " - " . $invoiceid;

   try {
      $charge = Stripe_Charge::create(array(     
           "amount" => $amount_cents,
           "currency" => "usd",
           "source" => $_POST['stripeToken'],
           "description" => $description)         
      );

      if ($charge->card->address_zip_check == "fail") {
         throw new Exception("zip_check_invalid");
      } else if ($charge->card->address_line1_check == "fail") {
         throw new Exception("address_check_invalid");
      } else if ($charge->card->cvc_check == "fail") {
         throw new Exception("cvc_check_invalid");
      }
      // Payment has succeeded, no exceptions were thrown or otherwise caught          

      $result = "success";

   } catch(Stripe_CardError $e) {         

   $error = $e->getMessage();
      $result = "declined";

   } catch (Stripe_InvalidRequestError $e) {
      $result = "declined";        
   } catch (Stripe_AuthenticationError $e) {
      $result = "declined";
   } catch (Stripe_ApiConnectionError $e) {
      $result = "declined";
   } catch (Stripe_Error $e) {
      $result = "declined";
   } catch (Exception $e) {

      if ($e->getMessage() == "zip_check_invalid") {
         $result = "declined";
      } else if ($e->getMessage() == "address_check_invalid") {
         $result = "declined";
      } else if ($e->getMessage() == "cvc_check_invalid") {
         $result = "declined";
      } else {
         $result = "declined";
      }       
   }
   echo "<BR>Stripe Payment Status : ".$result;
   echo "<BR>Stripe Response : ";
   print_r($charge); exit;
}
?>
<!DOCTYPE html>
<html>
<head >
    <meta charset="utf-8">
    <!--     
        <base href="/"> -->
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Become A Juara Guide </title>
	<!-- seo meta tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,700" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Raleway:200,300,700" rel="stylesheet"> 
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
	<link rel='stylesheet' id='google-fonts-css'  href='http://fonts.googleapis.com/css?family=Nunito%3A400%2C700%7COpen+Sans%3A400%2C700%26subset%3Dlatin%2Clatin-ext' type='text/css' media='all' />

	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	<link href="styles.css" rel="stylesheet">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
	<script	src="https://code.jquery.com/jquery-3.1.1.min.js"
	integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
	crossorigin="anonymous"></script>
   
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

	
  <script type="text/javascript" src="https://www.gpsindia.net/assets/frontend/js/jquery.min.js"></script>
  <link rel="stylesheet" href="dev.css" />
    <script type="text/javascript"  src="http://hr.sonuyadav.com/jaura/scripts.js"></script>
      <script type="text/javascript" src="https://www.gpsindia.net/assets/frontend/js/jquery.validate.js"></script>

      <style type="text/css">
        .stripe-button-el{
              width: 100%!important;
        }
      </style>
</head> 



<body class="body"> 
     <div class="wrapper main_wrapper text-shadow" ng-controller="rootAppCtrl">
   <div class="container">
      <div class="header">
         <img src="https://juaraskincare.com/wp-content/uploads/2015/08/juara-logo.png"
            class="col-md-2 col-sm-2 col-xs-8">
      </div>
      <p class="col-xs-12"></p>
      <div class="col-md-8 col-md-offset-2 text-center margin-5">
         <h1 class="">Become A
            <span>Juara Guide</span>
         </h1>
         <h3>Join The Movement To Unlock The Secrets Of Jamu To Help Improve Our Daily Lives. </h3>
         <p></p>
      </div>
      <div class="col-md-8 col-md-offset-2 form  right margin-5  ">
         <form id="payform" name="myForm" method="post">
           <label>First Name <i class="glyphicon glyphicon-star icon-require"></i></label><input required type="text" placeholderx="First Name" name="fname">
          
            </p>
            <p class="col-md-12">
            <label>Last Name <i class="glyphicon glyphicon-star icon-require"></i></label>
            <input required type="text" placeholderx="Last Name" ng-model="userInfo.lname" name="lname">
            <div class="col-md-12" ng-show="myForm.$submitted  && myForm.lname.$invalid">
            </div>
            </p>
            <p class=" col-xs-12">
            <label>Phone number <i class="glyphicon glyphicon-star icon-require"></i></label>
            <input required type="text" placeholderx="Phone number" ng-model="userInfo.phone" name="phone">
            
            </p>
            <p class=" col-xs-12">
            <label>Email <i class="glyphicon glyphicon-star icon-require"></i></label>
            <input type="email" name="email" required>
            
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12">
                  1. Choose how you want to be contacted <i
                     class="glyphicon glyphicon-star icon-require"></i>
            <div class="cust-div">Choose as many as you like</div>
            </label>
            <div class="col-md-12">
               <label class="cust-label">Email <input type="checkbox" name="contactedOption_email"></label>
               <label class="cust-label"> Mobile Phone <input type="checkbox" name="contactedOption_phone"
                 ></label>
               <label class="cust-label">Alternative Phone <input type="checkbox" name="contactedOption_altPhone"
                 ></label>
            </div>
           
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12">
                  2. SHIPPING INFO: What is your Street Address, (including apt number)
            <div class="cust-div">This is the location that you would like your JUARA Guide Starter System
            to be delivered to.
            </div>
            </label>
            <input type="text" name="shippingInfo" ng-model="userInfo.shippingInfo" required>
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12">3. What is your City?
               </label>
               <input type="text" name="city" required>
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12">4. What is your Zip Code?
               </label>
               <input type="text" name="zipCode" required>
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12">5. Your State?
               </label>
               <input type="text" name="state" required>
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12 cust-label">6. Please agree to our Terms & Conditions*
               <span class="col-md-6 pull-right ">
               <input type="checkbox" name="isTermAccept" value="" required />
               </span>
               </label>
          
            </p>
            <p class=" col-xs-12">
               <label class="col-md-12 cust-label">7. Please agree to our Policies & Procedures*
               <span class="col-md-6 pull-right ">
               <input type="checkbox" name="isAgreeToPolicy" required />
               </span>
               </label>
           
            </p>


            <p class="col-md-6 col-sm-6 col-xs-12">
   <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="<?php echo $params['public_test_key']; ?>"
    data-amount="2500"
    data-name="juaraskincare.com"
    data-description="Service"
    data-image="https://juaraskincare.com/wp-content/uploads/2015/08/juara-logo.png"
    data-locale="auto"
    data-zip-code="true">
  </script>
  </p>
         </form>
      </div>
   </div>
</div>
    </body>
</html>



