<?php
/*
Plugin Name: UTM Code Reporting
Plugin URI: http://www.firsthandfoundation.org
Description: A plugin to have a UTM-Code-Reporting on UTM codes.
Version: 1.0.1
Author: Darrell Agee
Author URI: https://www.firsthandfoundation.org
*/

// Register the plugin admin page
yourls_add_action( 'plugins_loaded', 'report_init' );
function report_init() {
    yourls_register_plugin_page( 'UTM-Code-Reporting', 'UTM Report', 'report_display_page' );
}

// The function that will draw the admin page
function report_display_page() {
  ?>
    <head>
      <link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/tablesorter.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
      <script src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/js/jquery.tablesorter.min.js" type="text/javascript"></script>
      <script src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/js/jquery.tablesorter.widgets.min.js" type="text/javascript"></script>
      <script src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/js/jquery.tablesorter.pager.min.js" type="text/javascript"></script>
      <script src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/js/main.js" type="text/javascript"></script>
      <link rel="stylesheet" href="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/style.css" type="text/css"/>
    </head>
    <div class="pager">
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/first.png" class="first"/>
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/prev.png" class="prev"/>
      <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/next.png" class="next"/>
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/last.png" class="last"/>
      <select class="pagesize" title="Select page size">
        <option selected="selected" value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <select class="gotoPage" title="Select page number"></select>
    </div>
    <div class="tblResponsive">
      <table id="utm_table" class="tblSorter" cellpadding="0" cellspacing="1">
        <thead>
          <tr class="tablesorter-headerRow">
            <th id="utm_table_head_shorturl" data-column="0" class="tablesorter-header">
              Short URL
            </th>
            <th id="utm_table_head_title" data-column="1" class="tablesorter-header">
              Title
            </th>
            <th id="utm_table_head_source" data-column="2" class="tablesorter-header">
              Source
            </th>
            <th id="utm_table_head_medium" data-column="3" class="tablesorter-header">
              Medium
            </th>
            <th id="utm_table_head_campaign" data-column="4" class="tablesorter-header">
              Campaign
            </th>
            <th id="utm_table_head_date" data-column="5" class="tablesorter-header">
              Date
            </th>
            <th id="utm_table_head_clicks" data-column="6" class="tablesorter-header">
              Clicks
            </th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th colspan="7" date-column="0">
              <br>
            </th>
          </tr>
        </tfoot>
        <tbody>
          <?php
            global $ydb;
            $utm_codes = ["utm_source=","utm_medium=","utm_campaign="];
            $count = 1;
            $table_url = YOURLS_DB_TABLE_URL;
            $table_results = $ydb->get_results("SELECT keyword, url, title, timestamp, clicks FROM `$table_url`;");
            foreach($table_results as $table_result)
            {
              $keyword = yourls_sanitize_string($table_result->keyword);
              $long_url = stripslashes($table_result->url);
              $title = $table_result->title;
              $source = checkUTMCode($long_url, $utm_codes[0]);
              $medium = checkUTMCode($long_url, $utm_codes[1]);
              $campaign = checkUTMCode($long_url, $utm_codes[2]);
              $timestamp = date("M d, Y H:i", strtotime($table_result->timestamp));
              $clicks = $table_result->clicks;
              echo '<tr><td>' . $keyword . '</td>
              <td><a href="' . $long_url . '" target="_blank">' . $title . '</a></td>
              <td>' . $source . '</td><td>' . $medium . '</td>
              <td>' . $campaign . '</td><td>' . $timestamp . '</td>
              <td>' . $clicks . '</td></tr>';
              $count++;
            }
          ?>
        </tbody>
      </table>
    </div>

    <br>
    <div class="pager">
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/first.png" class="first"/>
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/prev.png" class="prev"/>
      <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/next.png" class="next"/>
      <img src="<?php yourls_site_url(); ?>/user/plugins/UTM-Code-Reporting/css/images/last.png" class="last"/>
      <select class="pagesize" title="Select page size">
        <option selected="selected" value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <select class="gotoPage" title="Select page number"></select>
    </div>
  <?php
}

function checkUTMCode($long_url, $utm_code)
{
  if (strpos($long_url, $utm_code) !== false)
  {
    $value = returnUTMCode($long_url, $utm_code);
  } else {
    $value = '';
  }

  return $value;
}

function returnUTMCode($long_url, $utm_code)
{
  $querySeperator = '&';
  $firstPosition = strpos($long_url, $utm_code) + strlen($utm_code);
  if (strpos($long_url, $querySeperator, $firstPosition) !== false)
  {
    $lastPosition = strpos($long_url, $querySeperator, $firstPosition);
    $value = preg_replace("/\+/", " ", substr($long_url, $firstPosition, $lastPosition - $firstPosition));
  } else {
    $value = preg_replace("/\+/", " ", substr($long_url, $firstPosition));
  }

  return $value;
}
?>
