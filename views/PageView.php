<?php
abstract class PageView implements View
{
  protected $title = 'CRyptonite Robotics - CRyptonite Robotics';

  function renderHead()
  { ?>
    <!DOCTYPE html>
    <html>
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $this->title ?></title>
        <link rel="icon" href="/favicon.ico">
        <meta name="theme-color" content="#222222">
        <link rel="stylesheet" type="text/css" href="/SuperCSSLoader.php">
        <script type="text/javascript" src="/scripts/jquery-3.3.1.min.js"></script>
        <!-- call all of your js files here -->
<<<<<<< HEAD
          <script type="text/javascript" src="/scripts/scouting/objects/matchdata.js"></script>
          <script type="text/javascript" src="/scripts/scouting/objects/team.js"></script>
          <script type="text/javascript" src="/scripts/scouting/objects/match.js"></script>
		  <script type="text/javascript" src="/scripts/modules/integer.js"></script>
          
=======
          <script type="text/javascript" src="/scripts/offline.js"></script>
>>>>>>> 8c638aeb35eb8a6b63c29e4a8795d4e4367ff529
          <script type="text/javascript" src="/scripts/mobile-resizer.js"></script>
          
          <script type="text/javascript" src="/scripts/objects/match.js"></script>
          <script type="text/javascript" src="/scripts/objects/team.js"></script>
        <!-- end call all of your js files -->
      </head>
    <?php 
  }

  function renderMenus()
  { ?>
    <body>
    <?php (new MenuView())->render();
  }
  
  abstract protected function renderBody();
  
  static function renderFooter()
  { ?>
      <div class="page-footer">
        <p class="page-footer-text">CRyptonite Robotics • CRHS</p>
        <p class="page-footer-text">23440 Cinco Ranch Boulevard</p>
        <p class="page-footer-text">Katy, TX 77494</p>
        <p class="page-footer-text"><a href="mailto:info@team624.org" target="_blank">info@team624.org</a> • 281-237-7000</p>
        <p class="page-footer-text page-footer-copyright">© CRyptonite Robotics. All rights reserved</p>
      </div>
    </body>
  <?php }

  function render()
  {
    $this->renderHead();
    $this->renderMenus();
    $this->renderBody();
    $this->renderFooter();
  }
}
?>
