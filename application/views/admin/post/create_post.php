<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/post/trendy_save_link" enctype= "multipart/form-data">
    <div class="row" id="Title">
        <div class="col-lg-12"><legend>create Post</legend></div>
    </div>
    <div class="row form-group">
        <div class="col-lg-12">

            <table class="table table-bordered table-hover">
                <tr><td> Post</td>
                    <td><input type="text" name="post_name" required id="post_name" size="60"></td></tr>
                <tr>
                    <td colspan="2" align="center"><button class="btn btn-info" type="submit" name="submit" >Submit</button></td>
                </tr>

            </table>
        </div>

    </div> 
</form>