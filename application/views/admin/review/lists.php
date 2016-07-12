<script language="javascript">


    function toggleStatus(id, status, statusdiv) {
        $.ajax({
            url: "<?php echo base_url() ?>admin/occasion/ajaxstatus",
            data: {id: id, status: status, statusdiv: statusdiv},
            success: function (result) {
                window.location.reload();

            }

        });

    }
    function toggleblock(id, block, blockdiv) {
        $.ajax({
            url: "<?php echo base_url() ?>admin/occasion/ajaxblock",
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
                $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/index"); ?>");
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
                    $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/bulkAction"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                }
                else {
                    return false;
                }

            }
            else if (action == 'active' || action == 'inactive') {
                if (confirm('Are you sure to change status of the selected user(s)?')) {
                    $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/bulkAction"); ?>");
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
                    $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/lists"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                });



        // END: check all

        //Change Limit of pagination
        $(document).on('change', '#limit', function () {
            $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/lists"); ?>");
            $("#userMasterForm").submit();
            return true;
        });


        $('#btn_search').click(
                function () {
                    $("#userMasterForm").attr("action", "<?php echo site_url("admin/review"); ?>");
                    $("#userMasterForm").submit();
                    return true;
                });

        $(document).on('change', '#status', function () {
            $("#userMasterForm").attr("action", "<?php echo site_url("admin/review/lists"); ?>");
            $("#userMasterForm").submit();
            return true;
        });
        // END: Change Limit of pagination

    });




</script>


<?php //echo http_build_query( $this->input->get() ); ?>

<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url(); ?>admin/review/lists">
    <input type="hidden" name="order_by_field" id="order_by_field" value="<?php //echo $_REQUEST['order_by_field'];       ?>" />
    <input type="hidden" name="order_by_value" id="order_by_value" value="<?php //echo $_REQUEST['order_by_value'];        ?>" />
    <input type="hidden" name="actions" id="actions" value="" />
    <div class="row" id="Title">
        <div class="col-lg-12"><legend>Review Lists</legend></div>
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
                    <a href="javascript:" onclick="window.location.href = '<?php echo base_url() ?>admin/review/index'" class="blu_btn">
                        <button class="btn btn-default" type="button">Reset</button></a>
                </span>
            </div><!-- /input-group -->


        </div>


    </div>
    <?php // print_r($userlist); ?>
    <table class="table table-bordered table-hover"> 
        <thead>

            <tr>
                <th width="20"><center><input  type="checkbox" name="select_all"  id="select_all" ></center></th>
        <th ><span class="link_blak" ><center> Actions</center></span></th>
        <th width="70">Review</th>
        <th width="60">Product</th>
        <th width="60">Posted By</th>
        <th width="69">
            Posted On
        </th>

        </thead>
        <tbody>
            <?php if (sizeof($userlist) > 0) : //echo "<pre>"; print_r($userlist); exit;  ?>
                <?php foreach ($userlist as $val) : ?>

                    <tr>
                        <td><center><input  type="checkbox" name="sel[]" value="<?php echo $val->occasion_id; ?>"  rel="" class="chk"  /></center></td>
                <td width="5"><center><a href="<?php echo base_url(); ?>admin/review/delete/<?php echo $val->review_id; ?>?limit=<?php echo $limit; ?>&per_page=<?php echo $per_page; ?>" onclick="return confirm('Are you sure you want to delete?');"><i class="glyphicon glyphicon-trash" title="Delete"></i></a></center></td>



                <td><?= $val->review; ?></td>
                <td><a href="<?= base_url() ?>admin/post/details/<?=$val->post_id ?>" target="_blank">View Product</a></td>

                <td><?= $val->user_name ?></td>

                <td><?php echo date(getConfigValue('date_format'), strtotime($val->reviewed_on)); ?>
                </td>

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
                            <i class="glyphicon glyphicon-map-marker"></i> <?php //echo $limit;        ?>
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

<!-- Modal 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>

<script>
    function view_preferevce(occasion_id)
    {
        $("#myModal").html("");
        $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>admin/users/preferences",
            data: {'occasion_id': occasion_id},
            success: function (data) {
                $("#myModal").html(data);
                //alert(data);
            }
        });
    }
    function view_feedback(occasion_id)
    {
        $("#myModal").html("");
        $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>admin/occasion/feedback",
            data: {'occasion_id': occasion_id},
            success: function (data) {
                $("#myModal").html(data);
                //alert(data);
            }
        });
    }

</script>
<script>
    $(document).ready(function () {
        $('.full_link').click(function () {
            window.location = $(this).attr('href');
            return false;
        });
    });
</script>