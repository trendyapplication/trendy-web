<script src="<?= base_url() ?>assets/js/ajaxupload.3.5.js"></script>

<div class="container"> <div class="row">
        <div class="col-lg-12">
            <legend>Brand Details - <?php echo isset($brand['brand']) ? $brand['brand'] : ''; ?></legend>
        </div> 

        <div class="clearfix">&nbsp;</div>

        <div class="col-md-12">

            <form class="form-horizontal" role="form" action="" method="post">
                <fieldset>

                    <!-- Form Name -->


                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Brand Name</label>

                        <div class="col-sm-10">

                            <input type="text" name="name" value="<?php echo isset($brand['name']) ? $brand['name'] : ''; ?>" placeholder="Occasionname" class="form-control" >
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Created On</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" style="cursor: auto" value="<?php echo isset($brand['inserted_on']) ? date(getConfigValue('date_format'), strtotime($brand['inserted_on'])) : ''; ?>" placeholder="Created on" class="form-control"  readonly="readonly" >

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Updated On</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" style="cursor: auto" value="<?php echo isset($brand['updated_on']) ? date(getConfigValue('date_format'), strtotime($brand['updated_on'])) : ''; ?>" placeholder="Updated on" class="form-control"  readonly="readonly" >

                        </div>
                    </div>



                    </div>
                    </div>






                    <input type="hidden" name="id" value="<?php echo isset($brand['id']) ? $brand['id'] : ''; ?>" />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="pull-right">
                                <button type="submit" name="sub" class="btn btn-info">Update</button>


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