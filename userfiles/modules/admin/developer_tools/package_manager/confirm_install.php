<?php

if (!mw_is_installed()) {

} else {
    if (!user_can_access('module.marketplace.index')) {
        return;
    }
}


$confirm_key = $require_name = $require_version = $rel_type = '';

if (isset($params['confirm_key'])) {
    $confirm_key = $params['confirm_key'];
}

if (isset($params['require_name'])) {
    $require_name = $params['require_name'];
}

if (isset($params['require_version'])) {
    $require_version = $params['require_version'];
}


if (isset($params['rel_type'])) {
    $rel_type = $params['rel_type'];
}

if (!$confirm_key) {
    return;
}

$confirm_files_count = 0;
$get_existing_files_for_confirm = cache_get($confirm_key, 'composer');

if (is_array($get_existing_files_for_confirm)) {
    $confirm_files_count = count($get_existing_files_for_confirm);
} else {
    return;
}


?>


<script>
    mw.install_composer_package_confirm_by_key = function ($confirm_key, $require_name, $require_version) {
        mw.notification.success('Installing...', 6000);

        $('#js-buttons-confirm-install-link').addClass('disabled');
        if (typeof(mw.admin) != 'undefined') {
            mw.admin.admin_package_manager.set_loading(true)
        }
        mw.spinner({element: "#js-buttons-confirm-install-link", size: 30, color: 'white'}).show()


        var values = {confirm_key: $confirm_key, require_version: $require_version, require_name: $require_name};
        $.ajax({
            url: "<?php print api_link('mw_composer_install_package_by_name'); ?>",
            type: "post",
            data: values,
            success: function (msg) {
                mw.notification.msg(msg, 3000);
                if (msg.success) {
                    mw.tools.modal.get('#js-buttons-confirm-install').remove()
                }

                if (typeof(mw.marketplace_dialog_jquery_ui) != 'undefined') {
                    mw.marketplace_dialog_jquery_ui.dialog('close');
                }

                if (typeof(getTemplateForInstallScreen) != 'undefined') {
                    getTemplateForInstallScreen();
                }

                if (typeof(mw.admin) != 'undefined') {
                    mw.admin.admin_package_manager.set_loading(false)
                    mw.admin.admin_package_manager.reload_packages_list();
                }

            },
            always: function () {

                $('#js-buttons-confirm-install-link').removeClass('disabled');

            }
        })

    }
</script>
<script>

    $(document).ready(function () {

        $('.js-show-files').on('click', function () {

            $('.js-files').toggleClass('hidden');
        });

        $(function () {
            $(".js-show-files").click(function () {
                $(this).text(function (i, text) {
                    return text === "Hide files" ? "Show files" : "Hide files";
                })
            });
        })
    });
</script>

<style>
    .js-files {
        max-height: 300px;
        overflow: scroll;
    }
</style>


<div class="js-install-package-loading-container-confirm">
    <div class="text-center">
        <div class="mb-3">
            <h5>Please confirm the installation of <br/> <strong><?php print $require_name ?></strong></h5>
            <h6>Version <?php print $require_version ?> </h6>
            <small class="text-muted"><?php print count($get_existing_files_for_confirm); ?> files will be installed</small>
        </div>

        <div>
            <?php if ($get_existing_files_for_confirm) { ?>
                <div class="js-files hidden">
                    <table class="table text-left" style="table-layout: fixed;">
                        <thead>
                        <tr>
                            <th>File location</th>
                        </tr>

                        </thead>

                        <?php
                        $i = 0;

                        foreach ($get_existing_files_for_confirm as $file) { ?>
                            <tr>
                                <td><?php print ($file) ?></td>
                            </tr>
                            <?php

                            if ($i > 1000) {
                                break;
                            }

                            $i++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div id="js-buttons-confirm-install" class="p-3 d-flex align-items-center justify-content-between">
                <a class="btn btn-secondary btn-sm" onclick="mw.dialog.get(this).remove()">Cancel</a>

                <?php if ($get_existing_files_for_confirm) { ?>
                    <button type="button" class="js-show-files btn btn-primary btn-sm">Show files</button>
                <?php } ?>

                <a href="javascript:;" id="js-buttons-confirm-install-link" class="btn btn-success btn-sm" onclick="mw.install_composer_package_confirm_by_key('<?php print $confirm_key ?>', '<?php print $require_name ?>','<?php print $require_version ?>')">Confirm</a>
            </div>
        </div>
    </div>
</div>
