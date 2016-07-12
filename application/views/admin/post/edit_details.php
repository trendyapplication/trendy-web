<script src="<?= base_url() ?>assets/js/ajaxupload.3.5.js"></script>
<script>
    $(function () {
//        alert("df");
        var btnUpload = $('#im2');
        var id = <?= $this->uri->segment(4, 0) ?>;
//        alert(id);
        new AjaxUpload(btnUpload, {
            action: '<?= base_url() ?>admin/post/uploadImage',
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
//                    alert(response);
                    var d = new Date();
                    $("#img_pic").attr('src', 'https://s3.amazonaws.com/trendyservice/post/' + response.trim() + "?" + d.getTime());
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
            <legend>Post Details <?php echo isset($occasion->name) ? $occasion->name : ''; ?></legend>
        </div> 
        <div class="col-lg-12 tabfour">
            <div class="col-lg-6">
                <div class="col-lg-12">
                    <div id="im2">
                        <?php if ($datas['fileNAME'] != '') { ?>
                            <img src="https://s3.amazonaws.com/trendyservice/post/<?= $datas['fileNAME']; ?>" id="img_pic" width="165" height="130" />
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

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Price </label>

                        <div class="col-sm-10">
                            <input required pattern=".*\S+.*" type="text" name="price" value="<?php echo isset($datas['price']) ? $datas['price'] : ''; ?>" placeholder="Price" class="form-control" >
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Description </label>

                        <div class="col-sm-10">
                            <textarea name="desc" required pattern=".*\S+.*"  class="form-control"  > <?= $datas['description'] ?> </textarea>
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput" >Brand </label>

                        <div class="col-sm-10">
                             <!--<div class="col-sm-10">-->
                            <select name="brand" class="form-control"   required >

                                <?php foreach ($brand as $occ) {
                                    ?>
                                    <option value="<?= $occ['id'] ?>" <?php if ($occ['id'] == $datas['brand']) { ?> selected <?php } ?>><?= $occ['brand'] ?></option>
                                <?php } ?>

                            </select>
                        <!--</div>-->
                            <!--<input type="text" name="brand" required pattern=".*\S+.*"  value="<?= $datas['brand'] ?>"  class="form-control">-->
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput" >Occasion </label>

                        <div class="col-sm-10">
                            <select name="occasion" class="form-control"   required >

                                <?php foreach ($occasion as $occ) {
                                    ?>
                                    <option value="<?= $occ['occasion_id'] ?>" <?php if ($occ['occasion_id'] == $datas['occasion_id']) { ?> selected <?php } ?>><?= $occ['name'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput" >Category </label>

                        <div class="col-sm-10">
                            <select name="category" class="form-control" required >
                                <?php foreach ($category as $cat) {
                                    ?>
                                    <option value="<?= $cat['category_id'] ?>" <?php if ($cat['category_id'] == $datas['product_type']) { ?> selected <?php } ?> ><?= $cat['name'] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput" >Gender </label>

                        <div class="col-sm-10">
                            <select name="gender" class="form-control" required >
                                <option <?php
                                if ($datas['gender'] == "male") {
                                    echo "selected";
                                }
                                ?> value="male">male</option>
                                <option <?php
                                if ($datas['gender'] == "Female") {
                                    echo "selected";
                                }
                                ?> value="Female">Female</option>
                            </select>
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput" >Created_by </label>

                        <div class="col-sm-10">
                            <input readonly type="text" value="<?= $datas['user_name'] ?>"  class="form-control">
                            <span class="alert-danger"><?php echo form_error('first_name'); ?></span>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="textinput">Created On</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" value="<?php echo isset($datas['created_on']) ? date(getConfigValue('date_format'), strtotime($datas['created_on'])) : ''; ?>" placeholder="Created on" class="form-control"  readonly="readonly" >
                            <span class="alert-danger"><?php echo form_error('email'); ?></span>
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