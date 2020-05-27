let toast = siiimpleToast;

toast = toast.setOptions({
    container: 'body',
    class: 'siiimpleToast',
    position: 'bottom|left',
    margin: 15,
    delay: 0,
    duration: 3000,
    style: {
        fontSize: "14px",
        fontWeight: "560",
        borderRadius: "8px"
    },
});

// Disabling Enter button on all forms
if (document.forms.length) {
    [].slice.apply(document.forms).forEach(form => {
        if (!form.dataset.hasEnter) form.onkeydown = event => event.key != "Enter";
    })
}

// Adding is-active class on .dropdown buttons (bulma)
[...document.querySelectorAll(".dropdown")].forEach(dropdown => {
    dropdown.addEventListener("click", function (elem) {
        this.classList.toggle('is-active');
    })
})

// Managing the back button if there is an active modal (bulma)
window.history.replaceState({ modal_active: 0 }, "modal hidden", '');
window.onpopstate = function (event) {
    if (!window.history.state.modal_active) {
        toggle_modal()
    }
}

// Toggling the navbar on mobile (bulma)
function toggle_nav(e) {
    let target = e && e.dataset.target ? e.dataset.target : "navmenu";
    let elem = document.getElementById(target);
    if (!e) {
        e = document.getElementById("nav_burger");
    }
    e.classList.toggle("is-active");
    elem.classList.toggle("is-active");
}

// Toggling a modal state (bulma)
function toggle_modal(elem, options = {}) {
    if (!elem) {
        let modal = document.querySelector(".modal.is-active");
        if (modal) {
            if (!modal.classList.toggle("is-active")) {
                if (window.history.state.modal_active) window.history.back();
            };
        };
    } else {
        let target;
        if (typeof elem == 'string') {
            target = elem;
        } else {
            target = elem.dataset.target;
        }
        let modal = document.getElementById(target);
        if (modal.classList.toggle("is-active")) {
            window.history.pushState({ modal_active: 1 }, "modal is active", "#modal_active");
        } else {
            if (window.history.state.modal_active) window.history.back();
        }
    }
    if (options.focus) {
        let focus = document.querySelector(options.focus);
        focus.focus();
    }
}

// Closing the modal on clicking the background (bulma)
let modal_bg = document.querySelectorAll("div.modal-background");
[...modal_bg].forEach(ele => {
    ele.addEventListener("click", () => {
        toggle_modal();
    })
})

// Changing between tabs (bulma)
function tab_change(elem) {
    const target = document.getElementById(elem.dataset.target);
    const tab_level = elem.dataset.tabLevel || 1;
    let active;
    if (tab_level > 1) {
        active = document.querySelector(".tab.is-active.tab-level-" + tab_level);
    } else {
        active = document.querySelector(".tab.is-active");
    }
    active.classList.remove("is-active");
    target.classList.add("is-active");
}

// Helper funcitons
function make_action(params, options = {}) {
    let form = options.form ? options.form : 0;
    let reload = options.hasOwnProperty('reload') ? options.reload : 1;
    let callback = options.callback ? options.callback : 0;
    fetch("action.php", {
        method: "post",
        body: params
    }).then(x => x.json())
        .then(res => {
            if (res.status) {
                if (res.message) toast.success(res.message)
                toggle_modal();
                if (reload) {
                    toast.message("Page will refresh in 2 seconds", {
                        delay: 500
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
                if (callback) callback();
            } else {
                if (res.message) toast.alert(res.message);
                if (res.focus && form) form[res.focus].focus();
            }
        })
}

function make_request(params, options = {}) {
    let callback = options.callback ? options.callback : 0;
    fetch("api.php", {
        method: "POST",
        body: params
    }).then(x => x.json())
        .then(res => {
            if (res.status) {
                if (res.message) {
                    toast.success(res.message)
                }
                if (callback) {
                    callback(res);
                }
            } else {
                toast.alert(res.message);
            }
        })
}

function clear_form(target) {
    let form = document.forms[target];

    [...form].forEach(input => input.value = '');

    toggle_modal();
}

function randomStr(l) {
    let c = "0123456789abcdefghijklmnopqrstuvwxyz";
    let r = '';
    for (let i = l; i > 0; --i) r += c[Math.floor(Math.random() * c.length)];
    return r;
}

function copyText(str) {
    let input = document.getElementById("copyText");
    input.value = str;
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand("copy");
    toast.success("Copied");
}