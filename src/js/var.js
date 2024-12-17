const pc_screen           = document.querySelector("#pc-screen");
const phone_screen        = document.querySelector("#phone-screen");
const create_ele          = {
    "pc": {
        "id":       document.querySelector("#pc-id-cre"),
        "url":      document.querySelector("#pc-url-cre"),
        "password": document.querySelector("#pc-password-cre"),
        "button":   document.querySelector("#pc-create")
    },
    "ph": {
        "id":       document.querySelector("#ph-id-cre"),
        "url":      document.querySelector("#ph-url-cre"),
        "password": document.querySelector("#ph-password-cre"),
        "button":   document.querySelector("#ph-create")
    }
}
const delete_ele = {
    "pc": {
        "id":       document.querySelector("#pc-id-del"),
        "password": document.querySelector("#pc-password-del"),
        "button":   document.querySelector("#pc-delete")
    },
    "ph": {
        "id":       document.querySelector("#ph-id-del"),
        "password": document.querySelector("#ph-password-del"),
        "button":   document.querySelector("#ph-delete")
    }
}
const success_ele = {
    "pc": {
        "cre": {
            "ele": document.querySelector("#pc-success-cre"),
            "url": document.querySelector("#created-url-pc"),
        },
        "del": document.querySelector("#pc-success-del")
    },
    "ph": {
        "cre": {
            "ele": document.querySelector("#ph-success-cre"),
            "url": document.querySelector("#created-url-ph")
        },
        "del": document.querySelector("#ph-success-del")
    }
}
const fail_ele = {
    "pc": {
        "cre": document.querySelector("#pc-fail-cre"),
        "del": document.querySelector("#pc-fail-del")
    },
    "ph": {
        "cre": document.querySelector("#ph-fail-cre"),
        "del": document.querySelector("#ph-fail-del")
    }
}