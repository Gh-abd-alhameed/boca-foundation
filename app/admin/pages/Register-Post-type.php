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

    thead th {
        width: 15%;
    }

    tbody tr {
        margin-bottom: 10px;
    }

    tbody tr:nth-child(odd) {
        background-color: #eee;
    }
</style>

<style>
    .table-container {
        display: table;
        width: 100%;
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
                <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&addnew=true"
                   class="btn btn-success ">+ add new</a>
                <form action="/wp-json/boca/v1/flush-rewrite" method="POST">
                    <input type="text" name="_token_app" hidden
                           value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                    <input type="submit" value="flash" class="btn btn-success"/>
                </form>
                <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&rewriteRule=true"
                   class="btn btn-success ">+ add rewrite rule</a>
                <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&redirect=true"
                   class="btn btn-success ">+ add Redirect</a>
                <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&rewrite_tag=true"
                   class="btn btn-success ">+ add Rewrite Tag</a>
            </div>
        </div>
    </div>
    <?php if(Request::hasInput("rewrite_tag")): ?>
        <form action="/wp-json/boca/v1/create-rewrite-tag" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
            <div class="row py-3">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <h3 class="m-0">Rewrite tag:</h3>
                                <a class="btn btn-success" id="btn-add-rewrite-tag">+</a>
                            </div>
                        </div>
                        <div class="table-head">
                            <div class="table-cell">
                                Tag
                            </div>
                            <div class="table-cell">
                                Regx
                            </div>
                            <div class="table-cell">
                               Query
                            </div>
                            <div class="table-cell">

                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">
							<?php
							$array = get_option("boca-rewrite-tag");
							$rewrite_tag = $array ? unserialize($array) : [];
							if (count($rewrite_tag) > 0):
								foreach ($rewrite_tag as $key => $value):
									?>
                                    <div class="table-row">
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["tag"] ?>"
                                                   name="tag[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["regx"] ?>"
                                                   name="regx[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["query"] ?>"
                                                   name="query[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <a onclick="remove_section()" class="btn btn-danger">X</a>
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
                        <div class="table-footer">
                            <div class="table-cell">
                                <input type="submit" class="btn btn-outline-dark" value="save"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <script>
        let container_append_input = document.getElementById("container-append-row");
        let btn_add_rewrite_tag = document.getElementById("btn-add-rewrite-tag");
        btn_add_rewrite_tag.addEventListener("click" , function (){
            if(document.getElementById("no-data") != null){
                document.getElementById("no-data").remove();
            }
            let div = document.createElement("div");
            div.classList.add("table-row");
            let container = `
                    <div class="table-cell">
                        <input type="text" class="form-control" value=""
                               name="tag[]"/>
                    </div>
                    <div class="table-cell">
                        <input type="text" class="form-control" value=""
                               name="regx[]"/>
                    </div>
                    <div class="table-cell">
                        <input type="text" class="form-control" value=""
                               name="query[]"/>
                    </div>
                    <div class="table-cell">
                        <a onclick="remove_section()" class="btn btn-danger">X</a>
                    </div>
            `;
            div.innerHTML = container;
            container_append_input.appendChild(div);
        });

        let remove_section = () => {
            event.target.parentElement.parentElement.remove();
        }
    </script>
    <?php endif ; ?>
	<?php if (Request::hasInput("redirect")): ?>
        <form action="/wp-json/boca/v1/create-redirect" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
            <div class="row py-3">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center">
                                <h3 class="m-0">Redirect:</h3>
                                <a class="btn btn-success" id="btn-add-redierct">+</a>
                            </div>

                        </div>
                        <div class="table-head">

                            <div class="table-cell">
                                Old Url
                            </div>
                            <div class="table-cell">
                                New
                            </div>
                            <div class="table-cell">
                                Code
                            </div>
                            <div class="table-cell">

                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">
							<?php
							$array = get_option("boca-redirect");
							$redirect = $array ? unserialize($array) : [];
							if (count($redirect) > 0):
								foreach ($redirect as $key => $value):
									?>
                                    <div class="table-row">
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["old"] ?>"
                                                   name="oldUrl[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["new"] ?>"
                                                   name="newUrl[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <select class="form-select" name="code[]">
                                                <option <?php echo $value["code"] == 301 ? "selected" : "" ?>
                                                        value="301">
                                                    301
                                                </option>
                                                <option <?php echo $value["code"] == 302 ? "selected" : "" ?>
                                                        value="302">
                                                    302
                                                </option>
                                            </select>
                                        </div>
                                        <div class="table-cell">
                                            <a onclick="remove_section()" class="btn btn-danger">X</a>
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
                        <div class="table-footer">
                            <div class="table-cell">
                                <input type="submit" class="btn btn-outline-dark" value="save"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            let container_append_row = document.getElementById("container-append-row");
            let btn_add_redierct = document.getElementById("btn-add-redierct");
            btn_add_redierct.addEventListener("click", () => {
                if (document.getElementById("no-data") != null) {
                    document.getElementById("no-data").remove();
                }
                let div = document.createElement("div");
                div.classList.add("table-row");
                let container = `

                <div class="table-cell">
                    <input type="text" class="form-control" value="" name="oldUrl[]"/>
                </div>
                <div class="table-cell">
                    <input type="text" class="form-control" value="" name="newUrl[]"/>
                </div>
                <div class="table-cell">
                    <select class="form-select" name="code[]">
                        <option   value="301">301</option>
                        <option  value="302">302</option>
                    </select>
                </div>
                <div class="table-cell">
                    <a onclick="remove_section()" class="btn btn-danger">X</a>
                </div>`;
                div.innerHTML = container;
                container_append_row.appendChild(div);
            });
            let remove_section = () => {
                event.target.parentElement.parentElement.remove();
            }
        </script>
	<?php endif; ?>
	<?php if (Request::hasInput("rewriteRule")): ?>

        <form action="/wp-json/boca/v1/create-rewrite-rule" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
            <div class="row py-3">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center">
                                <h3 class=""> Rewrite: </h3>
                                <a class="btn btn-success" id="btn-add-rewrite">+</a>
                            </div>
                        </div>
                        <div class="table-head">
                            <div class="table-cell">
                                Url
                            </div>
                            <div class="table-cell">
                                matches
                            </div>
                            <div class="table-cell">
                                rule
                            </div>
                            <div class="table-cell">

                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">
							<?php
							$array = get_option("boca-rewrite-rule");
							$rewrite = $array ? unserialize($array) : [];
							if (count($rewrite) > 0):
								foreach ($rewrite as $key => $value):
									?>
                                    <div class="table-row">
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["url"] ?>" name="url[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <input type="text" class="form-control" value="<?php echo $value["matches"] ?>" name="matches[]"/>
                                        </div>
                                        <div class="table-cell">
                                            <select class="form-select" name="rule[]">
                                                <option <?php echo  $value["rule"] == "Top" ? "selected" : "" ?> value="Top">Top</option>
                                            </select>
                                        </div>
                                        <div class="table-cell">
                                            <a onclick="remove_section()" class="btn btn-danger">X</a>
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
							endif; ?>
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
        <script>
            let container_append_row = document.getElementById("container-append-row");
            let btn_add_rewrite = document.getElementById("btn-add-rewrite");
            btn_add_rewrite.addEventListener("click", (e) => {
                if (document.getElementById("no-data") != null) {
                    document.getElementById("no-data").remove();
                }
                let div = document.createElement("div");
                div.classList.add("table-row");
                let container = `
                    <div class="table-cell">
                        <input type="text" class="form-control" name="url[]"/>
                    </div>
                    <div class="table-cell">
                        <input type="text" class="form-control" name="matches[]"/>
                    </div>
                    <div class="table-cell">
                        <select class="form-select" name="rule[]">
                            <option value="Top">Top</option>
                        </select>
                    </div>
                    <div class="table-cell">
                        <a onclick="remove_section()" class="btn btn-danger">X</a>
                    </div>
            `;
                div.innerHTML = container;
                container_append_row.appendChild(div);
            })
            let remove_section = () => {
                event.target.parentElement.parentElement.remove();
            }
        </script>
	<?php endif; ?>
	<?php if (Request::uri() == "/wp-admin/admin.php?page=boca_submenu_register_post_type"): ?>
        <div class="row ox-3">
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
							if ($array_posts):
								foreach ($array_posts as $key => $value):
									?>
                                    <tr>
                                        <td><?php echo $key ?></td>
                                        <td><?php echo $value["rewrite"]["slug"] ?></td>
                                        <td class="d-flex gap-2">
                                            <form class="" action="/wp-json/boca/v1/delete-post-type" method="POST">
                                                <input type="text" name="_token_app" hidden
                                                       value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                                                <input type="text" hidden name="post-type" value="<?php echo $key ?>"/>
                                                <input type="submit" value="X" class="btn btn-danger"/>
                                            </form>
                                            <a href="/wp-admin/admin.php?page=boca_submenu_register_post_type&Editpost=true"
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
	<?php if (Request::hasInput("Editpost")):

		$array = get_option("boca-posts-types");
		$posts_array = $array ? unserialize($array) : [];
		if (count($posts_array) > 0):
			foreach ($posts_array as $key => $value):
				?>
                <form class="" action="/wp-json/boca/v1/edit-post-type" method="POST">
                    <input type="text" name="_token_app" hidden
                           value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                    <div class="row">
                        <h3>Edit Post Type</h3>
                        <div class="d-flex flex-column pb-4">
                            <label>Settings</label>
                            <div class="col-6">
                                <label>Name Register Post Type</label>
                                <input type="text" name="name_post_type" placeholder="Name Post Type"
                                       value="<?php echo $key ?>" class="form-control" required/>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column pb-4">
                            <label>labels</label>
                            <div class="row div-inputs gap-3">
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>name</label>
                                    <input type="text" name="name" value="<?php echo $value["labels"]["name"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>singular name</label>
                                    <input type="text" name="singular_name"
                                           value="<?php echo $value["labels"]["singular_name"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>menu name</label>
                                    <input type="text" name="menu_name"
                                           value="<?php echo $value["labels"]["menu_name"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>name admin bar</label>
                                    <input type="text" name="name_admin_bar"
                                           value="<?php echo $value["labels"]["name_admin_bar"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>add new</label>
                                    <input type="text" name="add_new" value="<?php echo $value["labels"]["add_new"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>add new item</label>
                                    <input type="text" name="add_new_item"
                                           value="<?php echo $value["labels"]["add_new_item"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>new item</label>
                                    <input type="text" name="new_item"
                                           value="<?php echo $value["labels"]["new_item"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>edit item</label>
                                    <input type="text" name="edit_item"
                                           value="<?php echo $value["labels"]["edit_item"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>view item</label>
                                    <input type="text" name="view_item"
                                           value="<?php echo $value["labels"]["view_item"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>all items</label>
                                    <input type="text" name="all_items"
                                           value="<?php echo $value["labels"]["all_items"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>search items</label>
                                    <input type="text" name="search_items"
                                           value="<?php echo $value["labels"]["search_items"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex  flex-column " style="width: 33.333%;">
                                    <label>parent item colon</label>
                                    <input type="text" name="parent_item_colon"
                                           value="<?php echo $value["labels"]["parent_item_colon"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>not found</label>
                                    <input type="text" name="not_found"
                                           value="<?php echo $value["labels"]["not_found"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>not found in trash</label>
                                    <input type="text" name="not_found_in_trash"
                                           value="<?php echo $value["labels"]["not_found_in_trash"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>featured image</label>
                                    <input type="text" name="featured_image"
                                           value="<?php echo $value["labels"]["featured_image"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>set featured image</label>
                                    <input type="text" name="set_featured_image"
                                           value="<?php echo $value["labels"]["set_featured_image"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>remove featured image</label>
                                    <input type="text" name="remove_featured_image"
                                           value="<?php echo $value["labels"]["remove_featured_image"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>use featured image</label>
                                    <input type="text" name="use_featured_image"
                                           value="<?php echo $value["labels"]["use_featured_image"] ?>"
                                           class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>archives</label>
                                    <input type="text" name="archives"
                                           value="<?php echo $value["labels"]["archives"] ?>" class="form-control"
                                           required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>insert into item</label>
                                    <input type="text" name="insert_into_item"
                                           value="<?php echo $value["labels"]["insert_into_item"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>uploaded to this item</label>
                                    <input type="text" name="uploaded_to_this_item"
                                           value="<?php echo $value["labels"]["uploaded_to_this_item"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>filter items list</label>
                                    <input type="text" name="filter_items_list"
                                           value="<?php echo $value["labels"]["filter_items_list"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column  " style="width: 33.333%;">
                                    <label>items list navigation</label>
                                    <input type="text" name="items_list_navigation"
                                           value="<?php echo $value["labels"]["items_list_navigation"] ?>"
                                           class="form-control" required/>
                                </div>
                                <div class="d-flex flex-column " style="width: 33.333%;">
                                    <label>items list</label>
                                    <input type="text" name="items_list" class="form-control"
                                           value="<?php echo $value["labels"]["items_list"] ?>"
                                           required/>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column">
                            <label>args</label>
                            <div class="col-6">
                                <label>rewrite</label>
                                <input type="text" name="rewrite" placeholder="Slug Post type"
                                       value="<?php echo $value["rewrite"]["slug"] ?>" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-outline-dark" value="save"/>
                </form>
			<?php
			endforeach;
		else:
			?>
            <h1>no data</h1>
		<?php
		endif;
		?>

	<?php endif; ?>
	<?php if (Request::hasInput("addnew")): ?>
        <form action="/wp-json/boca/v1/add-post-type" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
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











