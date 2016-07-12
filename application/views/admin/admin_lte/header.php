<?php
$url_segment = $this->uri->segment(2);

//echo $url_segment;
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $TITLE ? $TITLE : 'Trendy Service'; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo $template_url; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- font Awesome -->
    <link href="<?php echo $template_url; ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Ionicons -->
    <link href="<?php echo $template_url; ?>/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <!-- Morris chart -->
    <link href="<?php echo $template_url; ?>/css/morris/morris.css" rel="stylesheet" type="text/css">
    <!-- jvectormap -->
    <link href="<?php echo $template_url; ?>/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
    <!-- Date Picker -->
    <link href="<?php echo $template_url; ?>/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css">
    <!-- Daterange picker -->
    <link href="<?php echo $template_url; ?>/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo $template_url; ?>/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css">
    <!-- Theme style -->
    <link href="<?php echo $template_url; ?>/css/AdminLTE.css" rel="stylesheet" type="text/css">



    <link href="<?php echo base_url('assets/css'); ?>/adminstyle.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url('assets/js'); ?>/jquery.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('assets'); ?>/js/jquery.bxslider.min.js"></script>

    <link href="<?php echo base_url('assets'); ?>/css/jquery.bxslider.css" rel="stylesheet" />

</head>

<body class="skin-black" onLoad="startTime()">
    <header class="header">
        <a href="<?php echo base_url() . 'admin/home'; ?>" class="logo">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <?php // if(isset($site_name)) {  echo $site_name; } else echo 'Logo OR Title'; ?>
            <img src="<?php echo base_url('assets/images'); ?>/h.png" />
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">

            <!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-right">
                <ul class="nav navbar-nav">

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <span>Administrator <i class="caret"></i></span>                            </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header bg-light-black">
                                <img src="<?php echo $template_url; ?>/img/avatar5.png" class="img-circle" alt="User Image">
                                <p>
                                    Administrator
                                    <small>Trendy Service  Administrator</small>                                    </p>
                            </li>

                            <li class="user-footer">
                                <!-- <div class="pull-left">
                                     <a href="#" class="btn btn-default btn-flat">Profile</a>
                                 </div>-->
                                <div class="pull-right">
                                    <a href="<?php echo site_url('admin/home/logout'); ?>" class="btn btn-info btn-flat">Sign out</a>                                    </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="wrapper row-offcanvas row-offcanvas-left">

        <aside class="left-side sidebar-offcanvas" style="min-height: 1519px;">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">


                <div class="user-panel">

                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li <?php if ($url_segment == 'home') { ?>class="active" <?php } ?>>
                        <a href="<?php echo base_url(); ?>admin/home">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li <?php if ($url_segment == 'users') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-users"></i> <span>User Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/users/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> User list</a></li>

                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'post') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-list"></i> <span>Post Listing </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/post/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Post list</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/post/create_post" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Create Post</a></li>
                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'category') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-briefcase"></i> <span>Category Listing </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/category/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Category list</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/category/create" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Create Category</a></li>
                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'country' or $url_segment == 'state' or $url_segment == 'state_gn') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-circle-o"></i> <span>Country & City Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/country/country_list" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Country Management</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/state/city_list" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> City Management</a></li>
                              <li><a href="<?php echo base_url(); ?>admin/state_gn/city_list" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> State Management</a></li>
                      </ul>
                  </li>

                    <li <?php if ($url_segment == 'occasion') { ?>class="active treeview" <?php } else { ?> class = "treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-laptop"></i> <span>Occasion Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/occasion/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Occasion list</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/occasion/create" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Create Occasion</a></li>
                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'review') { ?>class="active treeview" <?php } else { ?> class = "treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-windows"></i> <span>Review Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/review" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Review list</a></li>
                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'brand') { ?>class="active treeview" <?php } else { ?> class = "treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-star"></i> <span>Brand Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/brand" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Brand list</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/brand/create" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Create Brand</a></li>
                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'report') { ?>class="active treeview" <?php } else { ?> class = "treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-paper-plane"></i> <span>User Report Management </span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>admin/report" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>User Report list</a></li>

                        </ul>
                    </li>
                    <li <?php if ($url_segment == 'error') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-anchor"></i> <span>Error Reporting</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url('admin/error'); ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Report List</a></li>
                        </ul>
                    </li>
                    
                    
                    
                    
                    
                     <li <?php if ($url_segment == 'error') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-anchor"></i> <span>CMS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url('admin/faq_list'); ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>CMS List</a></li>
                        </ul>
                    </li>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <li <?php if ($url_segment == 'configuration') { ?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                        <a href="javascript:void(0);">
                            <i class="fa fa-cog lg"></i> <span>General Settings </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url('admin/configuration'); ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Settings</a>
                            </li>
                            <li><a href="<?php echo base_url('admin/configuration/changePassowrd'); ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Change Password</a></li>
                            <li><a href="<?php echo base_url('admin/configuration/emailconfig'); ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Email Config</a></li>
                        </ul>
                    </li>



                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>


        <aside class="right-side">

            <section class="content-header">
                <h1>
                    Trendy Service
                    <small>Control panel</small>
                </h1>
                <ol class="breadcrumb">
                    <?php
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();

                    switch ($controller) {
                        case "app" :
                            $controller = 'App customization';
                            break;
                        case "contacts":
                            $controller = 'Contact Us';
                            break;
                    }

                    switch ($method) {
                        case "index" :
                            $method = $controller;
                            break;
                        case "lists":
                            $method = 'Manage' . ' ' . $controller;
                            break;
                        case "progress":
                            $method = 'In progress';
                            break;
                        default :
                            $method = str_replace('_', ' ', $method);
                    }
                    ?>
                    <li>
                        <a href="<?php echo base_url() . 'admin/' . $this->router->fetch_class(); ?>"><i class="fa fa-dashboard"></i> 
                            <?php echo ucwords($controller); ?>
                        </a>
                    </li>
                    <li class="active"><?php echo ucwords($method); ?></li>
                </ol>
            </section>

            <div id="status"></div>
            <?php if ($this->session->flashdata('message')) { ?>
                <section class="alert alert-success">
                    <?php echo $this->session->flashdata('message'); ?>
                </section>
            <?php } ?>

            <?php if ($this->session->flashdata('error')) { ?>
                <section class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </section>
            <?php } ?>

            <section class="content">