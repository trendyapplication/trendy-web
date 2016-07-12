<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lunch Matcher</title>
<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="<?php echo base_url();?>assets/css/theme.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

</head>
<div class="background_loader" style="display:none;">
	<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
</div>
<body>
<header role="banner" id="top" class="navbar navbar-fixed-top cust_nav fx_top">
  <div class="container">
    <div class="navbar-header">
      <button data-target=".bs-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="../"><img src="<?php echo base_url();?>assets/images/logo.png" class="img-responsive"></a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse">
      
      <ul class="nav navbar-nav navbar-right cust_nav_lnk">
        <li><a class="navlink active" rel="home" href="#home" data-top="0" >HOME</a></li>
        <li><a class="navlink" rel="overvw" href="#overvw" data-top="650">OVERVIEW</a></li>
        <li><a class="navlink" rel="feature" href="#feature" data-top="1250">FEATURES</a></li>
        <li><a class="navlink" rel="gallery" href="#gallery" data-top="1800">GALLERY</a></li>
        <li><a class="navlink" rel="contact" href="#contact" data-top="2800">CONTACT</a></li>
      </ul>
    </nav>
  </div>
</header>

<a id="home">
<div class="banner">
<div class=""><img src="<?php echo base_url();?>assets/images/bg-banner.png" class="img-responsive"></div>

<div class="caption_wp">
<div class="col-lg-10">
<h2><span>LUNCH MATCHER:</span>

USE YOUR LUNCH BREAKS TO NETWORK WITH NEW, NEARBY PROFESSIONALS EVERY DAY!</h2>
<p>Why have lunch at your desk or with the same old colleagues? Make use of your lunch time to meet new like-minded professionals in your area, 
and discover interesting new places to eat! </p>

<h3>Coming soon...</h3>
<h4><button class="btn ntfy_btn" data-toggle="modal" data-target="#myModal">SIGNUP TO BE NOTIFIED</button></h4>

</div>
</div>
</div>
</a>


<div class="overview">
<div class="container">
	<a id="overvw"><h4>OVERVIEW</h4></a>
    <hr>
    
    <div class="col-lg-4">
    <div class="tab_section">
    <a href="">
    	<i><img src="<?php echo base_url();?>assets/images/ioc1.png"></i>
        <h4>LUNCH MATCHING</h4>
        <p>Our  advanced algorithms create a daily random match based on your preferences and shared favorite nearby venues.</p>
    </a>
    </div>
    </div>
    
    <div class="col-lg-4">
    <div class="tab_section_gray">
    <a href="#">
    	<i><img src="<?php echo base_url();?>assets/images/ioc2.png"></i>
        <h4>BRANCH OUT</h4>
        <p>Expand your professional network by making new connections with interesting people working nearby you.</p>
    </a>
    </div>
    </div>
    
    <div class="col-lg-4">
    <div class="tab_section">
    <a href="#">
    	<i><img src="<?php echo base_url();?>assets/images/ioc3.png"></i>
        <h4>EAT DIFFERENT</h4>
        <p>Experience new tastes in your area as our app recommends nearby venues and shows how others have rated them.</p>
    </a>
    </div>
    </div>
    
</div>
</div>


<div class="features">
<div class="container">
<div class="col-lg-9">
<a id="feature"><h4>FEATURES</h4></a>
<hr>

<div class="col-lg-6 feature_list">
<h6>CONTROL YOUR EXPERIENCE</h6>
<p>Set your availability parameters, and see the person’s LinkedIn profile before deciding to accept a match.</p>
</div>

<div class="col-lg-6 feature_list feature_list1">
<h6>PEOPLE-SEARCH PREFERENCES</h6>
<p>Specify the exact area & radius for user matching, and set exclusions such as gender and company.</p>
</div>


<div class="col-lg-6 feature_list feature_list2">
<h6>RATING & REVIEW</h6>
<p>Rate your experiences with both people and venues. Our intricate scoring system does the rest!</p>
</div>


<div class="col-lg-6 feature_list feature_list3">
<h6>MAKE NEW CONTACTS</h6>
<p>Grow your social and professional circle exponentially by simply filling your lunch calendar.</p>
</div>


</div>

<div class="col-lg-3">
<img src="<?php echo base_url();?>assets/images/feature_img.png" class="img-responsive">
</div>
</div>
</div>



<div class="gallery">
<div class="container">
      <a id="gallery"><h4>GALLERY</h4></a>
      <hr>

	  <div data-ride="carousel" class="carousel slide" id="carousel-example-generic">
      <ol class="carousel-indicators">
        <li class="" data-slide-to="0" data-target="#carousel-example-generic"></li>
        <li data-slide-to="1" data-target="#carousel-example-generic" class=""></li>
        <li data-slide-to="2" data-target="#carousel-example-generic" class="active"></li>
      </ol>
      <div role="listbox" class="carousel-inner">
        <div class="item active">
          <img alt="First slide [900x500]" data-src="" src="<?php echo base_url();?>assets/images/slider1.png" data-holder-rendered="true">
        </div>
        <div class="item">
          <img alt="Second slide [900x500]" data-src="" src="<?php echo base_url();?>assets/images/slide2.png" data-holder-rendered="true">
        </div>
        <div class="item ">
          <img alt="Third slide [900x500]" data-src="" src="<?php echo base_url();?>assets/images/slide3.png" data-holder-rendered="true">
        </div>
      </div>
      <a data-slide="prev" role="button" href="#carousel-example-generic" class="left carousel-control">
        <span aria-hidden="true" class="arrow_cust"><img src="<?php echo base_url();?>assets/images/arrow-left.png" class="img-responsive"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a data-slide="next" role="button" href="#carousel-example-generic" class="right carousel-control">
        <span class="arrow_cust1" aria-hidden="true"><img src="<?php echo base_url();?>assets/images/arrow-right.png" class="img-responsive"></span>
    	<span class="sr-only">Next</span>
      </a>
    </div>

</div>
</div>



<div class="contact">
<div class="container">
	<div class="col-lg-8">
    <div class="col-lg-12">
    <a id="contact"><h4>CONTACT US</h4></a>
    <p>Don’t be shy, drop us an email.</p>
    </div>
    <div class="col-lg-6">
    <input type="text" class="form-control" placeholder="Your Name *">
    </div>
    <div class="col-lg-6">
    <input type="text" class="form-control" placeholder="Your E-mail *">
    </div>
    <div class="col-lg-12">
    <textarea role="8" class="form-control" placeholder="Your Message *"></textarea>
    </div>
    <div class="col-lg-12">
    <button class="btn yellow_btn col-lg-4" type="button">SEND</button>
    </div>
    
    </div>
    
    <div class="col-lg-4 email_section">
    	<h5><i><img src="<?php echo base_url();?>assets/images/email.png"> </i> EMAIL ADDRESS</h5>
        <a>lunchmatcher@demo.com</a>
    </div>
    <div class="col-lg-12">
    <footer class="cus_ftr">
    <hr>
    <p>© 2015 <span>LUNCH MATCHER</span></p>
    </footer>
    </div>
</div>
</div>




<!-- Modal -->
<div class="modal fade bs-example-modal-sm in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header my_title">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">SIGNUP TO BE NOTIFIED</h4>
      </div>
      <div class="modal-body">
        <form name="sign_up" id="sign_up"  method="post">                
        <div class="form-heading">
        <p style="text-align:center;">We will notify you by email when APP is gone Live !!</p>
        <hr class="dotted">                      
        </div> 
		
		<div class="form-heading " id="success_msg" style="display:none">
        <p style="text-align:center;">Your registration has been completed successfully !!</p>
        <hr class="dotted">                      
        </div>
		<div class="form-heading " id="error_msg" style="display:none">
        <p style="text-align:center;">This email id is already registered!!</p>
        <hr class="dotted">                      
        </div>		
		 
		 <div class="form-heading " id="error_msg2" style="display:none; color:#FF0000">
        <p style="text-align:center;">This linkedin account is already registered!!</p>
        <hr class="dotted">                      
        </div>
		
        <div class="form-body cust_form">
        
        <div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">First Name</span>
        </div>
        <div class="col-lg-12">
		 <input name="firstname" id="firstname" value="<?php echo $_POST['firstname'];?>" class="form-control js-placeholder" placeholder="First Name" data-bvalidator="required" data-bvalidator-msg="Please enter your First Name">
        </div>
        <div class="clearfix"></div>
        </div>
        
		
		<div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">Last Name</span>
        </div>
        <div class="col-lg-12">
		 <input name="lastname" id="lastname" value="<?php echo $_POST['lastname'];?>" class="form-control js-placeholder" placeholder="Last Name" data-bvalidator="required" data-bvalidator-msg="Please enter your Last Name">
        </div>
        <div class="clearfix"></div>
        </div>
		
		
        <div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">Email address</span>
        </div>
        <div class="col-lg-12">
		<input name="email" id="email" value="<?php echo $_POST['email'];?>" class="form-control js-placeholder" placeholder="Email" data-bvalidator="email,required" data-bvalidator-msg="">
        </div>
        <div class="clearfix"></div>
        </div>
        
        <div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">Country</span>
        </div>
        <div class="col-lg-12">
		<!-- <input name="country" id="country" value="<?php //echo $_POST['country'];?>" class="form-control js-placeholder" placeholder="Country" data-bvalidator="required" data-bvalidator-msg="Please enter your Country">-->
		 
		 <select name="country" id="country" class="form-control js-placeholder">
		 <?php foreach($countrylist as $country){?>
		 <option value="<?php echo $country->country_name?>" <?php if($country->country_name=='United Arab Emirates'){ ?> selected="selected" <?php } ?>><?php echo $country->country_name?></option>
		 <?php }?>
		 </select>
		 
		 
        </div>
        <div class="clearfix"></div>
        </div>
        
        <div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">Work industry</span>
        </div>
        <div class="col-lg-12">
<!--        <input class="form-control" id="industry" name="industry" value="<?php echo $_POST['industry'];?>" placeholder="Industry">
-->		

		<select name="industry" id="industry" class="form-control" >
			<option>Accounting</option>
			<option>Airlines/Aviation</option>
			<option>Alternative Dispute Resolution</option>
			<option>Alternative Medicine</option>
			<option>Animation</option>
			<option>Apparel & Fashion</option>
			<option>Architecture & Planning</option>
			<option>Arts and Crafts</option>
			<option>Automotive</option>
			<option>Aviation & Aerospace</option>
			<option>Banking</option>
			<option>Biotechnology</option>
			<option>Broadcast Media</option>
			<option>Building Materials</option>
			<option>Business Supplies and Equipment</option>
			<option>Capital Markets</option>
			<option>Chemicals</option>
			<option>Civic & Social Organization</option>
			<option>Civil Engineering</option>
			<option>Commercial Real Estate</option>
			<option>Computer & Network Security</option>
			<option>Computer Games</option>
			<option>Computer Hardware</option>
			<option>Computer Networking</option>
			<option>Computer Software</option>
			<option>Construction</option>
			<option>Consumer Electronics</option>
			<option>Consumer Goods</option>
			<option>Consumer Services</option>
			<option>Cosmetics</option>
			<option>Dairy</option>
			<option>Defense & Space</option>
			<option>Design</option>
			<option>Education Management</option>
			<option>E-Learning</option>
			<option>Electrical/Electronic Manufacturing</option>
			<option>Entertainment</option>
			<option>Environmental Services</option>
			<option>Events Services</option>
			<option>Executive Office</option>
			<option>Facilities Services</option>
			<option>Farming</option>
			<option>Financial Services</option>
			<option>Fine Art</option>
			<option>Fishery</option>
			<option>Food & Beverages</option>
			<option>Food Production</option>
			<option>Fund-Raising</option>
			<option>Furniture</option>
			<option>Gambling & Casinos</option>
			<option>Glass, Ceramics & Concrete</option>
			<option>Government Administration</option>
			<option>Government Relations</option>
			<option>Graphic Design</option>
			<option>Health, Wellness and Fitness</option>
			<option>Higher Education</option>
			<option>Hospital & Health Care</option>
			<option>Hospitality</option>
			<option>Human Resources</option>
			<option>Import and Export</option>
			<option>Individual & Family Services</option>
			<option>Industrial Automation</option>
			<option>Information Services</option>
			<option>Information Technology and Services</option>
			<option>Insurance</option>
			<option>International Affairs</option>
			<option>International Trade and Development</option>
			<option>Internet</option>
			<option>Investment Banking</option>
			<option>Investment Management</option>
			<option>Judiciary</option>
			<option>Law Enforcement</option>
			<option>Law Practice</option>
			<option>Legal Services</option>
			<option>Legislative Office</option>
			<option>Leisure/Travel & Tourism</option>
			<option>Libraries</option>
			<option>Logistics and Supply Chain</option>
			<option>Luxury Goods & Jewelry</option>
			<option>Machinery</option>
			<option>Management Consulting</option>
			<option>Maritime</option>
			<option>Marketing and Advertising</option>
			<option>Market Research</option>
			<option>Mechanical or Industrial Engineering</option>
			<option>Media Production</option>
			<option>Medical Devices</option>
			<option>Medical Practice</option>
			<option>Mental Health Care</option>
			<option>Military</option>
			<option>Mining & Metals</option>
			<option>Motion Pictures and Film</option>
			<option>Museums and Institutions</option>
			<option>Music</option>
			<option>Nanotechnology</option>
			<option>Newspapers</option>
			<option>Nonprofit Organization Management</option>
			<option>Oil & Energy</option>
			<option>Online Media</option>
			<option>Outsourcing/Offshoring</option>
			<option>Package/Freight Delivery</option>
			<option>Packaging and Containers</option>
			<option>Paper & Forest Products</option>
			<option>Performing Arts</option>
			<option>Pharmaceuticals</option>
			<option>Philanthropy</option>
			<option>Photography</option>
			<option>Plastics</option>
			<option>Political Organization</option>
			<option>Primary/Secondary Education</option>
			<option>Printing</option>
			<option>Professional Training & Coaching</option>
			<option>Program Development</option>
			<option>Public Policy</option>
			<option>Public Relations and Communications</option>
			<option>Public Safety</option>
			<option>Publishing</option>
			<option>Railroad Manufacture</option>
			<option>Ranching</option>
			<option>Real Estate</option>
			<option>Recreational Facilities and Services</option>
			<option>Religious Institutions</option>
			<option>Renewables & Environment</option>
			<option>Research</option>
			<option>Restaurants</option>
			<option>Retail</option>
			<option>Security and Investigations</option>
			<option>Semiconductors</option>
			<option>Shipbuilding</option>
			<option>Sporting Goods</option>
			<option>Sports</option>
			<option>Staffing and Recruiting</option>
			<option>Supermarkets</option>
			<option>Telecommunications</option>
			<option>Textiles</option>
			<option>Think Tanks</option>
			<option>Tobacco</option>
			<option>Translation and Localization</option>
			<option>Transportation/Trucking/Railroad</option>
			<option>Utilities</option>
			<option>Venture Capital & Private Equity</option>
			<option>Veterinary</option>
			<option>Warehousing</option>
			<option>Wholesale</option>
			<option>Wine and Spirits</option>
			<option>Wireless</option>
			<option>Writing and Editing</option>
		</select>
        </div>
        <div class="clearfix"></div>
        </div>
        
        <div class="cust_frm_mg">
        <div class="col-lg-12">
        <span class="form-label">Experience level</span>
        </div>
        <div class="col-lg-12">
        <select class="form-control" name="level" id="level">
        <option>0-5 Years</option>
        <option>5-10 Years</option>
        <option>10-15 Years</option>
        <option>More than 15 Years</option>
        </select>
        </div>
        <div class="clearfix"></div>
        </div>
        
        <div class="cust_frm_mg">
        <div class="col-lg-12"><center>
        <button type="submit" name="sign_up" class="btn btn-default btn_yellow">SIGN UP</button> 
		</center>
        </div>
        <div class="clearfix"></div>
        </div>
        
        </div>                                 
        </form>
        <div class="clear-form"></div>
      </div>
      <div class="clearfix"></div>
	  
	  
      <div class="modal-footer">
	  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5b1.js"></script>
	<script type="text/javascript" src="http://platform.linkedin.com/in.js">
    api_key:<?php echo $Api_key;?> 
    authorize: true
	scope: r_basicprofile r_emailaddress r_fullprofile
	</script>
	<script type="text/javascript">

    function onLinkedInLoad() {
        var s=IN.UI.Authorize().place();      
        IN.Event.on(IN, "auth", function () { onLogin(); });
        IN.Event.on(IN, "logout", function () { onLogout(); });
    }

    function onLogin() {
          //  IN.API.Profile("me").result(displayResult);
			IN.API.Profile("me").fields("id,firstName,lastName,headline,emailAddress,mainAddress,phoneNumbers,pictureUrl,public-profile-url,industry,location:(name)").result(displayResult);
    }  
    function displayResult(profiles) {
        member = profiles.values[0];
        var phs="";
        var auth_id =member.id;
        var emailAddress= member.emailAddress;
        var lastName= member.lastName;
        var firstName= member.firstName;
		var headline=member.headline;
		var picture_url=member.pictureUrl;
		var industry=member.industry;
		var location=member.location.name;
		
        var json = JSON.stringify(profiles);
			 $.ajax({
				type:"post",
				url:"<?php echo base_url()?>home/addLinkedin",
				beforeSend: function(){
					$(".background_loader").show();
				},
				data:{'auth_id':auth_id,'emailAddress':emailAddress,'lastName':lastName,'firstName':firstName,'headline':headline,'picture_url':picture_url,'location':location,'industry':industry},
				success:function(data){
					if(data=='success')
					{
						$("#success_msg").show();
						$("#error_msg2").hide();
						$(".cust_form").hide();
						$(".linkedin").hide();
						//$(this).find('form')[0].reset();
						$("#sign_up").get(0).reset();						
						window.setTimeout(function(){
							window.location.reload();
						}, 3000);
						
					}else{
						$("#error_msg2").show();
						$("#success_msg").hide();
						
					}
					$(".background_loader").hide();
					//$(".modal-content").html(data);
				
				}
			});
		return true;
		//IN.API.Profile(member.id).fields(fields).result(resultCallback)
        //alert(member.id + " Hello " +  member.firstName + " " + member.lastName);
    }  
</script>


        <a class="btn btn-default linkedin" href="javascript:void(0)" onClick="onLinkedInLoad()"> <i class="fa fa-linkedin modal-icons"></i> Sign In with Linkedin </a>
        <!--<a class="btn btn-default linkedin" href="<?php //echo base_url();?>linkedin" > <i class="fa fa-linkedin modal-icons"></i> Sign In with Linkedin </a>-->
      </div>
    </div>
  </div>
</div>









<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
<link href="<?php echo base_url();?>assets/css/bvalidator.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/jquery.bvalidator.js"></script>
</body>
</html>
<script>

	$(document).ready(function(){
		
		$('.navlink').click(function(e) {

		  e.preventDefault();
		  var ashval = $(this).attr('rel');
		  var top = $(this).data('top');
		  location.href="#"+ashval;
		  window.scroll(0,top);
		});

		var options = {
			singleError: true,
       		showCloseIcon: false,					
		};
		
		$("#sign_up").bValidator(options);
	
	
	});
	$('form').submit(function(event) {
		event.preventDefault();
		 //  var payload = $(this).serialize();
		  // do the ajax request with "data" option set to payload
   			var pattern = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;
		  	var firstname=$("#firstname").val();
		  	var lastname=$("#lastname").val();
			var country=$("#country").val();
			var email=$("#email").val();
			var industry=$("#industry").val();
			var level=$("#level").val();
			if(firstname!='' && lastname!='')
			{
				if (pattern.test(email)) {
				 $.ajax({
					type:"post",
					url:"<?php echo base_url()?>home/add",
					beforeSend: function(){
						$(".background_loader").show();
					},
					data:{'firstname':firstname,'lastname':lastname,'email':email,'country':country,'industry':industry,'level':level},

					success:function(data){
						if(data=='success')
						{
							$("#error_msg").hide();
							$(".cust_form").hide();
							$(".linkedin").hide();

							$("#success_msg").show();
							//$(this).find('form')[0].reset();
							$("#sign_up").get(0).reset();
							window.setTimeout(function(){
								window.location.reload();
							}, 3000);
							
							
						}else{
							$("#error_msg").show();
							$("#success_msg").hide();
							$( "#email" ).addClass( "borderred" );
						}
						$(".background_loader").hide();
					//$(".modal-content").html(data);
					
					}
				});
				}else{
					$( "#email" ).addClass( "borderred" );
				}
			}
		  
	});

$('#myModal').on('shown.bs.modal', function() {
   // $('#sign_up').formValidation('resetForm', true);
	$(this).find('form')[0].reset();
	$( "#email" ).removeClass( "borderred" );
	$("#error_msg").hide();
});
</script>