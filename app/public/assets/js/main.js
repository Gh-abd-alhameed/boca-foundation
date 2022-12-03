let boca_btn_add_image = document.querySelectorAll(".boca-btn-add-image");
let boca_btn_delete_image = document.querySelectorAll(".boca-btn-delete-image");
if(boca_btn_add_image != null){
    boca_btn_add_image.forEach(el=>{
        el.addEventListener("click",e =>{
            wp.media({
                title: 'Select image',
                button: {
                    text: 'use this image',
                },
                multiple: false,
            }).open().on("select",function (){
                let id = this.models[0].attributes.selection.models[0].attributes.id;
                let url = this.models[0].attributes.selection.models[0].attributes.url;
                e.target.parentElement.previousElementSibling.firstElementChild.setAttribute("src" , url);
                e.target.parentElement.nextElementSibling.setAttribute("value" , id);
            })
        })
    })
    boca_btn_delete_image.forEach(el=>{
        el.addEventListener("click" , (e)=>{
            e.target.parentElement.previousElementSibling.firstElementChild.setAttribute("src" , "");
            e.target.parentElement.nextElementSibling.setAttribute("value" , "");
        })
    })
}