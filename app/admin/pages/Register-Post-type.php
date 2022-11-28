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
    thead th{
        width: 15%;
    }
    tbody tr {
        margin-bottom: 10px;
    }
    tbody tr:nth-child(odd){
        background-color: #eee;
    }
</style>

<input type="text" name="_token_app" hidden
       value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
<div class="container-fluid py-5">
    <?php if (\boca\core\settings\session::has("success")): ?>
        <small class="alert alert-success w-100"><?php echo \boca\core\settings\session::get("success");
            \boca\core\settings\session::clear("success"); ?></small>
    <?php endif; ?>

    <?php if (\boca\core\settings\session::has("error")): ?>
        <small class="alert alert-danger w-100"><?php echo \boca\core\settings\session::get("error");
            \boca\core\settings\session::clear("error"); ?></small>
    <?php endif; ?>
    <?php if (Request::uri() == "/wp-admin/admin.php?page=boca_submenu_register_post_type"): ?>
        <div class="row px-3">
            <div class="col-12">
                <div class="d-flex">
                    <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&addnew=true"
                       class="btn btn-success ms-auto">+ add new</a>
                </div>
            </div>
            <div class="col-12 pt-4">
                <div class="div-post-type-loop">
                    <h3 class="pb-3">Post Type:</h3>
                    <div class="row">
                        <table class="">
                            <thead class="">
                            <tr class="p-4">
                                <th>Post type</th>
                                <th>Slug</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $array = get_option("boca-posts-types");
                            $array_posts = $array ? unserialize($array) : [];
                            if($array_posts):
                                foreach ($array_posts as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key ?></td>
                                <td><?php echo $value["rewrite"]["slug"] ?></td>
                                <td class="d-flex gap-2">
                                    <form class="" action="/wp-json/boca/v1/delete-post-type" method="POST">
                                        <input type="text" hidden value="<?php echo $key ?>"/>
                                        <input type="submit" value="X" class="btn btn-danger"/>
                                    </form>
                                    <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&editposttype=true"
                                       class="btn btn-success">Edit</a>
                                </td>
                            </tr>
                          <?php
                          endforeach;
                            else:
                                ?>
                              <td>No Data</td>
                            <?php
                            endif;

                          ?>
                            </tbody>
                        </table>
                        <?php /*<div class="d-flex flex-column gap-2" style="width: 20%">
                        <label></label>
                        <p class=""></p>
                    </div>
                    <div class="d-flex flex-column gap-2" style="width: 20%">
                        <label></label>

                    </div>
                    <div class="d-flex gap-2" style="width: 20%">

                    </div> */ ?>
                    </div>


                </div>
            </div>

        </div>
    <?php endif; ?>
    <?php if (Request::hasInput("addnew")): ?>
        <form action="/wp-json/boca/v1/add-post-type" method="POST">
            <div class="row ">
                <div class="d-flex flex-column pb-4">
                    <label>Settings</label>
                    <div class="col-6">
                        <label>Name Register Post Type</label>
                        <input type="text" name="name_post_type" placeholder="Name Post Type"
                               value="{Post Name Register}" class="form-control" required/>
                    </div>
                </div>
                <hr>
                <div class="d-flex flex-column pb-4">
                    <label>labels</label>
                    <div class="row div-inputs gap-3">
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>name</label>
                            <input type="text" name="name" value="Post Name" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>singular name</label>
                            <input type="text" name="singular_name" value="Post Name" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>menu name</label>
                            <input type="text" name="menu_name" value="Post Name" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>name admin bar</label>
                            <input type="text" name="name_admin_bar" value="Post Name" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>add new</label>
                            <input type="text" name="add_new" value="Add New {Post Name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>add new item</label>
                            <input type="text" name="add_new_item" value="Add New {Post Name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>new item</label>
                            <input type="text" name="new_item" value="New {Post Name}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>edit item</label>
                            <input type="text" name="edit_item" value="Edit {Post Name}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>view item</label>
                            <input type="text" name="view_item" value="View {Post Name}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>all items</label>
                            <input type="text" name="all_items" value="All {Post Name}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>search items</label>
                            <input type="text" name="search_items" value="Search {Post Name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex  flex-column " style="width: 33.333%;">
                            <label>parent item colon</label>
                            <input type="text" name="parent_item_colon" value="Parent {Post Name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>not found</label>
                            <input type="text" name="not_found" value="Not Found {Post Name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>not found in trash</label>
                            <input type="text" name="not_found_in_trash" value="Not found in Trash."
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>featured image</label>
                            <input type="text" name="featured_image" value="{Post Name} Cover Image"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>set featured image</label>
                            <input type="text" name="set_featured_image" value="Set cover image" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>remove featured image</label>
                            <input type="text" name="remove_featured_image" value="Remove cover image"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>use featured image</label>
                            <input type="text" name="use_featured_image" value="Use as cover image" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>archives</label>
                            <input type="text" name="archives" value="{Post Name} archives" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>insert into item</label>
                            <input type="text" name="insert_into_item" value="Insert into {Post Name}"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>uploaded to this item</label>
                            <input type="text" name="uploaded_to_this_item" value="Uploaded to this {Post Name}"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>filter items list</label>
                            <input type="text" name="filter_items_list" value="Filter {Post Name} list"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>items list navigation</label>
                            <input type="text" name="items_list_navigation" value="{Post Name} list navigation"
                                   class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>items list</label>
                            <input type="text" name="items_list" class="form-control" value="{Post Name} List"
                                   required/>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex flex-column">
                    <label>args</label>
                    <div class="col-6">
                        <label>rewrite</label>
                        <input type="text" name="rewrite" placeholder="Slug Post type" class="form-control"/>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-outline-dark" value="save"/>
        </form>
    <?php endif; ?>

</div>











