<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <style type="text/css">
            img {border-width: 0}
            * {font-family:'Lucida Grande', sans-serif;}
        </style>
        <link href="<?= base_url() ?>assets/css/uploadfilemulti.css" rel="stylesheet">
        <script src="<?= base_url() ?>assets/js/jquery-1.8.0.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.fileuploadmulti.min.js"></script>

    </head>
    <body>

        <form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/post/trendy_save_post"   enctype= "multipart/form-data">
            <div class="row" id="Title">
                <div class="col-lg-12">
                    <legend>Product Details</legend>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-12">

                    <table class="table table-bordered table-hover">
                        <tr>
                            <td>User</td>
                            <td><select name="user_id" style="cursor: auto" class="form-control" required>
                                    <?php foreach ($user as $cnt_data) { ?>  <option value="<?php echo $cnt_data['user_id'] ?>"><?php echo $cnt_data['name'] ?></option> <?php } ?>
                                </select></td>
                        </tr> 


                        <tr>
                            <td>Occasion</td>
                            <td><select name="occasion" style="cursor: auto" class="form-control"  required>
                                    <option value="0">None</option>
                                    <?php foreach ($occasion as $cnt_data) { ?>  <option value="<?php echo $cnt_data['occasion_id'] ?>"><?php echo $cnt_data['name'] ?></option> <?php } ?>
                                </select></td>
                        </tr>

                        <tr>
                            <td>gender</td>
                            <td><select onchange="chgange_cat_gender()" id="gender" name="gender" style="cursor: auto" class="form-control" required>
                                    <option value="male">Male</option>
                                    <option value="Female">Female</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Product Type</td>
                            <td>
                                <select id="cat_gender"  name="product_type" style="cursor: auto" class="form-control" required>
                                    <?php foreach ($category as $cnt_data) { ?>  <option value="<?php echo $cnt_data['category_id'] ?>"><?php echo $cnt_data['name'] ?></option> <?php } ?>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td>Product url</td>
                            <td><input type="text" name="product_url"   size="60" required></td>
                        </tr>


                        <tr>
                            <td>Price</td>
                            <td><input type="text" name="price" size="60" required></td>
                        </tr>

                        <tr>
                            <td>Brand Name</td>
                            <td><select name="brand" style="cursor: auto" class="form-control" required>
                                    <?php foreach ($brand as $cnt_data) { ?>  <option value="<?php echo $cnt_data['id'] ?>"><?php echo $cnt_data['brand'] ?></option> <?php } ?>
                                </select></td>
                        </tr>



                        <tr>
                            <td>Location</td>
                            <td><select name="country_name" style="cursor: auto" class="form-control" required>
                                    <?php foreach ($city as $cnt_data) { ?>  <option value="<?php echo $cnt_data['city'] ?>"><?php echo $cnt_data['city'] ?></option> <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea name="description" style="" required><?php echo $description ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Images</td>
                            <td><input type="file" name="file" id="file" required></td></tr>
                        <tr>

                        <tr>

                            </div>

                        <tr>
                            <td colspan="2" align="center"><button class="btn btn-info" type="submit" name="submit" >Submit</button></td>
                        </tr>

                    </table>

                </div> 
        </form>


        <script>


            function chgange_cat_gender() {
                var gender = $("#gender").val();
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url(); ?>admin/post/change_gender",
                    data: {'gender': gender},
                    success: function (data) {
                        $("#cat_gender").empty();
                        $("#cat_gender").html(data);
                        //alert(data);
                    }
                });
            }


            $(document).ready(function ()
            {

                var settings = {
                    url: "<?php echo base_url(); ?>admin/post/upload",
                    method: "POST",
                    allowedTypes: "jpg,png,gif,doc,pdf,zip,jpeg",
                    fileName: "myfile",
                    multiple: true,
                    onSuccess: function (files, data, xhr)
                    {
                        $("#status").html("<font color='green'>Upload is success</font>");

                    },
                    afterUploadAll: function ()
                    {
                    },
                    onError: function (files, status, errMsg)
                    {
                        $("#status").html("<font color='red'>Upload is Failed</font>");
                    }
                }
                $("#mulitplefileuploader").uploadFile(settings);

            });
        </script>
    </body>
</html>