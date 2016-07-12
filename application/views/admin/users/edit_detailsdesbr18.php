<div class="container"> <div class="row">
        <div class="col-lg-12">
            <legend>User Details <?php echo isset($user->firs_tname) ? $user->first_name : ''; ?></legend>
        </div> 
        <div class="col-lg-12 tabfour">
            <div class="col-lg-2">
                <div class="col-lg-12">
                    <?php
                    if ($user->img_extension != '') {
                        $thumb_image_url = base_url() . "uploads/members/" . $user->user_id . "." . $user->img_extension . '?' . date("his");
                        ?>
                        <img src="<?php echo $thumb_image_url; ?>" height="" width="" />
                    <?php } else { ?>
                        <img src="<?php echo base_url(); ?>assets/images/no_image.png" height="" width="" />
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-10 mrtp">
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-12">

            <form class="form-horizontal" role="form" action="<?php echo base_url() ?>admin/users/edit" method="post">
                <fieldset>
                    <!-- Form Name -->
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">First Name</label>

                        <div class="col-sm-10">
                            <input type="text" name="names" value="<?php echo isset($user->name) ? $user->name : ''; ?>" placeholder="Firstname" class="form-control" >
                            <span class="alert-danger"><?php echo form_error('name'); ?></span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Email</label>
                        <div class="col-sm-10">
                            <?php
                            if (strpos($user->user_email, "null")) {
                                ?>
                                <input type="email" name="user_email" value="No email found" placeholder="Email" class="form-control"   >
                                <?php
                            } else {
                                ?>
                                <input type="email" name="user_email" value="<?php echo isset($user->user_email) ? $user->user_email : ''; ?>" placeholder="Email" class="form-control"   >
                            <?php } ?>
                            <span class="alert-danger"><?php echo form_error('user_email'); ?></span>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $user->user_id ; ?>" placeholder="Email" class="form-control"   >
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Is user is a bot?</label>
                        <div class="col-sm-10">
                            <select name="bot">
                                <option <?php if( $user->bot=="NO"){ echo "selected";} ?>>NO</option>
                                <option <?php if( $user->bot=="YES"){ echo "selected";} ?>>YES</option>
                            </select>
                            <span class="alert-danger"><?php echo form_error('user_email'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Location</label>
                        <div class="col-sm-10">
                            <select name="location">
                                <?php
                                foreach ($locations as $loc) {
                                    ?>

                                    <option <?php if( $user->location==$loc['id']){ echo "selected";} ?> value="<?= $loc['id']; ?>"><?= $loc['city'] ?></option>


                                <?php } ?>
                            </select>
                            <span class="alert-danger"><?php echo form_error('user_email'); ?></span>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?php echo isset($user->member_id) ? $user->member_id : ''; ?>" />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="pull-right">
                                <button type="submit" name="submitit" class="btn btn-primary">Update</button>
                                <a href="javascript:" onclick="window.location.href = '<?php echo base_url() ?>admin/users/lists'" class="blu_btn">
                                    <button type="button" class="btn btn-info">Back</button>
                                </a>

                            </div>
                        </div>
                    </div>

                </fieldset>
                 </form>
               <!--</div> /.col-lg-12--> 
                  



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