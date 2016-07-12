<script language="javascript">

//Change Limit of pagination
	$(document).on('change', '#limit', function() {
			$("#userMasterForm").attr("action", "<?php echo base_url().$this->user->root;?>/promocodes/lists");
				$("#userMasterForm").submit();return true;
	});	
	
	$('#btn_search').click(function(){	
	
		   alert("hii");
			
	});
  
	
	$(document).on('change', '#status', function() {
			$("#userMasterForm").attr("action", "<?php echo base_url().$this->user->root;?>/promocodes/lists");
			$("#userMasterForm").submit();return true;	
	});
	// END: Change Limit of pagination
	

</script>

<link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
    <?php if($this->session->flashdata('error_message')!=''){ ?>
 		<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error_message'); ?></div>
    <?php }else{ ?>
 		<div class="alert alert-danger" role="alert" style="display:none;"></div>
    <?php } ?>
    <?php if($this->session->flashdata('success_message')!=''){ ?>
 		<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success_message'); ?></div>
    <?php }else{ ?>
 		<div class="alert alert-success" role="alert" style="display:none;"></div>
    <?php } ?>
    
<form name="userMasterForm" id="userMasterForm" action="" method="post" >   

<div  class="tab_wrper" >

    <ul role="tablist" class="nav nav-tabs tab_links" id="myTabs">
      <li role="presentation" class="active tog_tab" style="width:10%;">
      <a aria-controls="dish" class="dish-tab" id="" role="tab" href="<?php echo base_url().$this->user->root;?>/promocodes/lists">Promocodes</a>
      </li>
      <li style="width:90%;">
      		
            <div class="col-lg-5 ">
     		<div class="input-group">
   			  <input type="text" class="form-control" placeholder="Keyword Search" onFocus="if(this.value=='Keywords')this.value=''" onBlur="if(this.value=='')this.value=''" name="key" id="key" value="<?php if($key != ''){ echo $key;}else{ echo '';}?>">
      				<span class="input-group-btn btn_search">
     					 <button class="btn btn-info " id="btn_search" type="button">Go!</button>
     				</span>
	  				<span class="input-group-btn btn_search">
                          <a href="javascript:" onclick="window.location.href='<?php echo base_url().$this->user->root;?>/promocodes/lists'" class="blu_btn">
                          <button class="btn btn-default" type="button">Reset</button></a>
	  				</span>
    		 </div><!-- /input-group --> 	
          
   		 </div>
     		<div class="col-lg-1 pull-right"><a href="<?php echo base_url().$this->user->root;?>/promocodes/add" class="btn btn-info">Add New</a></div>
      </li>
            
    </ul>
    
    
 
	<div class="tab-content tab_contwp dish_cat_tab" id="myTabContent">                
    	<div aria-labelledby="category-tab" id="category" class="" role="tabpanel">           
			<div class="table-responsive"> 
                <table class="table table-striped tbl_category">
                  <thead class="head_table">
                    <tr>
                      <th class="col-md-2 col-sm-2">Title</th>
                      <th class="col-md-2 col-sm-2">Promocode</th>
                      <th class="col-md-2 col-sm-2">Valid From</th>
                      <th class="col-md-2 col-sm-2">Valid To</th>
                      <th class="col-md-1 col-sm-2">Number Of Uses</th>
                      <th class="col-md-2 col-sm-2">Type</th>
                      <th class="col-md-1 col-sm-2">Amount</th>
                    </tr>
                  </thead>
                  <tbody class="table_body">
                    
                    
                    <?php 
                   
                    if(count($promolist)!=0){
                    foreach($promolist as $promo){ ?>
                    <tr id="row_<?php echo $promo['promo_id'];?>">
                     
                       <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link">
                        <?php echo $promo['title'];?>
                       </td>
                       <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link" >           
                        <?php echo $promo['promocode'];?>
                       </td>
                        <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link">
						<?php echo date("d-m-Y h:i a ", strtotime ($promo['from_date']));?></td>
                        <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link">
						<?php echo  date("d-m-Y h:i a ", strtotime ($promo['end_date']));?>
                      <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link" >
                        <?php echo $promo['uses_per_coupon'];?></td>
                       </td>
                       <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link">
                        <?php echo $promo['discount_type'];?>
                       </td>
                        <td href="<?php echo base_url().$this->user->root;?>/promocodes/add" style="cursor:pointer;" class="full_link">
                        <?php echo $promo['discount_amount'];?>
                       </td>
                      
                    
                                
                    </tr>
                    
                    <?php 	}
                        }else { ?>
                        
                      <tr>
                      <td colspan="8">
                      No Promocodes...
                      </td>
                      </tr>
                   <?php } ?>
                    
                  </tbody>
                </table>
     		</div>
   		</div>
   </div>
       <?php if(count($locationlist)!=0){?>
       <div class="row" id="Table footer">
    <div class="col-lg-12 ">
		<div class="col-offset-1 col-lg-2 pull-right">
			<div class="input-group">
			  <span class="input-group-addon">
				<i class="glyphicon glyphicon-map-marker"></i> 
			  </span>
			  <select name="limit" id="limit" class="form-control" >
					<option value="5" <?php if($limit == 5){?> selected="selected"<?php } ?> >5</option>
					<option value="10" <?php if($limit == 10){?> selected="selected"<?php } ?> >10</option>
					<option value="20" <?php if($limit == 20){?> selected="selected"<?php } ?> >20</option>
					<option value="50" <?php if($limit == 50){?> selected="selected"<?php } ?>>50</option>
					<option value="100" <?php if($limit == 100){?> selected="selected"<?php } ?>>100</option>
					<option value="all" <?php if($limit == 'all'){?> selected="selected"<?php } ?>>ALL</option>
				</select>
			</div><!-- /input-group -->
		</div>
		
		<div class="col-offset-1 col-lg-10">
				<div class="input-group">
					<ul class="pagination pull-right" style="margin:0px;">
					<?php echo $this->pagination->create_links(); ?>
					</ul>
				</div>
		</div>
    </div>
  </div>
  <?php } ?>
  
</div>
</form>


<script>
  $(document).ready(function(){
    $('.full_link').click(function(){
        window.location = $(this).attr('href');
        return false;
    });
  });


function restaurant_status(location_id,is_closed){
        
		var status=$('.restaurant_status'+location_id).attr('data-val'); 
		if($('.restaurant_status'+location_id).attr('data-val')=='Y')
			$('.restaurant_status'+location_id).attr('data-val','N');
		else
			$('.restaurant_status'+location_id).attr('data-val','Y');
			//alert(sta);
		$.ajax({
			
				type:"post",
				url:"<?php echo base_url().$this->user->root;?>/location/restaurant_status",
				data:{'location_id':location_id,'status':status},
				success:function(data){
				
					return true;
				}
			
			});
 }
	 
		
</script>
	  

<style>
.nav-tabs {
    border-bottom: 11px solid #ffffff!important;
}
.nav-tabs > li {
    margin-left: -1px!important;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #fff;
    border-image: none;
    border-style: solid;
    border-width: 1px;
    color: #555;
    cursor: default;
}
</style>