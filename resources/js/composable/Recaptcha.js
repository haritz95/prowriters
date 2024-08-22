import { ref, onMounted, onUnmounted } from "vue";

// by convention, composable function names start with "use"
export function useGoogleCaptcha() {
    // state encapsulated and managed by the composable
    const token = ref(null);

    const site_key = window.recaptcha.site_key;
    const is_enabled = window.recaptcha.is_enabled;

    // const y = ref(0)

    // // a composable can update its managed state over time.
    function update(event) {
        grecaptcha.ready(function () {
            grecaptcha
                .execute(site_key, {
                    action: "contact",
                })
                .then(function (response_token) {
                    if (response_token) {
                        token.value = response_token;
                    }
                });
        });
    }

    // a composable can also hook into its owner component's
    // lifecycle to setup and teardown side effects.
    onMounted(function () {
        if (is_enabled) {
            window.ReCaptchaLoaded = update;

            let recaptchaScript = document.createElement("script");
            recaptchaScript.setAttribute(
                "src",
                "https://www.google.com/recaptcha/api.js?onload=ReCaptchaLoaded&render=" +
                    site_key
            );

            recaptchaScript.id = "recaptcha-script";
            recaptchaScript.async = true;

            document.head.appendChild(recaptchaScript);
        } else {
            token.value = 1;
        }
    });

    onUnmounted(function () {
        if (is_enabled) {
            const script = document.getElementById("recaptcha-script");
            if (script) {
                script.remove();
            }
            document.querySelector(".grecaptcha-badge").remove();

            var tag = document
                .querySelector('script[src*="recaptcha"]')
                .remove();
        }
    });

    // expose managed state as return value
    return { token };
}
