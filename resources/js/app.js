import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Layout from "./Shared/Layout.vue";

import route from "ziggy-js";
import AppHead from "./Shared/AppHead.vue";
import PageTitle from "./Shared/PageTitle.vue";

// Dropdown Select Option
import vSelect from "vue-select";

// Dropdown Select Option

import ErrorField from "./components/order/Fields/ErrorField.vue";
import ValidationError from "./components/ValidationError.vue";
import DialogLink from "./components/DialogLink.vue";
import Modal from "./components/Modal.vue";
import AddButton from "./components/AddButton.vue";
import Avatar from "./components/Avatar.vue";
import InlineTags from "./components/InlineTags.vue";

// ES6 Modules or TypeScript
import Swal from "sweetalert2";

const customSwal = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success me-2",
        cancelButton: "btn btn-danger me-2",
    },
    buttonsStyling: false,
});

// Modal
import { modal } from "momentum-modal";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    resolve: async (name) => {
        let page = (
            await resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob("./Pages/**/*.vue")
            )
        ).default;
        page.layout ??= Layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(modal, {
                resolve: (name) =>
                    resolvePageComponent(
                        `./Pages/${name}.vue`,
                        import.meta.glob("./Pages/**/*.vue")
                    ),
            })
            .use(plugin)
            .mixin({
                created: function () {
                    this.ADMIN_ROLES = {
                        SUPER_ADMIN: "super_admin",
                        EDITOR: "editor",
                    };
                },
                methods: {
                    route: route,
                    __: (str) => window.translate(str),
                    localDateTime: function (dateTime) {
                        // dateTime in 2022-04-01T09:05:00.000000Z format
                        if (dateTime) {
                            return new Date(dateTime).toLocaleString();
                        }
                    },
                    localDate: function (dateTime) {
                        // dateTime in 2022-04-01T09:05:00.000000Z format
                        if (dateTime) {
                            return new Date(dateTime).toLocaleDateString();
                        }
                    },
                    getBrowserTimezone() {
                        return Intl.DateTimeFormat().resolvedOptions().timeZone;
                    },
                    formatMoney: function (value) {
                        if (value == 0) {
                            return accounting.formatMoney(
                                0,
                                currencyConfig.currency
                            );
                        }
                        if (!value) {
                            return;
                        }
                        // currencyConfig is defined in app.blade.php
                        return accounting.formatMoney(
                            value,
                            currencyConfig.currency
                        );
                    },
                    showAlertMessage: function (message) {
                        customSwal.fire({
                            text: message,
                        });
                    },
                    confirmDialog: function (confirmButtonText, callback) {
                        return customSwal
                            .fire({
                                title: window.translate("Are you sure?"),
                                text: window.translate(
                                    "You won't be able to revert this!"
                                ),
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: confirmButtonText,
                                cancelButtonText:
                                    window.translate("No, cancel"),
                                reverseButtons: true,
                            })
                            .then((result) => {
                                if (result.value) {
                                    callback();
                                }
                            });
                    },
                    deleteConfirmDialog: function (context, url) {
                        return customSwal
                            .fire({
                                title: window.translate("Are you sure?"),
                                text: window.translate(
                                    "You won't be able to revert this!"
                                ),
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText:
                                    window.translate("Yes, delete it!"),
                            })
                            .then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    context.$inertia.delete(url, {
                                        preserveState: false,
                                    });
                                }
                            });
                    },
                    // asset(path) {
                    //     var base_path = window._asset || "";
                    //     return base_path + path;
                    // },
                    // storageAsset(path) {
                    //     if (path) {
                    //         path = path.replace("public", "storage");
                    //     }
                    //     return window._base_url + "/" + path;
                    // },
                    asset(path) {
                        var base_path = window._asset || "";

                        // Extract the last part of url_string_1 and the first part of url_string_2
                        let lastPart_1 = base_path
                            .split("/")
                            .filter(Boolean)
                            .pop(); // Get last non-empty part
                        let firstPart_2 = path.split("/").filter(Boolean)[0]; // Get first non-empty part

                        // Check if the last part of url_string_1 matches the first part of url_string_2

                        if (lastPart_1 === firstPart_2) {
                            // Merge the two strings

                            let mergedUrl =
                                base_path +
                                path.split("/" + firstPart_2 + "/").pop();
                                return mergedUrl;
                        } else {
                            return base_path + path;
                        }
                    },
                    // Input Filter to allow only numbers and single dot[.]
                    // Usage example : @keypress="onlyNumber"
                    onlyNumber: function ($event, total) {
                        const keysAllowed = [
                            "0",
                            "1",
                            "2",
                            "3",
                            "4",
                            "5",
                            "6",
                            "7",
                            "8",
                            "9",
                            ".",
                        ];
                        const keyPressed = $event.key;
                        if (!keysAllowed.includes(keyPressed)) {
                            $event.preventDefault();
                        }

                        const new_total = String(total) + String(keyPressed);
                        const number_of_dots = (new_total.match(/\./g) || [])
                            .length;
                        if (total.length > 0 && number_of_dots > 1) {
                            $event.preventDefault();
                        }
                    },
                    hasRole: function (desiredRole) {
                        if (this.$page.props.auth_user.role == desiredRole) {
                            return true;
                        }
                        return false;
                    },
                    can: function (desiredPermission) {
                        var permissions =
                            this.$page.props.auth_user.permissions;
                        if (Object.hasOwn(permissions, "is_super_admin")) {
                            return true;
                        }
                        if (Object.hasOwn(permissions, desiredPermission)) {
                            return true;
                        }

                        return false;
                    },
                    canAny: function (desiredPermissions) {
                        var permissions =
                            this.$page.props.auth_user.permissions;
                        if (Object.hasOwn(permissions, "is_super_admin")) {
                            return true;
                        }
                        for (
                            let index = 0;
                            index < desiredPermissions.length;
                            index++
                        ) {
                            const permission_name = desiredPermissions[index];
                            if (Object.hasOwn(permissions, permission_name)) {
                                return true;
                            }
                        }

                        return false;
                    },
                },
            })
            .component("Link", Link)
            .component("AppHead", AppHead)
            .component("PageTitle", PageTitle)
            .component("vSelect", vSelect)
            .component("ErrorField", ErrorField)
            .component("ValidationError", ValidationError)
            .component("DialogLink", DialogLink)
            .component("Modal", Modal)
            .component("AddButton", AddButton)
            .component("Avatar", Avatar)
            .component("InlineTags", InlineTags)
            .mount(el);
    },
});

InertiaProgress.init({ color: "orange", showSpinner: true });

// Libraries
// Axios
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Bootstrap
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;
// Bootstrap Tooltip
import { Tooltip } from "bootstrap";
new Tooltip(document.body, {
    selector: "[data-bs-toggle='tooltip']",
});

//https://www.cssscript.com/demo/beautiful-growl-notification/
import "./growl-notification.min.js";
// window.GrowlNotification = growl;

// Accounting Js
import * as accounting from "accounting-js";
window.accounting = accounting;

// Main CSS file
import "../sass/app.scss";
