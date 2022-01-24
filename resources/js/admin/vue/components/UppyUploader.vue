<template>
    <div class="drag-drop-area"></div>
</template>

<script>
import Uppy from "@uppy/core";
import XHRUpload from "@uppy/xhr-upload";
import Dashboard from "@uppy/dashboard";

export default {
    props: {
        csrfToken: {
            type: String,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },

    data: function() {
        return {
            uppy: null
        };
    },

    mounted: function() {
        this.instantiateUppy();
    },

    methods: {
        instantiateUppy: function() {
            this.uppy = Uppy({
                debug: true
            })
                .use(Dashboard, {
                    target: ".drag-drop-area",
                    inline: true
                })
                .use(XHRUpload, {
                    endpoint: this.url,
                    formData: true,
                    fieldName: "file",
                    headers: {
                        "X-CSRF-TOKEN": this.csrfToken
                    }
                });

            uppy.on("complete", result => {
                console.log(
                    "Upload complete! We’ve uploaded these files:",
                    result.successful
                );
            });
        }
    }
};
</script>
