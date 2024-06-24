<?php 
   $uri = $_SERVER['REQUEST_URI'];
   $pathName = parse_url($uri, PHP_URL_PATH);
   $currentParentActiveID = "";
   foreach($GLOBALS['menus'] as $menu) {
      if($pathName === $menu['link'] || (strpos($pathName, $menu['link']) === 0)) {
         $currentParentActiveID = $menu['id'];
      }
      if($menu['has_child'] && $menu['is_parent']) {
         foreach($GLOBALS['subMenus'][$menu['id']] as $subMenu) {
            if($subMenu['link'] === $pathName || (strpos($pathName, $subMenu['link']) === 0)) {
               $currentParentActiveID = $subMenu['parent_id'];
               break;
            }
         }
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= $page['title'] ?></title>
      <link rel="stylesheet" href="/assets/css/datepicker.css">
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
      <link rel="stylesheet" href="/assets/css/datatables.min.css" />
      <link rel="stylesheet" href="/assets/plugins/jquery-ui/jquery-ui.min.css" />
      <link href="/assets/css/select2.min.css" rel="stylesheet" />
      <link href="/assets/css/cropper.min.css" rel="stylesheet">
      <script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/dt-2.0.5/r-3.0.2/datatables.min.js"></script>
   </head>
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">
        <!-- LOADING SPINNER -->
        <?php require_once('./views/components/loading.php'); ?>
         <nav class="main-header navbar navbar-expand color-bg-green-1 text-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-white"></i></a>
               </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
               <li class="nav-item-dropdown">
                  <a href="#" class="nav-link" data-toggle="dropdown" style="">  
                     <?php
                        // Get the current host
                        $host = $_SERVER['HTTP_HOST'];

                        // Parse the host to extract the hostname and port
                        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                        $protocol = $isSecure ? 'https://' : 'http://';
                        $hostParts = parse_url('http://' . $host);
                        $hostname = $hostParts['host'];
                        $port = isset($hostParts['port']) ? $hostParts['port'] : null;

                        // Determine if the port should be displayed
                        $displayPort = ($port && $port != 80 && $port != 443);

                        // Construct the final host string
                        $finalHost = $protocol . $hostname;
                        if ($displayPort) {
                           $finalHost .= ':' . $port;
                        }
                     ?>
                     <span class="mr-2 d-inline-block text-white"><?= $_SESSION['user_credential']['fullname'] ?></span>
                     <img src="<?= $finalHost . '/' . $_SESSION['user_credential']['picture'] ?>" alt="" width="40" class="rounded-circle" style="margin-top: -7px;">
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                     <a href="#" class="dropdown-item">
                        <div class="d-flex justify-content-center">
                           <img src="<?= $finalHost . '/' . $_SESSION['user_credential']['picture'] ?>" alt="" class="w-50 rounded-circle" />
                        </div>
                     </a>
                     <div class="dropdown-divider mt-3"></div>
                     <a href="/profile/edit-profile" class="dropdown-item">
                        <i class="fa fa-wrench mr-2"></i>
                        <span>Edit Profil</span>
                     </a>
                     <a href="/auth/logout" class="dropdown-item">
                        <i class="fa fa-door-open mr-2"></i>
                        <span>Logout</span>
                     </a>
                  </div>
               </li>
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
                     <?php foreach($GLOBALS['menus'] as $menu): ?>
                           <li class="nav-item <?= $currentParentActiveID == $menu['id'] && $menu['has_child'] && $menu['is_parent'] ? 'menu-open' : '' ?>">
                              <a href="<?= $menu['link'] ?>" class="nav-link <?= $currentParentActiveID === $menu['id'] ? 'active' : '' ?>">
                                 <i class="nav-icon <?= $menu['icon'] ?>"></i>
                                 <p><?= $menu['name'] ?>
                                    <?php if($menu['has_child'] == 1 && $menu['is_parent'] == 1): ?>
                                    <i class="right fas fa-angle-left"></i>
                                    <?php endif ?>
                                 </p>
                              </a>
                              <?php if($menu['has_child'] == 1 && $menu['is_parent'] == 1): ?>
                                 <ul class="nav nav-treeview">
                                    <?php foreach($GLOBALS['subMenus'][$menu['id']] as $subMenu): ?>
                                       <li class="nav-item">
                                          <a href="<?= $subMenu['link'] ?>" class="nav-link <?= strpos($pathName, $subMenu['link']) === 0 ? 'active' : '' ?>">
                                             <i class="far fa-circle nav-icon"></i>
                                             <p><?= $subMenu['name'] ?></p>
                                          </a>
                                       </li>
                                    <?php endforeach; ?>
                                 </ul>
                              <? endif; ?>
                           </li>
                        <?php endforeach; ?>
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
               <b>Version</b> 1.0.0
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
      <!-- AdminLTE App -->
      <script src="/assets/js/adminlte.js"></script>
      
      <script src="/assets/js/sweetalert2.js"></script>
      <script src="/assets/js/function.js"></script>
      <script src="/assets/js/request.js"></script>
      <script src="/assets/js/validator.js"></script>   
      <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
      <script src="/assets/js/mdb.min.js"></script>
      <script>
         <?php 
               $sess = new Utils\Session();
               $warningFlash = $sess->getFlash('warning');
               $successFlash = $sess->getFlash('success');
               $dangerFlash = $sess->getFlash('danger');
            ?>
            <?php if(!empty($warningFlash)): ?>
               showAlert("<?= $warningFlash ?>", 'warning');
            <?php endif; ?>
            
            <?php if(!empty($dangerFlash)): ?>
               showAlert("<?= $dangerFlash ?>", 'error');
            <?php endif; ?>
            
            <?php if(!empty($successFlash)): ?>
               showAlert("<?= $successFlash ?>", 'success');
            <?php endif; ?>
      </script>
   </body>
</html>