<?php

use boca\core\settings\Request;
use boca\core\settings\session;

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
	<?php if ( session::has( "success" ) ): ?>
        <small class="alert alert-success w-100"><?php echo session::get( "success" );
			session::clear( "success" ); ?></small>
	<?php endif; ?>

	<?php if ( session::has( "error" ) ): ?>
        <small class="alert alert-danger w-100"><?php echo session::get( "error" );
			session::clear( "error" ); ?></small>
	<?php endif; ?>
    <div class="row px-3">
        <div class="col-12">
            <div class="d-flex gap-2">
                <a href="/wp-admin/admin.php?page=boca_submenu_custom_taxonomy&newTax=true"
                   class="btn btn-success ">+ add new</a>
                <a href="/wp-admin/admin.php?page=boca_submenu_custom_taxonomy&addCustomFields=true"
                   class="btn btn-success ">+ add new Fields</a>
                <a href="/wp-admin/admin.php?page=boca_submenu_custom_taxonomy&manageFields=true"
                   class="btn btn-success ">+ manage Fields</a>
            </div>
        </div>
    </div>
	<?php if ( Request::hasInput( "manageFields" ) ): ?>
        <div class="row ox-3">
            <div class="col-12 pt-4">
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
                            Taxonomy
                        </div>
                        <div class="table-cell">
                            Type
                        </div>
                    </div>
                    <div class="table-body" id="container-append-row">
						<?php
						$array_custom_fields = get_option( "boca-custom-fields-taxonomy" );
						$custom_fields       = $array_custom_fields ? unserialize( $array_custom_fields ) : [];
						if (count($custom_fields) > 0 ):
							foreach ($custom_fields as $key => $value):
								?>
                                <div class="table-row">
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["name"] ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $key ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["taxonomies"] ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["type"] ?>"/>
                                    </div>
                                </div>
							<?php
							endforeach;
                            else:
                            ?>

                        <?php
						endif; ?>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( Request::hasInput( "addCustomFields" ) ): ?>
        <form action="/wp-json/boca/v1/create-taxonomy-fields" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo session::get( "_token_app" ) ?>"/>
            <div class="row ox-3">
                <div class="col-12 pt-4">
                    <div class="table-container">
                        <div class="table-caption">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <h3 class="m-0">Taxonomy:</h3>
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
                                Taxonomy
                            </div>
                            <div class="table-cell">
                                Type
                            </div>
                        </div>
                        <div class="table-body" id="container-append-row">
                            <div class="table-row">
                                <div class="table-cell">
                                    <input type="text" name="name" value=""/>
                                </div>
                                <div class="table-cell">
                                    <input type="text" name="id" value=""/>
                                </div>
                                <div class="table-cell">
                                    <select name="taxonomies">
										<?php foreach ( get_taxonomies() as $key_taxonomies => $value_taxonomies ): ?>
                                            <option value="<?php echo $value_taxonomies ?>"><?php echo $value_taxonomies ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="table-cell">
                                    <select name="type">
                                        <option value="text">text</option>
                                        <option value="image">image</option>
                                        <option value="gallery">gallery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">
                                    <input type="submit" class="btn btn-outline-dark" value="save"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	<?php endif; ?>
	<?php if ( Request::hasInput( "newTax" ) ): ?>
        <form action="/wp-json/boca/v1/create-taxonomy" method="POST">
            <input type="text" name="_token_app" hidden
                   value="<?php echo session::get( "_token_app" ) ?>"/>
            <div class="row">
                <div class="d-flex flex-column pb-4">
                    <label>Settings</label>
                    <div class="col-6">
                        <label>Name Register Taxonomy</label>
                        <input type="text" name="name_taxonomy" placeholder="Name tax"
                               value="{Taxonomy Name Register}" class="form-control" required/>
                    </div>
                </div>
                <hr>
                <div class="d-flex flex-column pb-4">
                    <label>labels</label>
                    <div class="row div-inputs gap-3">
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>name</label>
                            <input type="text" name="name" value="Taxonomy" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>singular name</label>
                            <input type="text" name="singular_name" value="Singular name" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>menu name</label>
                            <input type="text" name="menu_name" value="Taxonomy menu name" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>new item name</label>
                            <input type="text" name="new_item_name" value="new {Taxonomy name}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>name admin bar</label>
                            <input type="text" name="name_admin_bar" value="Name Admin Bar" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>add new</label>
                            <input type="text" name="add_new" value="Add New {Taxonomy}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>add new item</label>
                            <input type="text" name="add_new_item" value="Add New {Taxonomy}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column " style="width: 33.333%;">
                            <label>update item</label>
                            <input type="text" name="update_item" value="update item {Taxonomy}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>new item</label>
                            <input type="text" name="new_item" value="New {Taxonomy}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>edit item</label>
                            <input type="text" name="edit_item" value="Edit {Taxonomy}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>all items</label>
                            <input type="text" name="all_items" value="All {Taxonomy}" class="form-control" required/>
                        </div>
                        <div class="d-flex flex-column  " style="width: 33.333%;">
                            <label>search items</label>
                            <input type="text" name="search_items" value="Search {Taxonomy}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex  flex-column " style="width: 33.333%;">
                            <label>parent item colon</label>
                            <input type="text" name="parent_item_colon" value="Parent {Taxonomy}" class="form-control"
                                   required/>
                        </div>
                        <div class="d-flex  flex-column " style="width: 33.333%;">
                            <label>parent item</label>
                            <input type="text" name="parent_item" value="Parent {Taxonomy}" class="form-control"
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
                    <div class="col-6">
                        <label>Post Accept</label>
                        <select multiple name="accept_post[]">
							<?php foreach ( get_post_types() as $key => $value ): ?>
                                <option value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-outline-dark" value="save"/>
        </form>

	<?php endif; ?>
	<?php if ( Request::uri() == "/wp-admin/admin.php?page=boca_submenu_custom_taxonomy" ): ?>
        <div class="row ox-3">
            <div class="col-12 pt-4">
                <div class="table-container">
                    <div class="table-caption">
                        <div class="d-flex gap-3 align-items-center w-100">
                            <h3 class="m-0">Taxonomy:</h3>
                        </div>
                    </div>
                    <div class="table-head">
                        <div class="table-cell">
                            Taxonomy
                        </div>
                        <div class="table-cell">
                            Slug
                        </div>
                        <div class="table-cell">
                            Post Type
                        </div>
                        <div class="table-cell">

                        </div>
                    </div>
                    <div class="table-body" id="container-append-row">
						<?php
						$array_accept_post = get_option( "boca-tax-accept-post" );
						$accept_post       = $array_accept_post ? unserialize( $array_accept_post ) : [];
						$array             = get_option( "boca-custom-taxonomy" );
						$custom_taxonomy   = $array ? unserialize( $array ) : [];
						if ( count( $custom_taxonomy ) > 0 ):
							foreach ( $custom_taxonomy as $key => $value ):
								?>
                                <div class="table-row">
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $key ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly value="<?php echo $value["rewrite"]["slug"] ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <input type="text" readonly
                                               value="<?php echo join( ",", $accept_post[ $key ] ) ?>"/>
                                    </div>
                                    <div class="table-cell">
                                        <div class="d-flex gap-3">
                                            <a class="btn btn-success"
                                               href="/wp-admin/admin.php?page=boca_submenu_custom_taxonomy&edittax=<?php echo $key ?>">Edit</a>
                                            <form action="/wp-json/boca/v1/delete-taxonomy" method="POST">
                                                <input type="text" name="_token_app" hidden
                                                       value="<?php echo session::get( "_token_app" ) ?>"/>
                                                <input type="text" class="" name="id" hidden
                                                       value="<?php echo $key ?>"/>
                                                <input type="submit" class="btn btn-danger" value="x"/>
                                            </form>
                                        </div>
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
                                <div class="table-cell">
                                    no Data
                                </div>
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
</div>
