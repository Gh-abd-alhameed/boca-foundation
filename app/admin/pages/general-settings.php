<?php

settings_errors();

?>
<form action="options.php" method="post" enctype="multipart/form-data" novalidate="novalidate">
    <?php
    do_settings_sections('boca_settings');

    settings_fields('boca-settings-option');

    submit_button();
    ?>

</form>
