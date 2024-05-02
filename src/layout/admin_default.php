<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= $page['title'] ?></title>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Tempusdominus Bootstrap 4 -->
      <link rel="stylesheet" href="/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- iCheck -->
      <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- JQVMap -->
      <link rel="stylesheet" href="/assets/plugins/jqvmap/jqvmap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="/assets/css/adminlte.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="/assets/plugins/daterangepicker/daterangepicker.css">
      <!-- summernote -->
      <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="/assets/css/global.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/jq-3.7.0/dt-2.0.5/r-3.0.2/datatables.min.css" />
      <!-- <script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script> -->
      <script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/dt-2.0.5/r-3.0.2/datatables.min.js"></script>
   </head>
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">
        <!-- LOADING SPINNER -->
        <?php require_once('./views/components/loading.php'); ?>
         <!-- Preloader -->
         <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="/assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
            </div> -->
         <!-- Navbar -->
         <nav class="main-header navbar navbar-expand color-bg-green-1 text-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-white"></i></a>
               </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            </ul>
         </nav>
         <!-- /.navbar -->
         <!-- Main Sidebar Container -->
         <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/admin" class="brand-link text-center text-decoration-none">
               <!-- <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
               <h2 class="brand-image img-circle inika-regular elevation-3 ml-3 mt-1 text-center">P</h2>
               <span class="brand-text inika-regular text-decoration-none">PERPUS-KU</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
               <!-- Sidebar Menu -->
               <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <?php
                        // LOOP Parent Menu
                        foreach($GLOBALS['menus'] as $menu):
                           $parentNavItemClass = "nav-item";
                           $parentNavLinkClass = "nav-link";
                           if($menu['is_parent'] == 1) {
                              if(isset($page['parent'])) {
                                 if(strtolower($page['parent']) == strtolower($menu['name'])) {
                                    $parentNavItemClass .= " menu-is-opening menu-open";
                                    $parentNavLinkClass .= " active";
                                 }
                              }
                           }
                           else if($menu['is_parent'] == 0) {
                              if(strtolower($page['title']) == strtolower($menu['name'])) {
                                 $parentNavItemClass .= " menu-is-opening menu-open";
                                 $parentNavLinkClass .= " active";
                              }
                           }
                     ?>
                        <li class="<?= $parentNavItemClass ?>">
                           <a href="<?= $menu['link'] ?>" class="<?= $parentNavLinkClass ?>">
                           <i class="<?= $menu['icon'] ?>"></i>
                              <p>
                                 <?= $menu['name'] ?>
                                 <?= $menu['is_parent'] ? '<i class="right fas fa-angle-left"></i>' : '' ?>
                              </p>
                           </a>
                     <?php
                        if($menu['is_parent']):
                     ?>                     
                        <ul class="nav nav-treeview">

                     <?php
                           // LOOP Sub Menu
                           foreach($GLOBALS['subMenus'] as $subMenu): 
                              if($subMenu['id_menu'] != $menu['id']): continue; endif
                     ?>
                           <li class="nav-item">
                              <a href="<?= $subMenu['link'] ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], $subMenu['link']) === 0 ? "active" : "" ?>">
                                 <i class="<?= $subMenu['icon'] ?>"></i>
                                 <p><?= $subMenu['name'] ?></p>
                              </a>
                           </li>
                     <?php
                           endforeach;
                     ?>
                        </ul>
                     <?php
                        endif;
                     ?>
                        </li>
                     <?php
                        endforeach;
                     ?>
                  </ul>
               </nav>
               <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
         </aside>
         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <div class="content-header">
              <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= $page['title']; ?></h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <?= isset($page['parent']) ? "<li class=\"breadcrumb-item\">" . $page['parent'] . "</li>" : ""; ?> 
                          <!-- <li class="breadcrumb-item"></li> -->
                          <li class="breadcrumb-item active"><?= $page['title']; ?></li>
                        </ol>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </div>
            <section class="content">
               <?php require_once($viewFile); ?>
            </section>
         </div>
         <!-- /.content-wrapper -->
         <footer class="main-footer">
            <strong>Copyright &copy; <?= date('Y'); ?> <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
               <b>Version</b> 3.2.0
            </div>
         </footer>
         <!-- Control Sidebar -->
         <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
         </aside>
         <!-- /.control-sidebar -->
      </div>
      <!-- ./wrapper -->
      <!-- jQuery UI 1.11.4 -->
      <script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
      <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
      <!-- <script>
         $.widget.bridge('uibutton', $.ui.button)
      </script> -->
      <!-- Bootstrap 4 -->
      <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- ChartJS -->
      <script src="/assets/plugins/chart.js/Chart.min.js"></script>
      <!-- Sparkline -->
      <script src="/assets/plugins/sparklines/sparkline.js"></script>
      <!-- JQVMap -->
      <script src="/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
      <script src="/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
      <!-- jQuery Knob Chart -->
      <script src="/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
      <!-- daterangepicker -->
      <script src="/assets/plugins/moment/moment.min.js"></script>
      <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
      <!-- Summernote -->
      <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
      <!-- overlayScrollbars -->
      <script src="/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
      <!-- AdminLTE App -->
      <script src="/assets/js/adminlte.js"></script>
      
      <script src="/assets/js/sweetalert2.js"></script>
      <script src="/assets/js/function.js"></script>
      <script src="/assets/js/request.js"></script>
      <script src="/assets/js/validator.js"></script>    
   </body>
</html>