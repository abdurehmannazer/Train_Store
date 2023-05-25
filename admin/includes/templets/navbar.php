<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <button class="navbar-toggler text-white  border-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""><i class="fa-solid fa-bars"></i></span>
        </button>

        <a class="nav-link " aria-current="page" href="http://localhost/online_store_elzero">Store</a>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link" href="categories.php"><?php print lang('Categories'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="items.php"><?php print lang('Items'); ?></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="members.php"><?php print lang('Members'); ?></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="comments.php"><?php print lang('comments'); ?></a>
                </li>
            </ul>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php isset($_SESSION['admin_id_session']) ?  print $_SESSION['admin_id_session'] : print  "Admin" ; ?>
                </a>            
                <ul class="dropdown-menu ">
                    <li><a class="dropdown-item" href="members.php?do=edit&id=<?php  isset($_SESSION['admin_id_session']) ?  print $_SESSION['admin_id_session'] : "" ; ?>"><?php print lang('Edit'); ?></a></li>
                    <li><a class="dropdown-item" href="#"><?php print lang('Sett'); ?></a></li>
                    <li><a class="dropdown-item" href="logout.php"><?php print lang('Logout'); ?></a></li>
                </ul>
            </li>    
    </div>



</div>
</nav>