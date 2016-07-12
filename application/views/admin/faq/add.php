

    <script src="<?php echo base_url(); ?>assets/tinymce/js/tinymce/tinymce.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/faq/src/bootstrap-wysihtml5.css" />
    <script src="<?php echo base_url(); ?>assets/faq/lib/js/wysihtml5-0.3.0.js"></script>
    <script src="<?php echo base_url(); ?>assets/faq/lib/js/jquery-1.7.2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/faq/lib/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/faq/src/bootstrap3-wysihtml5.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/ckeditor/ckeditor.js"></script>

     <style type="text/css" media="screen">
        .btn.jumbo {
            font-size: 20px;
            font-weight: normal;
            padding: 14px 24px;
            margin-right: 10px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
        }
    </style>
<form class="form-horizontal" role="form"  method="post" name="formlist"  id="formlist">

        	<fieldset>
<div class="form-group">
            <label class="col-sm-4 " for="textinput">Title</label>
            <div class="col-sm-10">
       
         
          <input type="text" name="Question" id="Question" value="<?php echo $faq_ans['Question']; ?>"  class="col-sm-12">
          </div>
           <label class="col-sm-4 " for="textinput">Description</label>
            <div class="col-sm-10">
       <!--
          <textarea class="textarea form-control" name="Answer" id="Answer" placeholder="Enter Description..." style="height:150px" class="ckeditor"><?php echo $faq_ans['Answer']; ?></textarea>
         --> 
        
        
        
        
        
         <textarea name="Answer" id="Answer"  placeholder="Enter Description..."  class="ckeditor" style="height:300px">
                                  <?php echo $faq_ans['Answer']; ?>
                                </textarea>
                             
                           
        
        
          </div>

    </div>
     <div class="col-sm-10">  

        
          <input type="hidden" name="faq_id" value="<?php echo $faq_ans['faq_id']; ?>">
         <button type="button" class="btn btn-default pull-right" onclick="location.href='<?php echo base_url()?>admin/faq_list/lists'">Cancel</button>
           <button type="button" class="btn btn-info pull-right" name="submit_btn" id="submit_btn" href="javascript:void(0);" style="margin-right:10px;" onclick="validation();">Save</button>

       </div>  
       </fieldset>
       </form>



<script>

 $(document).ready(function(){
    $('.full_link').click(function(){
        window.location = $(this).attr('href');
        return false;
    });
  });
function validation(){
//alert($('#Answer').text());
	var Question=$("#Question").val();
	//var discount=$.trim($("#Answer").val());
	//alert(discount);
	if (Question == '')
	{
		alert('Please provide a Title!');
		$("#Question").focus();
		return false;
	}
	if (CKEDITOR.instances.Answer.getData() == '')
	{
		alert('Please provide Description !');
		$("#Answer").focus();
		return false;
	}
	
	
	else
	{
			$("#formlist").submit();return true;
    }			
	}	
</script>


