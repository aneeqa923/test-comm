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
	        			<i class="fa fa-info-circle"></i> About Us
	        		</h1>
	        		
	        		<h3><b>Welcome to BuzzWizz</b></h3>
	        		<p>
	        			BuzzWizz is your premier destination for quality products at competitive prices. 
	        			Founded with a vision to revolutionize online shopping, we bring you a curated 
	        			selection of products ranging from fashion accessories to electronics, all in one place.
	        		</p>
	        		
	        		<h4><b>Our Mission</b></h4>
	        		<p>
	        			Our mission is to provide customers with an exceptional shopping experience through:
	        		</p>
	        		<ul>
	        			<li>High-quality products at affordable prices</li>
	        			<li>Fast and reliable delivery service</li>
	        			<li>Secure payment options including PayFast and Cash on Delivery</li>
	        			<li>Outstanding customer support</li>
	        		</ul>
	        		
	        		<h4><b>Why Choose Us?</b></h4>
	        		<p>
	        			<b>Quality Assurance:</b> Every product in our catalog is carefully selected and 
	        			verified for quality. We partner with trusted suppliers to ensure you receive 
	        			only the best.
	        		</p>
	        		<p>
	        			<b>Secure Shopping:</b> Your security is our priority. We use industry-standard 
	        			encryption to protect your personal information and offer multiple secure payment 
	        			options.
	        		</p>
	        		<p>
	        			<b>Customer First:</b> We believe in putting our customers first. Our dedicated 
	        			support team is always ready to assist you with any questions or concerns.
	        		</p>
	        		
	        		<h4><b>Our Team</b></h4>
	        		<p>
	        			BuzzWizz was created by a team of passionate software engineers and e-commerce 
	        			experts including Aneeqa Mehboob, Zujaaj, and Faama. Together, we combine technical 
	        			expertise with a deep understanding of customer needs to deliver an outstanding 
	        			online shopping platform.
	        		</p>
	        		
	        		<p class="text-muted" style="margin-top: 30px;">
	        			<i>Thank you for choosing BuzzWizz. Happy Shopping!</i>
	        		</p>
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