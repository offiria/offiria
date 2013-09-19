<?php
require_once('./models/database.php');
require_once('./models/config.php');
require_once('./helper/files/copy.php');
require_once('./helper/files/file.php');
require_once('./helper/user.php');
require_once('./helper/timezone.php');

// !important: turn on for debugging
ini_set('display_errors', 'off');

// PATH 
define('FRAMEWORK_SQL_FILEPATH', realpath('./sql/framework.sql'));
define('OFFIRIA_SQL_FILEPATH', realpath('./sql/offiria.sql'));
define('CONFIG_FILEPATH', realpath('./default/configuration.php'));
define('HTACCESS_FILEPATH', realpath('./default/htaccess.txt'));
define('PATCHES_SQL_FILEDIR', realpath('./sql/patches/'));
define('SOURCE_FILEDIR', realpath('./source/'));
define('INSTALLATION_DIR', realpath('../'));

define('DS', DIRECTORY_SEPARATOR);
$filesInvolved = array(CONFIG_FILEPATH,
					   HTACCESS_FILEPATH,
					   INSTALLATION_DIR);
$stuckFiles = FileHelper::filterStuckFiles($filesInvolved);

session_start();
// to passed the default permission
$_SESSION['default_permission'] = substr(sprintf('%o', fileperms(INSTALLATION_DIR)), -4);

// since we need to output error in the same file, we need to put conditional skip on certain function
$canProceed = true;
$errorMessage = '';
$isSuccessful = false;
// this is when some settings need to be cleared before installation can proceed
$cannotProceed = (count($stuckFiles) > 0) ? true : false;

// safety stop
if (file_exists(INSTALLATION_DIR.DS.'configuration.php')) {
	//exit('Configuration file exist. If new installation is needed, please remove ' . CONFIG_FILEPATH . '. Exiting..');
	$isSuccessful = true;
	$cannotProceed = true;
}

if ($_GET) {
	$db_uname		  = $_GET['root_username'];
	$db_pw			  = $_GET['root_pw'];
	$db_name		  = $_GET['database_name'];
	$db_prefix		  = $_GET['database_prefix'];
	$db_host  		  = (!empty($_GET['database_host'])) ? $_GET['database_host'] : 'localhost';
	$admin_uname	  = $_GET['admin_username'];
	$admin_pw		  = $_GET['admin_pw'];
	$admin_email 	  = $_GET['admin_email'];
	$sitename		  = $_GET['sitename'];
	$default_timezone = $_GET['default_timezone'];

	// to passed variables
	$variables = $_SERVER['QUERY_STRING'];
	// to prevent timezone error
	date_default_timezone_set($default_timezone);

    // configuration handler
	$config = new ConfigManager(CONFIG_FILEPATH);

	// #1: Create framework database
	$dbo = DBConnection::connect(NULL, $db_host, $db_uname, $db_pw);
	$database = new DBManager($db_prefix);

	if ($dbo == NULL) {
		$canProceed = false;
		$errorMessage = 'Wrong username/password combination for Database Configuration';
	}

	// action handler 
	if (@$_GET['remove_database']) {
		if ($canProceed && !$database->dropDatabase($dbo, $db_name)) {
			$canProceed = false;
			$errorMessage = 'Failed to remove database';
			// exit('Failed to remove database');
		}
	}

	// #2: install offiria database
	if ($canProceed && !$database->createDatabase($dbo, $db_name)) {
		$link = '';
		$link .= '<p>';
		$link .= 'This is a fresh install of Offiria, <b>'. $db_name . '</b> database will be removed (this action is not reversible)<br />';

		$query = "?$variables&remove_database=true";
		$link .= '<a href="'.$query.'">Confirm</a>';
		$link .= '</p>';
		$errorMessage = $link;
		$canProceed = false;
	}

	// update connection based on the new database created
	$dbo = DBConnection::connect($db_name, $db_host, $db_uname, $db_pw);

	if ($canProceed && !$database->populateDatabase($dbo, FRAMEWORK_SQL_FILEPATH)) {
		$canProceed = false;
		$errorMessage = 'Failed to install framework';
		// exit('Failed to install framework');
	}

	if ($canProceed && !$database->populateDatabase($dbo, OFFIRIA_SQL_FILEPATH)) {
		$canProceed = false;
		$errorMessage = 'Failed to install Offiria';
		// exit('Failed to install Offiria');
	}

	if ($canProceed && !$database->installPatches($dbo, PATCHES_SQL_FILEDIR)) {
		$canProceed = false;
		$errorMessage = 'Failed to update patches';
		// exit('Failed to update patches');
	}

	// #3: Install Apache configuration
	// UNIX like system need to remove as some overwrite are not supported
	if (file_exists(INSTALLATION_DIR.DS.'.htaccess')) {
		unlink(INSTALLATION_DIR.DS.'.htaccess');
	}
	if ($canProceed 
		&& !copy(HTACCESS_FILEPATH, INSTALLATION_DIR.DS.'.htaccess')) {
			$canProceed = false;
			$errorMessage = 'Failed to install Apache configuration files';
	}

	// #4: Install admin
	if ($canProceed && !$database->installAdmin($dbo, $admin_uname, $admin_pw, $admin_email, $config)) {
		$canProceed = false;
		$errorMessage = 'Failed to install admin user';
		// exit('Failed to install admin user');
	}

	// #5: Modify configuration files
	$config->modify('user', $db_uname);
	$config->modify('password', $db_pw);
	$config->modify('mailfrom', $admin_email);
	$config->modify('sitename', $sitename);
	$config->modify('db', $db_name);
	$config->modify('host', $db_host);
	$config->modify('dbprefix', $db_prefix);
	$config->modify('default_timezone', $default_timezone);
	$config->modify('log_path', INSTALLATION_DIR.DS.'log');
	$config->modify('tmp_path', INSTALLATION_DIR.DS.'tmp');
	if ($canProceed && !$config->save()) {
		$canProceed = false;
		$errorMessage = 'Failed to save configuration files';
		// exit('Failed to save configuration files');
	}

	if ($canProceed && !$config->copy(INSTALLATION_DIR.DS.'configuration.php')) {
		$canProceed = false;
		$errorMessage = 'Failed to install configuration files. You need to manually save the following text to a file named: <b>' . INSTALLATION_DIR.DS.'configuration.php</b>';
		$configurationFiles = file_get_contents(CONFIG_FILEPATH);
		// exit('Failed to install configuration files');
	}

	// COMPLETE
	DBConnection::close_connection();
	$isSuccessful = true;
}
?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-min.css" />
<link rel="stylesheet" type="text/css" href="assets/css/installer.css" />
<link rel="stylesheet" href="assets/css/validationEngine.jquery.css" type="text/css"/>
</head>

<title>Offiria Installation</title>

<body>
	
	<div class="container">
		
		<form id="form-installer" class="form-horizontal" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		
		<div class="row">
			
			<div class="span1">&nbsp;</div>
			
			<div class="span10 installer-container">
				
				<div class="row">
					<div class="span10 header-section">
						<div class="header-content">
							<h1>Offiria Installation</h1>
						</div>
					</div>
				</div>

				<?php if(!$isSuccessful): ?>
				<div class="row">
					<div class="span10">
						<div class="installation-notes">
							<?php 
								$recommendedExt = 'mysql, mysqli, xml, zlib';
								$recommended = explode(', ', $recommendedExt);
								$installed = get_loaded_extensions();
								$missingExt = array();
								for ($i = 0; $i < count($recommended); $i++)
								{
									if (!in_array(trim($recommended[$i]), $installed))
									{
										$missingExt[] = $recommended[$i];
									}
								}							
							?>
							<div class="version-notes">
								Version installed : PHP <?php echo phpversion();?>; MySQL <?php echo mysql_get_server_info();?> ( <b>Recommended Version : PHP 5.3.13, MySQL 5.0.95</b> )
							</div>
							<?php if(count($missingExt) > 0) : ?>
							<div class="missing-ext">
								Missing Recommended Extensions (<strong><?php echo count($missingExt);?></strong>): <?php echo count($missingExt) > 0 ? "<span class=\"label\">".implode('</span><span class="label">', $missingExt)."</span>": 'None';?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="row">
					<div class="span10 intro-section clearfix">
						<div class="row">
							<div class="span1">
								<div class="i-content intro"></div>
							</div>
							<div class="span9">
								<div class="main-content">
									<?php if ($isSuccessful && $cannotProceed): // already installed ?>
									<p>
										You have already installed Offiria. If you want to reinstall, remove <?php echo CONFIG_FILEPATH; ?>
									</p>
									<?php elseif (!$isSuccessful && !$cannotProceed): // the initial page to load ?>
									<h2>Welcome.</h2>
									<p>
										Kindly fill the necessary information to proceed with Offiria installation. <br />
									</p>
									<?php elseif ($cannotProceed) : // if theres a permission issue with files ?>
									<div class="alert alert-error">
										<p>
											The following directory <b>needs</b> to be given writable permissions or assigned to a proper usergroup (<i>temporarily</i>). You will be reminded to revert the permissions by the end of the installation. <br />
											Once modified, refresh this page to continue.
										</p>
										<ul>
											<?php foreach ($stuckFiles as $file): ?>
											<li><?php echo $file; ?>
											<?php endforeach; ?>
										</ul>
									</div>
									<?php elseif (!$canProceed || !$isSuccessful): // if theres an error with installation ?>
									<div id="system-message-container">
										<div class="alert alert-error">
											<?php echo $errorMessage; ?>
										</div>
										<?php if (!empty($configurationFiles)): // if configuration.php need to be set manually ?>
										<textarea>
											<?php echo $configurationFiles; ?>
										</textarea>
										<?php endif; ?>
									</div>
									<?php else: // if successfully complete the installation ?>
									<div id="system-message-container">
										<div class="alert alert-success">
											The Offiria installation is <b>complete</b>. It is highly recommended that you revert the permissions of <b><?php echo
											INSTALLATION_DIR; ?></b> to default (<?php echo $_SESSION['default_permission']; ?>) or (0755).
											<br />
											<br />
											<a href="../">Click here</a> to go to your new Offiria site.
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if (!$cannotProceed && !$isSuccessful): // if fails to install or initial install form displayed ?>
				<div class="row">
					<div class="span10 site-section clearfix">
						<div class="row">
							<div class="span1">
								<div class="i-content site"></div>
							</div>
							<div class="span9">
								<div class="main-content">
									<h2>Database Configuration</h2>
									<span class="title-desc">Please fill in the server configuration.</span>

									<div class="theform">
										<div class="control-group">
											<label class="control-label" for="domain">Database Name</label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="database_name" value="<?php echo @$_GET['database_name']; ?>" />
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="domain">User Name</label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="root_username" value="<?php echo @$_GET['root_username']; ?>" />
											</div>
										</div>
										
										<div class="control-group">	
											<label class="control-label" for="domain">Password</label>
											<div class="controls">
												<input class="span3" type="password" name="root_pw" />
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="domain">Database Host</label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="database_host" value="<?php echo (!empty($_GET['database_name'])) ? $_GET['database_name'] : 'localhost'; ?>" />
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="domain">Table Prefix <br /><span class="title-desc">(Default to 'off_')</span></label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="database_prefix" value="<?php echo (!empty($_GET['database_prefix'])) ? $_GET['database_prefix'] : 'off_'; ?>" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span10 offiria-section clearfix">
						<div class="row">
							<div class="span1">
								<div class="i-content offiria"></div>
							</div>
							<div class="span9">
								<div class="main-content">
									<h2>Offiria Configuration.</h2>
									<span class="title-desc">Please fill in the site configuration.</span>

									<div class="theform">
										<div class="control-group">
											<label class="control-label" for="domain">Admin User Name</label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="admin_username" value="<?php echo	@$_GET['admin_username']; ?>" />
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="domain">Admin Password</label>
											<div class="controls">
												<input class="span3 validate[required]" type="password" id="admin_pw" name="admin_pw" />
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="domain">Re-type Password</label>
											<div class="controls">
												<input class="span3 validate[required,equals[admin_pw]]" type="password" name="admin_pw" />
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="domain">Admin Email</label>
											<div class="controls">
												<input class="span3 validate[required,custom[email]]" type="text" name="admin_email" value="<?php echo @$_GET['admin_email']; ?>"/>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="domain">Site Name</label>
											<div class="controls">
												<input class="span3 validate[required]" type="text" name="sitename" value="<?php echo @$_GET['sitename']; ?>" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="domain">Timezone</label>
											<div class="controls">
												<select name="default_timezone">
													<?php foreach (getTimezoneOption() as $key=>$value) : ?>
													<?php
													  if (@date_default_timezone_get()) {
														  $default_timezone = @date_default_timezone_get();
														  $selected = ($default_timezone == $key) ? 'selected="selected"' : '';
													  }
													?>
													<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span10 footer-section clearfix">
						<div class="row">
							<div class="span10">
								<div class="footer-content">
									<?php /*<div class="pull-left">
											By creating an account, you agree with our <a href="https://offiria.com/terms-of-service/" target="_blank">Terms of Service</a> and <a href="https://offiria.com/privacy-policy/" target="_blank">Privacy Policy</a>.
									</div> */ ?>
									<div class="pull-right">
										<input class="btn btn-info" type="submit" id="submit" value="Install Offiria">
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<?php endif; // end condition for $cannotProceed ?>								
				<div class="span1">&nbsp;</div>
				
			</div>
		</form>
		</div>
	</div>
	<script src="assets/js/jquery-1.7.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="assets/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
	<script src="assets/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#form-installer").validationEngine('attach', {
			onValidationComplete: function(form, status) {
				if (status == true) {
					$('#form-installer #submit').attr('disabled', 'disabled');
					form.validationEngine('detach');
					form.submit();
				}
				return true;
			}
		});
	});
	</script>
</body>
</html>