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

    }
}

let administrator = {
    update: (id) => {

        let formData = new FormData
        formData.append("id", id)
        formData.append("phone", $("#phone-" + id).val())
        formData.append("is_active", $("#is-active-" + id).is(':checked'))

        fetch("addonmodules.php?module=zend&tab=ajax_administrator_update", {
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

    }
}
