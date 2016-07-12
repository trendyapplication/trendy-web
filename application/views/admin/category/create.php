
<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/category/create_cat" enctype= "multipart/form-data">
    <div class="row" id="Title">
        <div class="col-lg-12"><legend>create category</legend></div>
    </div>
    <div class="row form-group">
        <div class="col-lg-12">

            <table class="table table-bordered table-hover">
                <tr><td>Category Name</td>
                    <td><input type="text" name="ocname" required id="ocname" size="60"></td></tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <select name="gender" id="gender" onchange="gender_change()">
                            <option value="0">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Parent</td>
                    <td>
                        <select name="parent" id="parent" required>
                            <option value="0">None</option>

                        </select>
                    </td>
                </tr>


                <tr>
                    <td>Description</td>
                    <td><textarea name="desc" required></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><button class="btn btn-info" type="submit" name="submit" >Submit</button></td>
                </tr>

            </table>
        </div>

    </div> 
</form>
<script>
    function gender_change() {
        var gender = $("#gender").val();
        $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>admin/category/gender_cat",
            data: {gender: gender},
            success: function (data) {
                $("#parent").empty();
                $("#parent").html(data);
               
//                                                                alert(data);
            }
        });
    }
</script>