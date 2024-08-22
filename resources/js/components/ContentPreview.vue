<template>
  <div class="w-100 border p-2 content-box">
    <h6 class="bg-light p-2">{{ __("Content Preview") }}</h6>
    <hr />
    <div v-if="content_preview">
      <div style="overflow-x: hidden; overflow-y: auto; height: 500px">
        <h1 class="h4 ps-4 pe-4">{{ content_preview.title }}</h1>
        <hr />
        <div
          class="nl2br mt-4 ps-4 pe-4"
          ref="contentBody"
          id="contentBody"
          v-html="content_preview.content"
        ></div>
      </div>
      <div class="popup-tag" ref="commentBox">
        <textarea
          class="form-control"
          rows="2"
          placeholder="Add your comment"
          v-model="message"
          @keydown.enter.exact.prevent="sendMessage(message)"
        ></textarea>
        <div class="row mt-5" style="font-size: 12px">
          <div class="col-4 text-right">{{ __("Esc to cancel") }}</div>
          <div class="col-8 text-right">
            {{ __("Press Enter to add comments") }}
          </div>
        </div>
      </div>
    </div>
    <div class="text-center align-middle" v-else>
      {{ __("No content to preview at this moment") }}
    </div>
  </div>
</template>
  
  <script>
export default {
  props: ["content_preview", "url_post_comment"],
  mounted() {
    let scope = this;
    if (this.$refs["commentBox"]) {
      scope.commentBoxStyle = this.$refs["commentBox"].style;
      document
        .getElementById("contentBody")
        .addEventListener("mouseup", (event) => {
          scope.unwrapHighlightedText();
          this.textSelected(event);
        });

      document.addEventListener("keydown", function (e) {
        if (e.key == "Escape") {
          scope.unwrapHighlightedText();
          scope.hideCommentBox(scope);
        }
      });
    }
  },
  data() {
    return {
      message: null,
      comment: null,
      selectedText: null,
      commentBoxStyle: null,
    };
  },
  methods: {
    unwrapHighlightedText() {
      document
        .querySelectorAll(".highlight")
        .forEach((EL) => EL.replaceWith(...EL.childNodes));
    },
    hideCommentBox(scope) {
      scope.selectedText = null;
      scope.message = null;
      scope.commentBoxStyle.display = "none";
    },
    sendMessage(message) {
      if (message != null && message != "" && message.length > 0) {
        let scope = this;
        axios
          .post(this.url_post_comment, {
            message: message,
            quote: this.selectedText,
          })
          .then(function (res) {
            scope.showAlertMessage(scope.__("Comment Sent"));
          });

        this.message = null;
        // Remove highlight
        this.unwrapHighlightedText();

        // Hide popup box
        this.hideCommentBox(this);
      }
    },
    textSelected(event) {
      var selection = window.getSelection().toString();

      let commentBoxStyle = this.commentBoxStyle;

      if (selection != "" && selection.length > 0) {
        var txt = window.getSelection(),
          range = txt.getRangeAt(0),
          boundary = range.getBoundingClientRect();
        // Highlight text
        var span = document.createElement("span");
        span.className = "highlight";
        span.appendChild(range.extractContents());
        range.insertNode(span);

        commentBoxStyle.display = "block";
        commentBoxStyle.top = event.pageY + 12 + "px";
        commentBoxStyle.left = boundary.left + "px";

        this.selectedText = selection;
      } else {
        //commentBoxStyle.display = "none";
        this.hideCommentBox(this);
      }
    },
  },
};
</script>
  
  
  <style scoped>
.popup-tag {
  position: absolute;
  display: none;
  background: #fff;
  color: #222;
  padding: 10px;
  font-weight: bold;
  text-decoration: underline;
  cursor: pointer;
  -webkit-filter: drop-shadow(0 1px 10px rgba(113, 158, 206, 0.8));

  box-shadow: 0 5px 20px rgb(0 0 0 / 50%);
  border-radius: 4px;
  border: 1px solid #dddee0;
  min-width: 300px;
  z-index: 999;
}

.popup-tag textarea {
  display: block;
  box-sizing: border-box;
  background-color: #fff;
  color: #4e555f;
  font-family: inherit;
  font-size: 14px;
  border: 1px solid #dddee0;
  width: 100%;
  height: 36px;
  margin: 0 0 1rem;
  padding: 6px 8px;
  border-radius: 4px;
  box-shadow: none;
  background-clip: padding-box;
  outline: none;
  line-height: 1.3125rem;
  min-height: 7.125rem;
  resize: none;
}
</style>