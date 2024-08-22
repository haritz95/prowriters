<template>
  <div class="container page-container">
    <div class="row">
      <div class="col-md-12">
        <h4>{{ __("Website & Content") }}</h4>
        <hr />
      </div>
      <div class="col-md-3">
        <ul class="nav flex-column">
          <template v-for="(menu, index) in menuItems" :key="index">
            <li class="nav-item dropend" v-if="menu.has_children">
              <a
                class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
                href="#"
                role="button"
                aria-expanded="false"
                >{{ menu.label }}</a
              >
              <ul class="dropdown-menu">
                <li
                  v-for="(childMenu, childrenIndex) in menu.children"
                  :key="childrenIndex"
                >
                  <Link class="dropdown-item" :href="childMenu.url">{{
                    childMenu.label
                  }}</Link>
                </li>
              </ul>
            </li>
            <Link v-else class="nav-link" :href="menu.url">{{
              menu.label
            }}</Link>
          </template>
        </ul>
      </div>
      <div class="col-md-9">
        <div class="">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h5 class="card-title">{{ title }}</h5>
              </div>
              <div class="col-md-4 text-end"><slot name="action"></slot></div>
            </div>
            <hr />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["content_language", "title"],
  created() {
    let __ = this.__;
    let content_language = this.content_language;

    this.add(__("Homepage Sections"), [
      [
        "Hero",
        route("admin.manage.content.homepage.section.hero", content_language),
      ],
      [
        "About",
        route("admin.manage.content.homepage.section.about", content_language),
      ],
      [
        "Why Choose Us",
        route(
          "admin.manage.content.homepage.section.whyChooseUs",
          content_language
        ),
      ],
      [
        "How it works",
        route(
          "admin.manage.content.homepage.section.howItWorks",
          content_language
        ),
      ],
      [
        "Footer",
        route("admin.manage.content.homepage.section.footer", content_language),
      ],
    ]);

    this.add(__("Frequently Asked Questions"), [
      [
        __("Categories"),
        route("admin.manage.content.faqCategories.index", content_language),
      ],
      [
        __("Questions"),
        route("admin.manage.content.faqQuestions.index", content_language),
      ],
    ]);

    this.add(
      __("Client Testimonials"),
      route("admin.manage.content.testimonials.index", content_language)
    );

    // Menu
    this.add(__("System Pages"), [
      [
        __("Home"),
        route("admin.manage.content.systemPages.home", content_language),
      ],
      [
        __("Blog"),
        route("admin.manage.content.systemPages.blog", content_language),
      ],
      [
        __("FAQ"),
        route("admin.manage.content.systemPages.faq", content_language),
      ],
      [
        __("Contact Us"),
        route("admin.manage.content.systemPages.contact", content_language),
      ],
      [
        __("Login"),
        route("admin.manage.content.systemPages.login", content_language),
      ],
      [
        __("Registration"),
        route(
          "admin.manage.content.systemPages.registration",
          content_language
        ),
      ],
      [
        __("Forgot Password"),
        route(
          "admin.manage.content.systemPages.forgotPassword",
          content_language
        ),
      ],
      [
        __("Author Application"),
        route(
          "admin.manage.content.systemPages.authorApplication",
          content_language
        ),
      ],
    ]);

    // Menu
    this.add(
      __("Menu Items"),
      route("admin.manage.content.websiteMenus.index", content_language)
    );

    this.add(
      __("Custom Website Pages"),
      route("admin.manage.content.websitePages.index", content_language)
    );

    if (content_language != "en") {
      this.add(
        __("Translation"),
        route("admin.manage.content.systemTranslations.index", content_language)
      );
      // this.add(__("Translation"), [
      //   [
      //     __("Home"),
      //     route("admin.manage.content.systemPages.home", content_language),
      //   ],
      //   [
      //     __("Blog"),
      //     route("admin.manage.content.systemPages.blog", content_language),
      //   ],
      // ]);
    }

    this.add(__("Blog"), [
      [
        __("Categories"),
        route("admin.manage.content.postCategories.index", content_language),
      ],
      [
        __("Articles"),
        route("admin.manage.content.posts.index", content_language),
      ],
    ]);
  },
  data() {
    return {
      menuItems: [],
    };
  },
  methods: {
    add(name, childrenOrUrl) {
      let scope = this;
      let menu_item = null;
      if (typeof childrenOrUrl == "object") {
        let children = childrenOrUrl;
        let child = [];
        for (let index = 0; index < children.length; index++) {
          const element = children[index];
          child.push(scope.prepare(element[0], element[1]));
        }

        menu_item = scope.prepare(name, null, true, child);
      } else {
        let url = childrenOrUrl;
        menu_item = scope.prepare(name, url, false, null);
      }
      this.menuItems.push(menu_item);
    },
    prepare(label, url, has_children, children) {
      return {
        label: label,
        url: url,
        has_children: has_children,
        children: children,
      };
    },
  },
};
</script>