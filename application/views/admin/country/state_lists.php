<script language="javascript">


    function toggleStatus(id, status, statusdiv) {
        $.ajax({
            url: "<?php echo base_url() ?>country/ajaxstatus",
            data: {id: id, status: status, statusdiv: statusdiv},
            success: function (result) {
                window.location.reload();

            }

        });

    }
    function toggleblock(id, block, blockdiv) {
        $.ajax({
            url: "<?php echo base_url() ?>country/ajaxblock",
            data: {id: id, block: block, blockdiv: blockdiv},
            success: function (result) {
                //alert(result);return false;
                window.location.reload();

            }

        });

    }



    $(document).ready(function () {
// function to delete masteraction 
        $("#deleteSel").click(function () {
            var chkCnt = $(".chk:checked").length;
            if (chkCnt == 0) {
                //alert("Please select at least one user.!");
                showMessageBox('Select atleast one item', 'danger');
                return false;
            }

            if (confirm('Are you sure to delete the selected occasio(s)?')) {
                $("#userMasterForm").attr("action", "<?php echo base_url("country/index"); ?>");
                $("#bulkaction_list").val('delete_list');
                $("#actions").val("delete");
                $("#userMasterForm").submit();
                return true;
            }

        });


// function to delete product 
        $("#bulkaction").change(function () {
            var chkCnt = $(".chk:checked").length;
            var action = $("#bulkaction").val();

            if (chkCnt == 0) {
                alert("Please select at least one user.!");
                return false;
            }
            else if (action == 'delete') {
                if (confirm('Are you sure to delete the selected user(s)?')) {
                    $("#userMasterForm").attr("action", "<?php echo base_url("country/bulkAction"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                }
                else {
                    return false;
                }

            }
            else if (action == 'active' || action == 'inactive') {
                if (confirm('Are you sure to change status of the selected user(s)?')) {
                    $("#userMasterForm").attr("action", "<?php echo base_url("country/bulkAction"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                }
                else {
                    return false;
                }

            }
            else {
                alert("Please specify any action.!");
                return false;
            }
        });

        // filter function
        $("#filter_button").click(function () {
            $("#userMasterForm").submit();
            return true;
        });
        // End : filter function

        //check all
        $('#select_all').click(function () {
            //alert("s");
            if ($('#select_all').is(':checked'))
                $('.chk').prop('checked', true);
            else
                $('.chk').prop('checked', false);
        });
        // end check all



        //check all
        $('.sortlink').click(
                function () {
                    var feild = $(this).attr('rel');
                    var title = $(this).attr('title');

                    $(this).removeAttr('title');
                    if (title == 'ASC') {
                        $(this).attr('title', 'DESC');
                    }
                    else {
                        $(this).attr('title', 'ASC');
                    }
                    $('#order_by_field').val(feild);
                    $('#order_by_value').val($(this).attr('title'));
                    $("#userMasterForm").attr("action", "<?php echo base_url("country/city_list"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                });



        // END: check all

        //Change Limit of pagination
        $(document).on('change', '#limit', function () {
            $("#userMasterForm").attr("action", "<?php echo base_url("admin/state/city_list"); ?>");
            $("#userMasterForm").submit();
            return true;
        });


        $('#btn_search').click(
                function () {
                    $("#userMasterForm").attr("action", "<?php echo base_url("admin/state/city_list"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                });

        $(document).on('change', '#status', function () {
            $("#userMasterForm").attr("action", "<?php echo base_url("country/city_list"); ?>");
            $("#userMasterForm").submit();
            return true;
        });
        // END: Change Limit of pagination

    });




</script>


<?php //echo http_build_query( $this->input->get() ); ?>

<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>state/city_list">
    <input type="hidden" name="order_by_field" id="order_by_field" value="<?php //echo $_REQUEST['order_by_field'];            ?>" />
    <input type="hidden" name="order_by_value" id="order_by_value" value="<?php //echo $_REQUEST['order_by_value'];             ?>" />
    <input type="hidden" name="actions" id="actions" value="" />
    <div class="row" id="Title">
        <div class="col-lg-12">
          <legend>City</legend>
        </div>
    </div>

    <div class="row" <?php if (!$message && !$error) { ?>style="display:none" <?php } ?> id="msg_head">
        <?php if ($message) { ?>
            <div class="alert alert-success col-lg-12 col-offset-1"><?php echo $message; ?><!--danger-info-->
                <button data-dismiss="alert" class="close" type="button">x</button>

            </div><?php } if ($error) { ?>
            <div class="alert alert-danger col-lg-12 col-offset-1"><?php echo $error; ?><!--danger-info-->
                <button data-dismiss="alert" class="close" type="button">x</button>
            </div><?php } ?>
    </div>



    <div class="row form-group">
        <div class="col-lg-6">

            <div class="input-group">

                <input type="text" class="form-control" placeholder="Keyword Search" onFocus="if (this.value == 'Keywords')
                            this.value = ''" onBlur="if (this.value == '')
                                        this.value = ''" name="key" id="key" value="<?php
                       if ($key != '') {
                           echo $key;
                       } else {
                           echo '';
                       }
                       ?>">
                <span class="input-group-btn btn_search">
                    <button class="btn btn-info " id="btn_search" type="button">Go!</button>
                </span>
                <span class="input-group-btn btn_search">
                    <a href="javascript:" onClick="window.location.href = '<?php echo site_url() ?>/admin/state/city_list'" class="blu_btn">
                        <button class="btn btn-default" type="button">Reset</button></a>
                </span>
                  <span class="input-group-btn btn_search" >
                    <a href="javascript:" onclick="window.location.href = '<?php echo site_url() ?>/admin/state/edit_statedetails'" class="blu_btn">
                        <button class="btn btn-default" type="button">Add</button></a>
                </span>

            </div><!-- /input-group -->


        </div>


    </div>
    <?php //print_r($userlist); ?>
    <table class="table table-bordered table-hover"> 
        <thead>

            <tr>
                 <th  width="53">Delete</th>
              <th  width="67">Edit</th>
                 
              <th  width="128">City</th>
               <th  width="128">Country</th>
                <th  width="128">State</th>
              
        </thead>
        <tbody>
            <?php if (sizeof($userlist) > 0) : //echo "<pre>"; print_r($userlist); exit;  ?>
                <?php foreach ($userlist as $val) : ?>
                    <tr>
                        <td width="53"><center><a href="<?php echo site_url(); ?>/admin/state/delete_state/<?php echo $val->id; ?>?limit=<?php echo $limit; ?>&per_page=<?php echo $per_page; ?>" onClick="return confirm('Are you sure you want to delete? If this country deleted , all datas belonging to this country will be deleted ');"><i class="glyphicon glyphicon-trash" title="Delete"></i></a></center></td>
               
              <td width="67"><center>

                    <a href="<?php echo site_url(); ?>/admin/state/edit_statedetails/<?php echo $val->id; ?>" class="link1">
                        <i class="glyphicon glyphicon-edit" title="Edit Details"></i></a></center>
                </td>

                <td><?= $val->city; ?></td>
                 <td><?= $val->name; ?></td>
                 <td><?= $val->state; ?></td>

                

                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td  colspan="8">No records...</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="row" id="Table footer">
        <div class="col-lg-12 ">
            <div class="col-offset-1 col-lg-2 pull-right">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-map-marker"></i> <?php //echo $limit;             ?>
                    </span>
                    <select name="limit" id="limit" class="form-control" >

                        <option value="5" <?php if ($limit == 5) { ?> selected="selected"<?php } ?> >5</option>
                        <option value="10" <?php if ($limit == 10) { ?> selected="selected"<?php } ?> >10</option>
                        <option value="20" <?php if ($limit == 20) { ?> selected="selected"<?php } ?> >20</option>
                        <option value="50" <?php if ($limit == 50) { ?> selected="selected"<?php } ?>>50</option>
                        <option value="100" <?php if ($limit == 100) { ?> selected="selected"<?php } ?>>100</option>
                        <option value="all" <?php if ($limit == 'all') { ?> selected="selected"<?php } ?>>ALL</option>
                    </select>
                </div> 
            </div>

            <div class="col-offset-1 col-lg-10">
                <div class="input-group">
                    <ul class="pagination pull-right" style="margin:0px;">
                        <?php
//$page_count = ceil($tot_prop / $limit);
                        echo $this->pagination->create_links();
                        ?>
                    </ul>
                </div>
            </div>


        </div>
    </div>

</form>
