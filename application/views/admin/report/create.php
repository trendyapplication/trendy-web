<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/occasion/create_occasion" enctype= "multipart/form-data">
    <div class="row" id="Title">
        <div class="col-lg-12"><legend>create occasions</legend></div>
    </div>
    <div class="row form-group">
        <div class="col-lg-12">

            <table class="table table-bordered table-hover">
                <tr><td>Occasion Name</td>
                    <td><input type="text" name="ocname" required id="ocname" size="60"></td></tr>
                <tr>
                    <td>Occasion Image</td>
                    <td><input type="file" name="ocimage" required id="ocimage" size="60"></td>                
                </tr>
                <tr>
                    <td colspan="2" align="center"><button class="btn btn-info" type="submit" name="submit" >Submit</button></td>
                </tr>

            </table>
        </div>

    </div> 
</form>