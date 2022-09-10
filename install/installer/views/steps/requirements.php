<?php $installable = true; ?>
<section id="requirements" class="center none">

    <div class="sub-section">
    
        <h3 class="section-title">
        1. Please configure PHP to match following requirements / settings:
        </h3>

        <table>
            <thead>
                <tr>
                    <th>PHP Settings</th>
                    <th>Required</th>
                    <th>Current</th>
                    <th class="status">&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><span class="fw-700">PHP Version</span></td>
                    <td>7.2.0+</td>
                    <td><?= PHP_VERSION ?></td>
                    <td class="status">
                    <?php if (version_compare(PHP_VERSION, '7.2.0') >= 0): ?>
                        <span class="icon-check"></span>
                    <?php else: ?>
                        <span class="icon-error"></span>
                        <?php $installable = false; ?>
                    <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <td><span class="fw-700">allow_url_fopen</span></td>
                    <td>On</td>
                    <td><?= ini_get("allow_url_fopen") ? "On" : "Off" ?></td>
                    <td class="status">
                        <?php if (ini_get("allow_url_fopen")): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="sub-section">
        <h3 class="section-title">
        2. Please make sure following extensions are installed and enabled:
        </h3>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Required</th>
                    <th>Current</th>
                    <th class="status">&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <?php $curl = function_exists("curl_version") ? curl_version() : false; ?>
                    <td><span class="fw-700">cURL</span></td>
                    <td>7.55.0+</td>
                    <td><?= !empty($curl["version"]) ? $curl["version"] : "Not installed"; ?></td>
                    <td class="status">
                        <?php if (!empty($curl["version"]) && version_compare($curl["version"], '7.55') >= 0): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php 
                        $openssl = extension_loaded('openssl'); 
                        if ($openssl && !empty(OPENSSL_VERSION_NUMBER)) {
                            $installed_openssl_version = get_openssl_version_number(OPENSSL_VERSION_NUMBER);
                        }
                    ?>
                    <td><span class="fw-700">OpenSSL</span></td>
                    <td>1.0.2k+</td>
                    <td><?= !empty($installed_openssl_version) ? $installed_openssl_version : "Outdated or not installed"; ?></td>
                    <td class="status">
                        <?php if (!empty($installed_openssl_version) && $installed_openssl_version >= "1.0.2k"): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php $pdo = defined('PDO::ATTR_DRIVER_NAME'); ?>
                    <td><span class="fw-700">PDO</span></td>
                    <td>On</td>
                    <td><?= $pdo ? "On" : "Off"; ?></td>
                    <td class="status">
                        <?php if ($pdo): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
	                <?php $mysqli = function_exists('mysqli_connect') ?>
                    <td><span class="fw-700">mysqli</span></td>
                    <td>On</td>
                    <td><?= $mysqli ? "On" : "Off"; ?></td>
                    <td class="status">
		                <?php if ($mysqli): ?>
                            <span class="icon-check"></span>
		                <?php else: ?>
                            <span class="icon-error"></span>
			                <?php $installable = false; ?>
		                <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php $gd = extension_loaded('gd') && function_exists('gd_info') ?>
                    <td><span class="fw-700">GD</span></td>
                    <td>On</td>
                    <td><?= $gd ? "On" : "Off"; ?></td>
                    <td class="status">
                        <?php if ($gd): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php $mbstring = extension_loaded('mbstring') && function_exists('mb_get_info') ?>
                    <td><span class="fw-700">mbstring</span></td>
                    <td>On</td>
                    <td><?= $mbstring ? "On" : "Off"; ?></td>
                    <td class="status">
                        <?php if ($mbstring): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
	                <?php $json = extension_loaded('json') && function_exists('json_decode') ?>
                    <td><span class="fw-700">json</span></td>
                    <td>On</td>
                    <td><?= $json ? "On" : "Off"; ?></td>
                    <td class="status">
		                <?php if ($json): ?>
                            <span class="icon-check"></span>
		                <?php else: ?>
                            <span class="icon-error"></span>
			                <?php $installable = false; ?>
		                <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php $exif = function_exists('exif_read_data') ?>
                    <td><span class="fw-700">EXIF</span></td>
                    <td>On</td>
                    <td><?= $exif ? "On" : "Off"; ?></td>
                    <td class="status">
                        <?php if ($exif): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="sub-section">
        <h3 class="section-title">
        3. Please make sure following files and directories are writable:
        </h3>

        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th class="status">&nbsp;</th>
                </tr>
            </thead>

            <tbody>

            <?php

            $config_writable = array_diff(scandir(MAIN_APP_PATH."/config"), array('.', '..'));
            $config_writable = array_values($config_writable);

            $is_writable = true;
            foreach ($config_writable as $config){
                if(!is_writable(MAIN_APP_PATH."/config/".$config))
                    $is_writable = false;
            }
            ?>

                <tr>
                    <td><span class="fw-700">/lite/config/*.php (all files in this directory)</span></td>
                    <td class="status">
                        <?php if ($is_writable): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php 
                        if (!file_exists(MAIN_ROOT_PATH."/assets/uploads/")) {
                            @mkdir(MAIN_ROOT_PATH."/assets/uploads/", "0777", true);
                        }
                    ?>
                    <td><span class="fw-700">/assets/uploads/</span></td>
                    <td class="status">
                        <?php if (is_writeable(MAIN_ROOT_PATH."/assets/uploads/")): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
                    <?php
                    if (!file_exists(MAIN_ROOT_PATH."/storage/videos/")) {
                        @mkdir(MAIN_ROOT_PATH."/storage/videos/", "0777", true);
                    }
                    ?>
                    <td><span class="fw-700">/storage/videos/</span></td>
                    <td class="status">
                        <?php if (is_writeable(MAIN_ROOT_PATH."/storage/videos/")): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>

                <tr>
        
                    <td><span class="fw-700">/index.php</span></td>
                    <td class="status">
                        <?php if (is_writeable(MAIN_ROOT_PATH."/index.php")): ?>
                            <span class="icon-check"></span>
                        <?php else: ?>
                            <span class="icon-error"></span>
                            <?php $installable = false; ?>
                        <?php endif ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <button <?=$installable ? '' : 'disabled'?> class="mt-40">Next</button>
</section>