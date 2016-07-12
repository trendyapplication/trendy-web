<script src="<?= base_url() ?>assets/js/ajaxupload.3.5.js"></script>

<script>
    $(function () {
//        alert("df");
        var btnUpload = $('#im2');
        var id = <?= $this->uri->segment(4, 0) ?>;
        new AjaxUpload(btnUpload, {
            action: '<?= base_url() ?>admin/occasion/uploadImage',
            name: 'ocimage',
            data: {id: id},
            onSubmit: function (file, ext) {
                if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
                    status.text('Only JPG, PNG or GIF files are allowed');
                    return false;
                }
//                        alert("sdhh");
            },
            onComplete: function (file, response) {
                // status.text('');
                if (response.indexOf("error") > -1) {

                } else {

                    var d = new Date();
                    $("#img_pic").attr('src', '<?= base_url() ?>uploads/thumbs/occasion/' + response.trim() + "?" + d.getTime());
                    $("#imname").val(response.trim());
                    var _html = '<div class="alert alert-block green" style="color:green;font-size:14px;font-family: Lato, sans-serif;">Image has been successfully updated!<br/></div>';
                    $(".success_msgs").html(_html);
                    $('.success_msgs').delay(5000).fadeOut();
                }
            }
        });

    });
</script>
<div class="container"> <div class="row">
        <div class="col-lg-12">
            <legend>Occasion Details <?php echo isset($occasion->name) ? $occasion->name : ''; ?></legend>
        </div> 
        <div class="col-lg-12 tabfour">
            <div class="col-lg-6">
                <div class="col-lg-12">
                    <div id="im2">
                        <?php if ($occasion['image'] != '') { ?>
                            <img src="<?php echo base_url() . 'uploads/thumbs/occasion/' . $occasion['image']; ?>" id="img_pic" width="165" height="130" />
                        <?php } else { ?>
                            <img src="<?php echo base_url(); ?>assets/images/no_image.png" height="" width="" />
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 mrtp">

                <div class="row">
                </div>





            </div>
        </div>
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-12">

            <form class="form-horizontal" role="form" action="" method="post">
                <fieldset>

                    <!-- Form Name -->


                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Occasion Name</label>

                        <div class="col-sm-10">
                            <input type="hidden" value="<?= $occasion['image'] ?>" name="imageName" id="imname">
                            <input type="text" name="name" value="<?php echo isset($occasion['name']) ? $occasion['name'] : ''; ?>" placeholder="Occasionname" class="form-control" >
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Created On</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" style="cursor: auto" value="<?php echo isset($occasion['inserted_on']) ? date(getConfigValue('date_format'), strtotime($occasion['inserted_on'])) : ''; ?>" placeholder="Created on" class="form-control"  readonly="readonly" >

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Updated On</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" style="cursor: auto" value="<?php echo isset($occasion['updated_on']) ? date(getConfigValue('date_format'), strtotime($occasion['updated_on'])) : ''; ?>" placeholder="Updated on" class="form-control"  readonly="readonly" >

                        </div>
                    </div>



                    </div>
                    </div>






                    <input type="hidden" name="id" value="<?php echo isset($occasion->occasion_id) ? $occasion->occasion_id : ''; ?>" />
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