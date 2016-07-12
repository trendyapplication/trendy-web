<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/brand/create" enctype= "multipart/form-data">
    <div class="row" id="Title">
        <div class="col-lg-12"><legend>create Brand</legend></div>
    </div>
    <div class="row form-group">
        <div class="col-lg-12">

            <table class="table table-bordered table-hover">
                <tr><td>Brand Name</td>
                    <td><input type="text" name="name" required id="name" size="60"></td></tr>
                
                <tr>
                    <td colspan="2" align="center"><button class="btn btn-info" type="submit" name="submit" >Submit</button></td>
                </tr>

            </table>
        </div>

    </div> 
</form>