<script src="<?= base_url() ?>assets/js/ajaxupload.3.5.js"></script>

<div class="container"> <div class="row">
        <div class="col-lg-12">
            <legend><span class="form-group">
            <label class="col-sm-2 control-label" for="textinput"></label>
            </span><?php echo isset($brand['brand']) ? $brand['brand'] : ''; ?></legend>
</div> 

<div class="clearfix">&nbsp;</div>

        <div class="col-md-12">

            <form class="form-horizontal" role="form" action="" method="post">
             <input type="hidden" name="city_name_old" value="<?php echo isset($brand['city']) ? $brand['city'] : ''; ?>" />
                <fieldset>

                    <!-- Form Name -->


                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">City Name</label>

                        <div class="col-sm-10">

                            <input type="text" name="city_name" value="<?php echo isset($brand['city']) ? $brand['city'] : ''; ?>" placeholder="city"  required class="form-control" >
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Country Name</label>
                        <div class="col-sm-10">
                          
                          
                           <select name="country_name" style="cursor: auto" class="form-control" >

                        <option value="<?php echo $brand['id'] ?>" ><?php echo $brand['name'] ?></option>
                                                                      
                                   <?php foreach($country_list as $cnt_data) {
								   ?>
    <option value="<?php echo $cnt_data->id ?>"><?php echo $cnt_data->name ?></option>
  <?php }?>
                      
                        </select>
                          
                        </div>
                        
                </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">State</label>
                        <div class="col-sm-10">
                        
                        
                        
                           <select name="state" style="cursor: auto" class="form-control" >

                        <option value="<?php echo $brand['state'] ?>" ><?php echo $brand['state'] ?></option>
                                                                      
                                   <?php foreach($get_state as $state_data) {
								   ?>
    <option value="<?php echo $state_data->state ?>"><?php echo $state_data->state ?></option>
  <?php }?>
                      
                        </select>

                        </div>
                    </div>
                </div>

            <input type="hidden" name="id" value="<?php echo isset($brand['id']) ? $brand['id'] : ''; ?>" />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="pull-right">
                                <button type="submit" name="sub" class="btn btn-info">Save</button>


                            </div>
                        </div>
                    </div>
                    </div>
                </fieldset>

        </div><!-- /.row -->



    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


    <script>
        function view_popup(member_id, popup)
        {
            $("#myModal").html("");
            $.ajax({
                type: "post",
                url: "<?php echo base_url(); ?>admin/users/" + popup,
                data: {'member_id': member_id},
                success: function (data) {
                    $("#myModal").html(data);
                    //alert(data);
                }
            });
        }
        function view_popup_comming(member_id, popup)
        {
            $("#myModal").html("");
            $.ajax({
                type: "post",
                url: "<?php echo base_url(); ?>admin/users/" + popup,
                data: {'member_id': member_id},
                success: function (data) {
                    $("#myModal").html(data);
                    //alert(data);
                }
            });
        }

    </script>