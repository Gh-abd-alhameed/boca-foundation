<?php

use boca\core\settings\Request;

?>
<style>
    * {
        font-size: 14px;
        font-family: system-ui;
    }

    .div-inputs {
        flex-wrap: wrap;
    }
</style>

<style>
    .table-container {
        display: table;
    }

    .table-caption {
        display: table-caption;
    }

    .table-head {
        display: table-header-group;
    }

    .table-body {
        display: table-row-group;
    }

    .table-row {
        display: table-row;
    }

    .table-cell {
        display: table-cell;
        padding: 7px 5px;
    }

    .table-footer {
        display: table-footer-group;
    }

</style>
<div class="container-fluid py-5">
    <?php if (\boca\core\settings\session::has("success")): ?>
        <small class="alert alert-success w-100"><?php echo \boca\core\settings\session::get("success");
            \boca\core\settings\session::clear("success"); ?></small>
    <?php endif; ?>

    <?php if (\boca\core\settings\session::has("error")): ?>
        <small class="alert alert-danger w-100"><?php echo \boca\core\settings\session::get("error");
            \boca\core\settings\session::clear("error"); ?></small>
    <?php endif; ?>


    <div class="row px-3">
        <div class="col-12">
            <div class="d-flex gap-2">
                <a href="/wp-admin/admin.php?page=boca_submenu_custom_fields"
                   class="btn btn-success ">Manage</a>
                <a href="/wp-admin/admin.php?page=boca_submenu_custom_fields&createfields=true"
                   class="btn btn-success ">add Fields</a>
            </div>
        </div>
    </div>
    <?php if (Request::hasInput("createfields")): ?>
        <form action="/wp-json/boca/v1/create-meta-boxes" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
            <div class="row py-3 justify-content-center">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center">
                                <h3 class="m-0">Create:</h3>
                            </div>
                        </div>
                        <div class="table-head">

                            <div class="table-cell">
                                Name
                            </div>
                            <div class="table-cell">
                                ID
                            </div>
                            <div class="table-cell">
                                Post Type
                            </div>
                            <div class="table-cell">
                                Type
                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">

                            <div class="table-row">
                                <div class="table-cell">
                                    <input type="text" class="form-control" value=""
                                           name="name"/>
                                </div>
                                <div class="table-cell">
                                    <input type="text" class="form-control" value=""
                                           name="id"/>
                                </div>
                                <div class="table-cell">
                                    <select multiple class="form-select" name="post_type[]">
                                        <?php foreach (get_post_types() as $key => $value): ?>
                                            <option value="<?php echo $value ?>">
                                                <?php echo $value ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="table-cell">
                                    <select class="form-select" name="type">
                                        <option value="text">
                                            text
                                        </option>
                                        <option value="image">
                                            image
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-footer">
                            <div class="table-cell">
                                <input type="submit" class="btn btn-outline-dark" value="save"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <?php if (Request::uri() == "/wp-admin/admin.php?page=boca_submenu_custom_fields"): ?>
        <div class="row py-3">
            <div class="col-12">
                <div class="table-container">
                    <div class="table-caption">
                        <div class="d-flex gap-3 align-items-center w-100">
                            <h3 class="m-0">Fields:</h3>
                        </div>
                    </div>
                    <div class="table-head">
                        <div class="table-cell">
                            Name
                        </div>
                        <div class="table-cell">
                            ID
                        </div>
                        <div class="table-cell">
                            Post Type
                        </div>
                        <div class="table-cell">
                            Type
                        </div>
                        <div class="table-cell">

                        </div>
                    </div>
                    <div class="table-body" id="container-append-row">
                        <?php
                        $array = get_option("boca-metaboxes-fields");
                        $rewrite_tag = $array ? unserialize($array) : [];
                        if (count($rewrite_tag) > 0):
                            foreach ($rewrite_tag as $key => $value):
                                ?>
                                <div class="table-row">
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["name"] ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $key ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly
                                               value="<?php echo join(",", $value["post_type"]) ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["type"] ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <a class="btn btn-success" href="/wp-admin/admin.php?page=boca_submenu_custom_fields&editFields=<?php echo $key ?>">Edit</a>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <div class="table-row" id="no-data">
                                <div class="table-cell">
                                    no Data
                                </div>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (Request::hasInput("editFields") && !empty(Request::input("editFields"))): ?>
        <form action="/wp-json/boca/v1/edit-meta-boxes" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
            <div class="row py-3 justify-content-center">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center">
                                <h3 class="m-0">Edit:</h3>
                            </div>
                        </div>
                        <div class="table-head">

                            <div class="table-cell">
                                Name
                            </div>
                            <div class="table-cell">
                                ID
                            </div>
                            <div class="table-cell">
                                Post Type
                            </div>
                            <div class="table-cell">
                                Type
                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">
                            <?php
                            $array = get_option("boca-metaboxes-fields");
                            $fields = $array ? unserialize($array)[Request::input("editFields")] : [];
                            if (count($fields) > 0):
                                    ?>
                                    <div class="table-row">
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $fields["name"] ?>"
                                                   name="name"/>
                                        </div>
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo Request::input("editFields") ?>""
                                                   name="id"/>
                                        </div>
                                        <div class="table-cell">
                                            <select multiple class="form-select" name="post_type[]" value="<?php echo join(",",$fields["post_type"]) ?>">
                                                <?php foreach (get_post_types() as $key => $value): ?>
                                                    <option  value="<?php echo $value ?>">
                                                        <?php echo $value ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="table-cell">
                                            <select class="form-select" name="type" value="<?php echo $fields["type"] ?>">
                                                <option <?php echo $fields["type"] == "text" ? "selected" : "" ?>  value="text">
                                                    text
                                                </option>
                                                <option <?php echo $fields["type"] == "image" ? "selected" : "" ?> value="image">
                                                    image
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                <?php
                            else:
                                ?>
                                <div class="table-row">
                                    <div class="table-cell">
                                        no data
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="table-footer">
                            <div class="table-cell">
                                <input type="submit" class="btn btn-outline-dark" value="save"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>











