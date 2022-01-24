<template>
  <div>
    <label :for="name" @click="setFocusOnCkEditor">{{ label }}</label>
    <ckeditor :editor="editor" :config="editorConfig" v-model="value" ref="editorInput"></ckeditor>
    <input type="hidden" :name="name" v-model="value" />
    <a
      :href="assetsPageUrl"
      target="_blank"
      rel="noreferrer"
      class="underline text-endeavour"
    >Assets</a>
  </div>
</template>

<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-editor-classic/src/classiceditor";

import Essentials from "@ckeditor/ckeditor5-essentials/src/essentials";
import Alignment from "@ckeditor/ckeditor5-alignment/src/alignment";
import Bold from "@ckeditor/ckeditor5-basic-styles/src/bold";
import Italic from "@ckeditor/ckeditor5-basic-styles/src/italic";
import Underline from "@ckeditor/ckeditor5-basic-styles/src/underline";
import StrikeThrough from "@ckeditor/ckeditor5-basic-styles/src/strikethrough";
import Subscript from "@ckeditor/ckeditor5-basic-styles/src/subscript";
import Superscript from "@ckeditor/ckeditor5-basic-styles/src/superscript";
import HorizontalLine from "@ckeditor/ckeditor5-horizontal-line/src/horizontalline";
import Link from "@ckeditor/ckeditor5-link/src/link";
import List from "@ckeditor/ckeditor5-list/src/list";
import Paragraph from "@ckeditor/ckeditor5-paragraph/src/paragraph";
import Heading from "@ckeditor/ckeditor5-heading/src/heading";
import Image from "@ckeditor/ckeditor5-image/src/image";
import ImageStyle from "@ckeditor/ckeditor5-image/src/imagestyle";
import ImageToolbar from "@ckeditor/ckeditor5-image/src/imagetoolbar";
import ImageUpload from "@ckeditor/ckeditor5-image/src/imageupload";
import RemoveFormat from "@ckeditor/ckeditor5-remove-format/src/removeformat";
import SimpleUploadAdapter from "@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter";
import Table from "@ckeditor/ckeditor5-table/src/table";
import TableToolbar from "@ckeditor/ckeditor5-table/src/tabletoolbar";
import TableProperties from "@ckeditor/ckeditor5-table/src/tableproperties";
import TableCellProperties from "@ckeditor/ckeditor5-table/src/tablecellproperties";
import LinkImage from "@ckeditor/ckeditor5-link/src/linkimage";

export default {
  components: {
    ckeditor: CKEditor.component,
  },

  props: {
    csrfToken: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    initialValue: {
      type: String,
      default: "",
    },
    assetsPageUrl: {
      type: String,
      required: true,
    },
  },

  data: function () {
    return {
      editor: ClassicEditor,
      value: this.initialValue,
      editorConfig: {
        plugins: [
          Essentials,
          Alignment,
          Bold,
          Italic,
          Underline,
          StrikeThrough,
          Superscript,
          Subscript,
          HorizontalLine,
          Link,
          LinkImage,
          List,
          Heading,
          Paragraph,
          Image,
          ImageStyle,
          ImageToolbar,
          ImageUpload,
          RemoveFormat,
          SimpleUploadAdapter,
          Table,
          TableToolbar,
          TableProperties,
          TableCellProperties,
        ],
        toolbar: [
          "heading",
          "|",
          "alignment",
          "bold",
          "italic",
          "link",
          "underline",
          "strikethrough",
          "superscript",
          "subscript",
          "|",
          "bulletedList",
          "numberedList",
          "|",
          "horizontalLine",
          "|",
          "imageUpload",
          "|",
          "removeFormat",
          "|",
          "insertTable",
        ],
        simpleUpload: {
          uploadUrl: "/api/admin/wysiwyg-file-uploads",
          // Headers sent along with the XMLHttpRequest to the upload server.
          headers: {
            "X-CSRF-TOKEN": this.csrfToken,
          },
        },
        table: {
          contentToolbar: [
            "tableColumn",
            "tableRow",
            "mergeTableCells",
            "tableProperties",
            "tableCellProperties",
          ],
        },
        heading: {
          options: [
            {
              model: "paragraph",
              title: "Paragraph",
              class: "wysiwyg-paragraph",
            },
            {
              model: "introParagraph",
              view: {
                name: "p",
                classes: "intro",
              },
              title: "Intro Paragraph",
              class: "wysiwyg-intro-paragraph",

              // It needs to be converted before the standard 'heading2'.
              converterPriority: "high",
            },
            {
              model: "heading1",
              view: "h1",
              title: "Heading 1",
              class: "wysiwyg-h1",
            },
            {
              model: "heading2",
              view: "h2",
              title: "Heading 2",
              class: "wysiwyg-h2",
            },
            {
              model: "heading3",
              view: "h3",
              title: "Heading 3",
              class: "wysiwyg-h3",
            },
            {
              model: "heading4",
              view: "h4",
              title: "Heading 4",
              class: "wysiwyg-h4",
            },
            {
              model: "heading5",
              view: "h5",
              title: "Heading 5",
              class: "wysiwyg-h5",
            },
          ],
        },
        image: {
          // You need to configure the image toolbar, too, so it uses the new style buttons.
          toolbar: [
            "imageTextAlternative",
            "|",
            "imageStyle:full",
            "imageStyle:alignLeft",
            "imageStyle:alignCenter",
            "imageStyle:alignRight",
          ],

          styles: [
            // This option is equal to a situation where no style is applied.
            "full",
            // This represents an image aligned to the left.
            "alignLeft",
            "alignCenter",
            "alignRight",
          ],
        },
        link: {
          decorators: {
            openInNewTab: {
              mode: 'manual',
              label: 'Open in a new tab',
              attributes: {
                target: '_blank',
                rel: 'noopener noreferrer'
              }
            }
          }
        },
      },
    };
  },

  methods: {
    setFocusOnCkEditor() {
      this.$refs.editorInput.instance.editing.view.focus();
    },
  },
};
</script>
