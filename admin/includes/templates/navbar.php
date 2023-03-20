<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home') ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="app-nav">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang('Categories') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang('Items') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php"><?php echo lang('Members') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="comments.php"><?php echo lang('Comments') ?></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="app-nav" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Meed
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']?>"><?php echo lang('EditPro') ?> </a>
          <a class="dropdown-item" href="#"><?php echo lang('Settings') ?></a>
          <a class="dropdown-item" href="../index.php"><?php echo lang('Visit_Shop') ?></a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php"><?php echo lang('Logout') ?></a>
        </div>
      </li>
    </ul>
  </div>
</nav>