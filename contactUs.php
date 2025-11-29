<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
   
	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
	        		
	        		<h1 style="color: #3c8dbc; font-weight: bold; margin-bottom: 25px; border-bottom: 3px solid #3c8dbc; padding-bottom: 10px;">
	        			<i class="fa fa-phone"></i> Contact Us
	        		</h1>
	        		
	        		<p>
	        			We'd love to hear from you! Whether you have a question about our products, 
	        			need assistance with an order, or just want to provide feedback, our team 
	        			is here to help.
	        		</p>
	        		
	        		<div class="row">
	        			<div class="col-md-6">
	        				<h4><b><i class="fa fa-map-marker"></i> Our Location</b></h4>
	        				<p>
	        					FAST Raggra University<br>
	        					Islamabad Campus<br>
	        					
	        				</p>
	        				
	        				<h4><b><i class="fa fa-clock-o"></i> Office Hours</b></h4>
	        				<p>
	        					Monday - Friday: 9:00 AM - 6:00 PM<br>
	        					Saturday: 10:00 AM - 4:00 PM<br>
	        					Sunday: Closed
	        				</p>
	        			</div>
	        			
	        			<div class="col-md-6">
	        				<h4><b><i class="fa fa-envelope"></i> Email Us</b></h4>
	        				<p>
	        					General Inquiries: info@buzzwizz.com<br>
	        					Customer Support: support@buzzwizz.com<br>
	        					Sales: sales@buzzwizz.com
	        				</p>
	        				
	        				<h4><b><i class="fa fa-phone"></i> Call Us</b></h4>
	        				<p>
	        					Customer Service: +92 300 1234567<br>
	        					Orders & Returns: +92 300 7654321
	        				</p>
	        			</div>
	        		</div>
	        		
	        		<hr>
	        		
	        		<h4><b>Frequently Asked Questions</b></h4>
	        		<p><b>What are your delivery times?</b></p>
	        		<p>We typically deliver within 3-5 business days for standard shipping.</p>
	        		
	        		<p><b>Do you offer international shipping?</b></p>
	        		<p>Currently, we only ship within Pakistan. International shipping is coming soon!</p>
	        		
	        		<p><b>What payment methods do you accept?</b></p>
	        		<p>We accept PayFast online payments and Cash on Delivery (COD).</p>
	        		
	        		<div class="callout callout-info" style="margin-top: 30px;">
	        			<h4><i class="fa fa-info-circle"></i> Need Immediate Help?</h4>
	        			<p>
	        				For urgent matters, please call on our customer support helpline 051-1234567
	        			</p>
	        		</div>
	        	</div>

	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>

	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>