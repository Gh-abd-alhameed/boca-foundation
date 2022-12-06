let boca_btn_add_image = document.querySelectorAll(".boca-btn-add-image");
let boca_btn_delete_image = document.querySelectorAll(".boca-btn-delete-image");
if (boca_btn_add_image[0] != null) {
    boca_btn_add_image.forEach(el => {
        el.addEventListener("click", e => {
            wp.media({
                title: 'Select image',
                button: {
                    text: 'use this image',
                },
                multiple: false,
            }).open().on("select", function () {
                let id = this.models[0].attributes.selection.models[0].attributes.id;
                let url = this.models[0].attributes.selection.models[0].attributes.url;
                e.target.parentElement.previousElementSibling.firstElementChild.setAttribute("src", url);
                e.target.parentElement.nextElementSibling.setAttribute("value", id);
            })
        })
    })
    boca_btn_delete_image.forEach(el => {
        el.addEventListener("click", (e) => {
            e.target.parentElement.previousElementSibling.firstElementChild.setAttribute("src", "");
            e.target.parentElement.nextElementSibling.setAttribute("value", "");
        })
    })
}
let boca_btn_add_gallery = document.querySelectorAll(".boca-btn-add-gallery");
let boca_btn_deletegallery = document.querySelectorAll(".boca-btn-delete-gallery");
if (boca_btn_add_gallery != null) {
    boca_btn_add_gallery.forEach(el => {
        el.addEventListener("click", e => {
            wp.media({
                title: "Select images",
                button: {
                    text: "use this images",
                },
                multiple: true,
            }).open().on("select", function () {
                let ids = [];
                let urls = [];
                this.models[0].attributes.selection.models.forEach(ele => {
                    ids.push(ele.attributes.id);
                    urls.push(`<img src="${ele.attributes.url}"/>`);
                });
                e.target.parentElement.previousElementSibling.innerHTML = urls.join("");
                e.target.parentElement.nextElementSibling.setAttribute("value", ids.join(","));
            })
        })
    })
    boca_btn_deletegallery.forEach((el) => {
        el.addEventListener("click", (e) => {
            e.target.parentElement.previousElementSibling.innerHTML = "";
            e.target.parentElement.nextElementSibling.setAttribute("value", "");
        });
    });
}