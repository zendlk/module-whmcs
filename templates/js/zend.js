let template = {
    edit: (id) => {

        let formData = new FormData
        formData.append("id", id)
        formData.append("message", $("#message-" + id).val())
        formData.append("is_active", $("#is-active-" + id).is(':checked'))

        fetch("addonmodules.php?module=zend&tab=ajax_template_edit", {
            method: "POST",
            body: formData
        })
        .then(Response => Response.json())
        .then(Response => {
            if ( Response.status == "success" ) {
                location.reload()
            } else {
                alert("failed to update template")
            }
        })

        // console.log("template::edit - " + id)
    }
}